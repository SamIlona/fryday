<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin\Controller;

use Admin\Form;

use Admin\Entity;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use Zend\Console\Request as ConsoleRequest;


/**
 * Subscriber controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class SubscriberController extends Action
{
    protected function getUploadPath()
    {
        $config = $this->getServiceLocator()->get('Config');
        $uploadDir = $config['path_to_data_files']['csv'];

        if (!is_dir($uploadDir )) 
        {
            $oldmask = umask(0);
            mkdir($uploadDir , 0777);
            umask($oldmask);
        }

        return $uploadDir;
    }

    /**
     * List All Users
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $subscribers = $this->entityManager->getRepository('Admin\Entity\Subscriber')->findAll();

        return array(
            'subscribers' => $subscribers,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createAction()
    {
        $this->entityManager = $this->getEntityManager();

        $createSubscriberForm = new Form\CreateSubscriberForm('create-subscriber-form', $this->entityManager);
        $createSubscriberForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\Subscriber'));
        
        $subscriberEntity = new Entity\Subscriber();
        $createSubscriberForm->bind($subscriberEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();

            $createSubscriberForm->setData($post);

            if($createSubscriberForm->isValid()) 
            {
                $data = $createSubscriberForm->getData();

                $this->entityManager->persist($subscriberEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator/default', array('controller' => 'subscriber', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createSubscriberForm,
        );
    }

    public function csvParseTextAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();

        $csvParseForm = new Form\CsvParseTextForm('create-subscriber-form', $em);

        if($request->isPost())
        {
            $post = $request->getPost();

            $csvParseForm->setData($post);

            if($csvParseForm->isValid()) 
            {
                $data = $csvParseForm->getData();

                $csvText    = $data['csvText'];
                $cityID     = $data['city'];
                $city       = $em->getRepository('Admin\Entity\City')->getCityByID($cityID);
                $rows       = explode("\n", $csvText);

                foreach($rows as $row => $data)
                {
                    //get row data
                    if(strlen($data) > 1)
                    {
                        $row_data = explode(',', $data);

                        $info[$row]['email']        = $row_data[0];
                        $info[$row]['firstName']    = $row_data[1];
                        $info[$row]['company']      = $row_data[2];
                        $info[$row]['lastName']     = $row_data[3];
                        $info[$row]['phone']        = $row_data[4];
                        $info[$row]['position']     = $row_data[5];
                        $info[$row]['city']         = $row_data[6];

                        if(($subscriber = $em
                            ->getRepository('Admin\Entity\Subscriber')
                            ->getSubscriberByEmail($info[$row]['email'])) == null)
                        {
                            $subscriberEntity = new Entity\Subscriber();

                            $subscriberEntity->setFirstName($info[$row]['firstName']);
                            $subscriberEntity->setLastName($info[$row]['lastName']);
                            $subscriberEntity->setEmail($info[$row]['email']);
                            $subscriberEntity->setPhone($info[$row]['phone']);
                            $subscriberEntity->setCompany($info[$row]['company']);
                            $subscriberEntity->setPosition($info[$row]['position']);

                            $subscriberEntity->setCity($city);

                            $em->persist($subscriberEntity);
                            $em->flush();
                        }
                        else
                        {
                            $this->flashMessenger()->addMessage('<strong> - ' . $info[$row]['email'] . '</strong> ' 
                                . $info[$row]['firstName'] . " "
                                . $info[$row]['lastName'] . " "
                                . $info[$row]['company'] . " "
                                . $info[$row]['phone'] . " "
                                . $info[$row]['position'] );
                        }
                    }
                }
                // var_dump($info);
                return $this->redirect()->toRoute('administrator/default', array('controller' => 'subscriber', 'action' => 'index'));
            }
        }

        return array(
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
            'form' => $csvParseForm,
        );
    }

    public function csvParseFileAction()
    {
        $em = $this->getEntityManager();
        $uploadDir = $this->getUploadPath();

        // phpinfo();

        $csvParseForm = new Form\CsvParseFileForm('csv-parse-file-form', $em, $uploadDir);

        // shell_exec('php public/index.php get happen --verbose apache2 ' . "> /dev/null 2>/dev/null &");

        // var_dump($var);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            // $post = $request->getPost();

            $csvParseForm->setData($post);

            if($csvParseForm->isValid()) 
            {
                $dataForm       = $csvParseForm->getData();

                $cityID         = $dataForm['city'];
                $targetFile     = '/var/www/html/fryday/' . $dataForm['csvFile']['tmp_name'];
                $shell          = 'php public/index.php parse file ' . $targetFile . " " . $cityID . 
                    ' >' . $targetFile . '.log 2>/var/www/html/fryday/data/csv/errlog.txt &';

                shell_exec($shell);

                $this->flashMessenger()->addMessage('Importing started in background');

                return $this->redirect()->toRoute('administrator/default', array('controller' => 'subscriber', 'action' => 'index'));
            }
        }

        return array(
            'form' => $csvParseForm,
        );
    }

    public function doParseFileConsoleAction()
    {
        $request = $this->getRequest();
        $em = $this->getEntityManager();
        $uploadDir = $this->getUploadPath();

        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $fileName = $request->getParam('filename', false);
        $cityID = $request->getParam('cityid', false);

        if (!file_exists($fileName))
            echo "The file $fileName does not exist\n";

        $handle = @fopen($fileName, "r");
        if (! $handle)
            throw new \RuntimeException("Could not open the file!");

        // echo $handle;

        if ($handle) {
            while (($data = fgets($handle, 4096)) !== false) {
                if(strlen($data) > 1)
                {
                    $row_data = explode(',', $data);

                    $email      = $row_data[0];
                    if(filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        $firstName  = $row_data[1];
                        $company    = $row_data[2];
                        $lastName   = $row_data[3];
                        $phone      = $row_data[4];
                        $position   = $row_data[5];
                        // $cityID     = $row_data[6];
                        $city       = $em->getRepository('Admin\Entity\City')->getCityByID($cityID);

                        if(($subscriber = $em->getRepository('Admin\Entity\Subscriber')->getSubscriberByEmail($email)) == null)
                        {
                            $subscriberEntity = new Entity\Subscriber();

                            $subscriberEntity->setFirstName($firstName);
                            $subscriberEntity->setLastName($lastName);
                            $subscriberEntity->setEmail($email);
                            $subscriberEntity->setPhone($phone);
                            $subscriberEntity->setCompany($company);
                            $subscriberEntity->setPosition($position);
                            $subscriberEntity->setCity($city);

                            $em->persist($subscriberEntity);
                            $em->flush();

                            echo $email . " successfully added to the Fryday database! \n";
                        }
                        else
                        {
                            echo "FAIL! " . $email . " already exist in the Fryday database! \n";
                        }
                    } 
                    else
                    {
                        echo "FAIL! " . $email . " not valid! \n";
                    }
                }
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
    }
}