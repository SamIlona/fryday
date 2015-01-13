<?php
/**
 * @category   Fryday_Application
 * @package    
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
 * City controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class CityController extends Action
{
    protected $_dir = null;

    protected function getUploadPath()
    {
        $config = $this->getServiceLocator()->get('Config');
        $uploadDir = $config['path_to_uploads']['city'];

        if (!is_dir($uploadDir )) 
        {
            $oldmask = umask(0);
            mkdir($uploadDir , 0777);
            umask($oldmask);
        }

        return $uploadDir;
    }

	/**
     * List All Cities
     *
     * @return \Zend\View\Model\ViewModel|array
     */
	public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $cities = $this->entityManager->getRepository('Admin\Entity\City')->findAll();

        return array(
            'cities' => $cities,
            'filesDir' => end(explode("public", $this->getUploadPath())),
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createFirstStepAction()
    {
        $em = $this->getEntityManager();
        $uploadDir = $this->getUploadPath();

        $cityForm = new Form\CreateCityFirstStepForm('new-city-form', $em);
        $cityForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\City'));
        $cityEntity = new Entity\City();
        $cityForm->bind($cityEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();

            $cityForm->setData($post);

            if($cityForm->isValid()) 
            {
                $data = $cityForm->getData();

                $this->entityManager->persist($cityEntity);
                $this->entityManager->flush();

                $city = $em->getRepository('Admin\Entity\City')->getLastAddedCity();
                $cityID = $city->getId();
                $currentPartnerUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $cityID;               
                if (!is_dir($currentPartnerUploadDir )) 
                {
                    $oldmask = umask(0);
                    mkdir($currentPartnerUploadDir, 0777);
                    umask($oldmask);
                }

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'city', 'action' => 'create-second-step', 'id' => $cityID));
            }
        }

        return array(
            'form' => $cityForm,
        );
    }

    public function createSecondStepAction()
    {
    	$em = $this->getEntityManager();
        $user = $this->getAuthenticatedUser();
        $cityID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentCityUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $cityID;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        $createCityForm = new Form\CreateCitySecondStepForm('new-city-form', $em, $user, $currentCityUploadDir);
        $createCityForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\City'));
        $cityEntity = $em->getRepository('Admin\Entity\City')->findOneBy(array('id' => $cityID));
        $createCityForm->bind($cityEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $createCityForm->setData($post);

            if($createCityForm->isValid()) 
            {
                $dataForm           = $createCityForm->getData();
                
                $imageData          = $dataForm->getImage();
                $imageName          = end(explode("$currentCityUploadDir". DIRECTORY_SEPARATOR, $imageData['tmp_name']));
                $thumb              = $thumbnailer->create($imageData['tmp_name'], $options = array(), $plugins = array());
                $thumb_square       = $thumbnailer->create($imageData['tmp_name'], $options = array(), $plugins = array());  
                $currentDimantions  = $thumb->getCurrentDimensions();

                if($post['x'] === '' ||
                    $post['y'] === '') 
                {
                    if($currentDimantions['height'] / $currentDimantions['width'] < 0.5)
                        $thumb->cropFromCenter($currentDimantions['height'] * 2, $currentDimantions['height']);
                    else 
                        $thumb->cropFromCenter($currentDimantions['width'], $currentDimantions['width'] / 2);
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

                $thumb->resize(640, 320);
                $resizedImg = $currentCityUploadDir . DIRECTORY_SEPARATOR . 'rect640x320_' . $imageName;
                $thumb->save($resizedImg);

                $thumb->resize(224, 112);
                $mailImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName;    
                $thumb->save($mailImg);

                if($currentDimantions['height'] / $currentDimantions['width'] < 1)
                    $thumb_square->cropFromCenter($currentDimantions['height'], $currentDimantions['height']);
                else 
                    $thumb_square->cropFromCenter($currentDimantions['width'], $currentDimantions['width']);

                $thumb_square->resize(60, 60);
                $mailImg = $currentCityUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName;    
                $thumb_square->save($mailImg);

                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'rect640x320_' . $imageName, 0777);
                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName, 0777); 
                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName, 0777); 

                $cityEntity->setImage($imageName);

                $cityEntity->setLabel($dataForm->getName());
                $cityEntity->setRoute('/' . strtolower($dataForm->getCountry()->getName()) . '/' . strtolower($dataForm->getName()));

                $this->entityManager->persist($cityEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'city', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createCityForm,
            'cityID' => $cityID,
        );
    }

    public function viewAction()
    {
        $em = $this->getEntityManager();
        $authUser = $this->getAuthenticatedUser();
        $request = $this->getRequest();
        $cityID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $cityID;
        $cityEntity = $em->getRepository('Admin\Entity\City')->findOneBy(array('id' => $cityID));
        $linkUserToCityForm = new Form\LinkUserToCityForm('link-user-to-city-form', $em, $authUser, $cityID);
        $usersLinkedToCity = $em->getRepository('Admin\Entity\LinkCityUser')->getUsersLinkedToCity($cityID);

        if($request->isPost())
        {
            $post = $request->getPost();

            $linkUserToCityForm->setData($post);

            if($linkUserToCityForm->isValid()) 
            {
                $postDataForm = $linkUserToCityForm->getData();

                $linkUserToCityForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\LinkCityUser'));
                $linkCityUserEntity = new Entity\LinkCityUser();
                $linkUserToCityForm->bind($linkCityUserEntity);

                $city = $em->getRepository('Admin\Entity\City')->getCityByID($cityID);
                $user = $em->getRepository('Admin\Entity\User')->getUserByID($postDataForm['user']);

                $linkCityUserEntity->setCity($city);
                $linkCityUserEntity->setUser($user);

                $em->persist($linkCityUserEntity);
                $em->flush();

                $usersLinkedToCity = $em->getRepository('Admin\Entity\LinkCityUser')->getUsersLinkedToCity($cityID);
            }
        }

		return array(
            'form' => $linkUserToCityForm,
			'city' => $cityEntity,
            'currentUploadDir' => end(explode('public', $currentUploadDir)),
            'usersLinkedToCity' => $usersLinkedToCity,
        );
    }

    public function editAction()
    {
        $em = $this->getEntityManager();
        $authUser = $this->getAuthenticatedUser();
        $request = $this->getRequest();
        $id = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $id;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');

        // City Entity
        $entity = $em->getRepository('Admin\Entity\City')->findOneBy(array('id' => $id));
        // Edit Form
        $form = new Form\EditCityForm('link-user-to-city-form', $em, $currentUploadDir);

        if($request->isPost())
        {
            // $post = $request->getPost();
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            // remember name and path to the image if new image has not been uploaded
            $imageName = $entity->getImage();
            $imagePath = $currentUploadDir. DIRECTORY_SEPARATOR . $imageName;

            $form->bind($entity);
            $form->setData($post);

            var_dump($post);

            if($form->isValid()) 
            {
                $postDataForm   = $form->getData();
                $imageData      = $postDataForm->getImage();

                var_dump($imageData);

                if(!$imageData['tmp_name'] == '')
                {
                    $imageName          = end(explode("$currentUploadDir". DIRECTORY_SEPARATOR, $imageData['tmp_name']));
                    $imagePath          = $imageData['tmp_name'];
                }

                $thumb              = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
                $thumb_square       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
                $currentDimantions  = $thumb->getCurrentDimensions();

                if($post['xStartCrop'] === '' ||
                    $post['yStartCrop'] === '') 
                {
                    if($currentDimantions['height'] / $currentDimantions['width'] < 0.5) 
                        $thumb->cropFromCenter($currentDimantions['height'] * 2, $currentDimantions['height']);
                    else 
                        $thumb->cropFromCenter($currentDimantions['width'], $currentDimantions['width'] / 2);
                }
                else 
                {
                    $scale = $currentDimantions['width'] / $post['widthCurrent'];

                    $thumb->crop($post['xStartCrop'] * $scale, 
                                 $post['yStartCrop'] * $scale,
                                 $post['widthCrop'] * $scale, 
                                 $post['heightCrop'] * $scale
                                );
                }

                $thumb->resize(640, 320);
                $resizedImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'rect640x320_' . $imageName;
                $thumb->save($resizedImg);

                $thumb->resize(224, 112);
                $mailImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName;    
                $thumb->save($mailImg);

                if($currentDimantions['height'] / $currentDimantions['width'] < 1)
                    $thumb_square->cropFromCenter($currentDimantions['height'], $currentDimantions['height']);
                else 
                    $thumb_square->cropFromCenter($currentDimantions['width'], $currentDimantions['width']);

                $thumb_square->resize(60, 60);
                $mailImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName;    
                $thumb_square->save($mailImg);   

                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'rect640x320_' . $imageName, 0777);
                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName, 0777); 
                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName, 0777); 

                $entity->setImage($imageName);

                $entity->setLabel($postDataForm->getName());
                $entity->setRoute('/' . strtolower($postDataForm->getCountry()->getName()) . '/' . strtolower($postDataForm->getName()));

                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('administrator_content/default', array(
                    'controller' => 'city', 
                    'action' => 'view', 
                    'id' => $entity->getId()
                ));
            }
        }
        else
        {
            $form->bind($entity);
            $form->get('country')->setValue($entity->getCountry()->getId());
        }

        return array(
            'form' => $form,
            'city' => $entity,
            'currentUploadDir' => end(explode('public', $currentUploadDir)),
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }
}