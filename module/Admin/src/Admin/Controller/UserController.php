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
 * Venue controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class UserController extends Action
{
    /**
     * List All Users
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $users = $this->entityManager->getRepository('Admin\Entity\User')->findAll();

        return array(
            'users' => $users,
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createAction()
    {
        $this->entityManager = $this->getEntityManager();

        $createUserForm = new Form\CreateUserForm('create-user-form', $this->entityManager);
        $createUserForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\User'));
        $userEntity = new Entity\User();
        $createUserForm->bind($userEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $createUserForm->setData($post);

            if($createUserForm->isValid()) 
            {
                $data = $createUserForm->getData();

                // var_dump($data);

                $profileImage = $data->getProfileImage();
                $urlProfileImage = explode("./public", $profileImage['tmp_name']);
                $userEntity->setProfileImage($urlProfileImage[1]);

                $this->entityManager->persist($userEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator/default', array('controller' => 'user', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createUserForm,
        );
    }
}