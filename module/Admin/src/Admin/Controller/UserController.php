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
    protected $_dir = null;

    protected function getUploadPath()
    {
        $config = $this->getServiceLocator()->get('Config');
        $uploadDir = $config['path_to_uploads']['user'];

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
        $users = $this->entityManager->getRepository('Admin\Entity\User')->findAll();

        return array(
            'users'             => $users,
            'filesDir'          => end(explode("public", $this->getUploadPath())),
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createFirstStepAction()
    {
        $em = $this->getEntityManager();
        // $user = $this->getAuthenticatedUser();
        $uploadDir = $this->getUploadPath();

        $createUserForm = new Form\CreateUserFirstStepForm('create-user-form', $em);
        $createUserForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\User'));
        $userEntity = new Entity\User();
        $createUserForm->bind($userEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();

            $createUserForm->setData($post);

            if($createUserForm->isValid()) 
            {
                $data = $createUserForm->getData();

                $em->persist($userEntity);
                $em->flush();

                $user = $em->getRepository('Admin\Entity\User')->getLastAddedUser();
                $userID = $user->getId();
                $currentUserUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $userID;
                if (!is_dir($currentUserUploadDir)) 
                {
                    $oldmask = umask(0);
                    mkdir($currentUserUploadDir, 0777);
                    umask($oldmask);
                }

                return $this->redirect()->toRoute('administrator/default', array('controller' => 'user', 'action' => 'create-second-step', 'id' => $userID));
            }
        }

        return array(
            'form' => $createUserForm,
            'filesDir' => end(explode("public", $this->getUploadPath())),
        );
    }

    public function createSecondStepAction()
    {
        $em = $this->getEntityManager();
        $userID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUserUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $userID;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        $createUserForm = new Form\CreateUserSecondStepForm('create-user-form', $em, $currentUserUploadDir);
        $createUserForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\User'));
        $userEntity = $em->getRepository('Admin\Entity\User')->findOneBy(array('id' => $userID));
        $createUserForm->bind($userEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $createUserForm->setData($post);

            if($createUserForm->isValid()) 
            {
                $dataForm           = $createUserForm->getData();
                $imageData          = $dataForm->getImage();
                $imageName          = end(explode("$currentUserUploadDir". DIRECTORY_SEPARATOR, $imageData['tmp_name']));
                $thumb              = $thumbnailer->create($imageData['tmp_name'], $options = array(), $plugins = array()); 
                $currentDimantions  = $thumb->getCurrentDimensions();

                if($post['x'] === '' ||
                    $post['y'] === '') 
                {
                    if($currentDimantions['height'] / $currentDimantions['width'] < 0.5)
                    {
                        $thumb->cropFromCenter($currentDimantions['width']);
                    }
                    else 
                    {
                        $thumb->cropFromCenter($currentDimantions['height']);
                    }
                }
                else 
                {
                    $scale = $currentDimantions['width'] / $post['cw'];

                    $thumb->crop($post['x'] * $scale, 
                                 $post['y'] * $scale,
                                 $post['w'] * $scale, 
                                 $post['h'] * $scale
                                );
                }

                $thumb->resize(264, 264);
                $resizedImg1 = $currentUserUploadDir . DIRECTORY_SEPARATOR . 'square264x264_' . $imageName;
                $thumb->save($resizedImg1);

                $thumb->resize(60, 60);
                $resizedImg2 = $currentUserUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName;    
                $thumb->save($resizedImg2);

                $userEntity->setImage($imageName);

                $this->entityManager->persist($userEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'user', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createUserForm,
            'userID' => $userID,
            'filesDir' => end(explode("public", $this->getUploadPath())),
        );
    }

    public function profileAction()
    {
        $em = $this->getEntityManager();
        $user = $this->getAuthenticatedUser();
        return array(
            'user' => $user,
            'filesDir' => end(explode("public", $this->getUploadPath())),
        );
    }

}