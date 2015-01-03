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
class NewsletterController extends Action
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
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createAction()
    {
        $this->entityManager = $this->getEntityManager();

        $createNewsletterForm = new Form\CreateNewsletterForm('create-subscriber-form', $this->entityManager);
        // $createSubscriberForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\Subscriber'));
        
        // $subscriberEntity = new Entity\Subscriber();
        // $createSubscriberForm->bind($subscriberEntity);

        // $request = $this->getRequest();
        // if($request->isPost())
        // {
        //     // // Make certain to merge the files info!
        //     // $post = array_merge_recursive(
        //     //     $request->getPost()->toArray(),
        //     //     $request->getFiles()->toArray()
        //     // );

        //     $post = $request->getPost();

        //     $createSubscriberForm->setData($post);

        //     if($createSubscriberForm->isValid()) 
        //     {
        //         $data = $createSubscriberForm->getData();

        //         // var_dump($data);

        // //         $profileImage = $data->getProfileImage();
        // //         $urlProfileImage = explode("./public", $profileImage['tmp_name']);
        // //         $userEntity->setProfileImage($urlProfileImage[1]);

        //         $this->entityManager->persist($subscriberEntity);
        //         $this->entityManager->flush();

        //         return $this->redirect()->toRoute('administrator/default', array('controller' => 'subscriber', 'action' => 'index'));
        //     }
        // }

        return array(
            'form' => $createNewsletterForm,
        );
    }
}