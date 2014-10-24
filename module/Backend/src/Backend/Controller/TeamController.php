<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Controller;

use Backend\Form;

use Backend\Entity;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


/**
 * Venue controller
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Controller
 */
class TeamController extends Action
{
    /**
     * List All Users
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $users = $this->entityManager->getRepository('Backend\Entity\User')->findAll();

        return array(
            'users' => $users,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createAction()
    {
        $this->entityManager = $this->getEntityManager();

        $createUserForm = new Form\CreateUserForm('create-user-form', $this->entityManager);
        $createUserForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Backend\Entity\User'));
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

        //     // var_dump($post);

            $createUserForm->setData($post);

            if($createUserForm->isValid()) 
            {
                $data = $createUserForm->getData(); // $data is a Entity\Venue object
                $profileImage = $data->getProfileImage();
                $userEntity->setProfileImage($profileImage['tmp_name']);

                $this->entityManager->persist($userEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('backend/default', array('controller' => 'team', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createUserForm,
        );
    }
}