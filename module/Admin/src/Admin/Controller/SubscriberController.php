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

// Pagination
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Doctrine\Common\Collecttions\Criteria as DoctrineCriteria; // for criteria
use Zend\Paginator\Paginator;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

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
        $page = $this->params()->fromRoute('page', 1);
        # move to service
        $limit = 500;
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pagedSubscribers = $em->getRepository('Admin\Entity\Subscriber')->getPagedSubscribers($offset, $limit);

        return array(
            'pagedSubscribers' => $pagedSubscribers,
            'page' => $page,
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'limit' => $limit,
        );
        # end move to service
        // $viewModel = new ViewModel();
        // $viewModel->setVariable( 'pagedSubscribers', $pagedSubscribers );
        // $viewModel->setVariable( 'page', $page );

        // return $viewModel;
        // $em = $this->getEntityManager();

        // // $adapter = new SelectableAdapter($em->getRepository('Admin\Entity\Subscriber'));
        // // $paginator = new Paginator($adapter);
        // // $page = 1;
        // // if ($this->params()->fromRoute('page')) 
        // //     $page = $this->params()->fromRoute('page');
        // // $paginator->setCurrentPageNumber((int)$page)->setItemCountPerPage(500); 
        // // $subscribers = $this->entityManager->getRepository('Admin\Entity\Subscriber')->findAll();

        // $paginator = $em->getRepository('Admin\Entity\Subscriber')->getPagedUsers();

        // return array(
        //     'paginator' => $paginator,
        //     'flashMessages' => $this->flashMessenger()->getMessages(),
        // );
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

                // $data = file($targetFile);
                // $lines = count($data);
                // var_dump($lines);

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
        $request                = $this->getRequest();
        $em                     = $this->getEntityManager();
        $uploadDir              = $this->getUploadPath();
        $subscriberRepository   = $em->getRepository('Admin\Entity\Subscriber');
        $cityRepository         = $em->getRepository('Admin\Entity\City');

        if (!$request instanceof ConsoleRequest)
            throw new \RuntimeException('You can only use this action from a console!');

        $fileName   = $request->getParam('filename', false);
        $cityID     = $request->getParam('cityid', false);

        $city       = $cityRepository->getCityByID($cityID);

        // $data = file($fileName);
        // $lines = count($data);
        // // var_dump($lines);
        // $x=0;
        // while($x<=$lines)
        // {
        //     $time = new \Datetime();
        //     $row_data = explode(',', $data[$x]);
        //     $x++;

        //     if(filter_var($row_data[0], FILTER_VALIDATE_EMAIL))
        //     {
        //         if(($subscriber = $subscriberRepository->getSubscriberByEmail($row_data[0])) == null)
        //         {
        //             $subscriberEntity = new Entity\Subscriber();

        //             $subscriberEntity->setEmail($row_data[0]);
        //             $subscriberEntity->setFirstName($row_data[1]);
        //             $subscriberEntity->setCompany($row_data[2]);
        //             $subscriberEntity->setLastName($row_data[3]);
        //             $subscriberEntity->setPhone($row_data[4]);
        //             $subscriberEntity->setPosition($row_data[5]);
        //             $subscriberEntity->setCity($city);

        //             $em->persist($subscriberEntity);
        //             $em->flush();

        //             echo '[' . $time->format("Y-m-d H:i:s") . "] " . 
        //                 $row_data[0] . " successfully added to the Fryday database! \n";
        //         }
        //         else
        //         {
        //             echo "FAIL! [" . $time->format("Y-m-d H:i:s") . "] " . 
        //                 $row_data[0] . " already exist in the Fryday database! \n";
        //         }
        //     } 
        //     else
        //     {
        //         echo "FAIL! [" . $time->format("Y-m-d H:i:s") . "] " . 
        //             $row_data[0] . " not valid! \n";
        //     }
        // }

        if (!file_exists($fileName))
            echo "The file $fileName does not exist\n";

        $handle = @fopen($fileName, "r");
        if (! $handle)
            throw new \RuntimeException("Could not open the file!");

        if ($handle) {
            while (($data = fgets($handle, 4096)) !== false) {
                $time = new \Datetime();
                $row_data = explode(',', $data);
                /*
                $row_data[0] - email
                $row_data[1] - $firstName
                $row_data[2] - $company
                $row_data[3] - $lastName
                $row_data[4] - $phone
                $row_data[5] - $position
                $row_data[6] - $city
                */
                if(filter_var($row_data[0], FILTER_VALIDATE_EMAIL))
                {
                    if(($subscriber = $subscriberRepository->isSubscriberExistByEmail($row_data[0])) == null)
                    {
                        $subscriberEntity = new Entity\Subscriber();

                        $subscriberEntity->setEmail($row_data[0]);
                        $subscriberEntity->setFirstName($row_data[1]);
                        $subscriberEntity->setCompany($row_data[2]);
                        $subscriberEntity->setLastName($row_data[3]);
                        $subscriberEntity->setPhone($row_data[4]);
                        $subscriberEntity->setPosition($row_data[5]);
                        $subscriberEntity->setCity($city);

                        $em->persist($subscriberEntity);
                        $em->flush();

                        echo '[' . $time->format("Y-m-d H:i:s") . "] " . 
                            $row_data[0] . " successfully added to the Fryday database! \n";
                    }
                    else
                    {
                        echo "FAIL! [" . $time->format("Y-m-d H:i:s") . "] " . 
                            $row_data[0] . " already exist in the Fryday database! \n";
                    }
                } 
                else
                {
                    echo "FAIL! [" . $time->format("Y-m-d H:i:s") . "] " . 
                        $row_data[0] . " not valid! \n";
                }
            }

            if (!feof($handle))
                echo "Error: unexpected fgets() fail\n";

            fclose($handle);
        }
    }
}