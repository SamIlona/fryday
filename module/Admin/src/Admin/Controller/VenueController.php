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
class VenueController extends Action
{
    protected function getUploadPath()
    {
        $config = $this->getServiceLocator()->get('Config');
        $uploadDir = $config['path_to_uploads']['venue'];

        if (!is_dir($uploadDir )) 
        {
            $oldmask = umask(0);
            mkdir($uploadDir , 0777);
            umask($oldmask);
        }

        return $uploadDir;
    }
    /**
     * List All Venues
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $venuesRepository = $this->entityManager->getRepository('Admin\Entity\Venue');
        $venues = $venuesRepository->findAll();

        return array(
            'venues' => $venues, 
            'filesDir' => end(explode("public", $this->getUploadPath())),
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    /**
     * Create venue
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createFirstStepAction()
    {
        $em = $this->getEntityManager();
        $sl = $this->getServiceLocator();
        $authenticatedUser = $this->getAuthenticatedUser();
        $slug = $sl->get('SeoUrl\Slug');
        $uploadDir = $this->getUploadPath();
        $venueForm = new Form\CreateVenueFirstStepForm('venue', $em);
        $venueForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Venue'));
        $venueEntity = new Entity\Venue();
        $venueForm->bind($venueEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();

            $venueForm->setData($post);

            if($venueForm->isValid()) 
            {
                $data = $venueForm->getData();

                if ($authenticatedUser->getRole()->getName() == 'franchisor')
                    $venueEntity->setCity($authenticatedUser->getCity());
                
                $em->persist($venueEntity);
                $em->flush();

                $venue = $em->getRepository('Admin\Entity\Venue')->getLastAddedVenue();
                $venueID = $venue->getId();
                $currentVenueUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $venueID;               
                if (!is_dir($currentVenueUploadDir )) 
                {
                    $oldmask = umask(0);
                    mkdir($currentVenueUploadDir, 0777);
                    umask($oldmask);
                }

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'venue', 'action' => 'create-second-step', 'id' => $venueID));

            }
        }

        return array(
            'form' => $venueForm,
        );
    }

    /**
     * Create venue
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createSecondStepAction()
    {
    	$em = $this->getEntityManager();
        $user = $this->getAuthenticatedUser();
        $venueID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $venueID;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
    	$venueForm = new Form\CreateVenueSecondStepForm('venue', $em, $currentUploadDir);
        $venueForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Venue'));
        $venueEntity = $em->getRepository('Admin\Entity\Venue')->findOneBy(array('id' => $venueID));
        $venueForm->bind($venueEntity);

                // var_dump($venueEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $venueForm->setData($post);

            if($venueForm->isValid()) 
            {
                $dataForm           = $venueForm->getData();

                $imageData          = $dataForm->getImage();
                $imageName          = end(explode("$currentUploadDir". DIRECTORY_SEPARATOR, $imageData['tmp_name']));
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
                $resizedImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'rect640x320_' . $imageName;
                $thumb->save($resizedImg);

                $thumb->resize(224, 112);
                $resizedImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName;    
                $thumb->save($resizedImg);

                if($currentDimantions['height'] / $currentDimantions['width'] < 1)
                    $thumb_square->cropFromCenter($currentDimantions['height'], $currentDimantions['height']);
                else 
                    $thumb_square->cropFromCenter($currentDimantions['width'], $currentDimantions['width']);

                $thumb_square->resize(60, 60);
                $resizedImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName;    
                $thumb_square->save($resizedImg);  

                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'rect640x320_' . $imageName, 0777);
                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName, 0777); 
                chmod($currentUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName, 0777); 

                $venueEntity->setImage($imageName);

                // $venueEntity->setLabel($dataForm->getName());
                // $venueEntity->setRoute('/' . strtolower($dataForm->getCountry()->getName()) . '/' . strtolower($dataForm->getName()));

                $this->entityManager->persist($venueEntity);
                $this->entityManager->flush();

                $this->flashMessenger()->addMessage('<strong>Well done!</strong> Venue has been successfully added!');

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'venue', 'action' => 'index'));
            }
        }

        return array(
        	'form' => $venueForm,
            'venueID' => $venueID,
        );
    }

    public function viewAction()
    {
        $em = $this->getEntityManager();
        $authUser = $this->getAuthenticatedUser();
        $request = $this->getRequest();
        $id = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $id;
        $entity = $em->getRepository('Admin\Entity\Venue')->findOneBy(array('id' => $id));
        // $linkUserToCityForm = new Form\LinkUserToCityForm('link-user-to-city-form', $em, $authUser, $id);
        // $usersLinkedToCity = $em->getRepository('Admin\Entity\LinkCityUser')->getUsersLinkedToCity($id);

        // if($request->isPost())
        // {
        //     $post = $request->getPost();

        //     $linkUserToCityForm->setData($post);

        //     if($linkUserToCityForm->isValid()) 
        //     {
        //         $postDataForm = $linkUserToCityForm->getData();

        //         $linkUserToCityForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\LinkCityUser'));
        //         $linkCityUserEntity = new Entity\LinkCityUser();
        //         $linkUserToCityForm->bind($linkCityUserEntity);

        //         $city = $em->getRepository('Admin\Entity\City')->getCityByID($id);
        //         $user = $em->getRepository('Admin\Entity\User')->getUserByID($postDataForm['user']);

        //         $linkCityUserEntity->setCity($city);
        //         $linkCityUserEntity->setUser($user);

        //         $em->persist($linkCityUserEntity);
        //         $em->flush();

        //         $usersLinkedToCity = $em->getRepository('Admin\Entity\LinkCityUser')->getUsersLinkedToCity($id);
        //     }
        // }

        return array(
            // 'form' => $linkUserToCityForm,
            'venue' => $entity,
            'currentUploadDir' => end(explode('public', $currentUploadDir)),
            // 'usersLinkedToCity' => $usersLinkedToCity,
        );
    }

    public function editAction()
    {
        $em = $this->getEntityManager();
        $id = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $id;
        $user = $this->getAuthenticatedUser();
        $form = new Form\EditVenueForm('event', $em, $currentUploadDir);
        $entity = $em->getRepository('Admin\Entity\Venue')->findOneBy(array('id' => $id));
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');

        $request = $this->getRequest();

        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            // remember name and path to the image if new image has not been uploaded
            $imageName = $entity->getImage();
            $imagePath = $currentUploadDir. DIRECTORY_SEPARATOR . $imageName;

            $form->bind($entity);
            $form->setData($post);
            
            if ($form->isValid()) 
            {
                $dataForm               = $form->getData();
                $city                   = $dataForm->getCity();
                $imageData              = $dataForm->getImage();

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
                $entity->setCity($city);

                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                $entity = $em->getRepository('Admin\Entity\Venue')->findOneBy(array('id' => $id));
                
                return $this->redirect()->toRoute('administrator/default', array('controller' => 'venue', 'action' => 'index'));
            }
        }
        else
        {
            $form->bind($entity);
            // $dateTimeEvent = $entity->getDateTimeEvent();
            // $form->get('date')->setValue($dateTimeEvent->format('d/m/Y'));
            // $form->get('time')->setValue($dateTimeEvent->format('H:i'));
        }

        return array(
            'venue' => $entity,
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'currentUploadDir' => end(explode('public', $currentUploadDir)),
            'address' => $form->get('address')->getValue(),
        );
    }
}