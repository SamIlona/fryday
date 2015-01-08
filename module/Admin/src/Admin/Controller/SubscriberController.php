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


/**
 * Subscriber controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class SubscriberController extends Action
{
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
            // // Make certain to merge the files info!
            // $post = array_merge_recursive(
            //     $request->getPost()->toArray(),
            //     $request->getFiles()->toArray()
            // );

            $post = $request->getPost();

            $createSubscriberForm->setData($post);

            if($createSubscriberForm->isValid()) 
            {
                $data = $createSubscriberForm->getData();

                // var_dump($data);

        //         $profileImage = $data->getProfileImage();
        //         $urlProfileImage = explode("./public", $profileImage['tmp_name']);
        //         $userEntity->setProfileImage($urlProfileImage[1]);

                $this->entityManager->persist($subscriberEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator/default', array('controller' => 'subscriber', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createSubscriberForm,
        );
    }

    public function csvParseAction()
    {
        $em = $this->getEntityManager();

        $csvParseForm = new Form\CsvParseForm('create-subscriber-form', $em);

        $request = $this->getRequest();
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
}