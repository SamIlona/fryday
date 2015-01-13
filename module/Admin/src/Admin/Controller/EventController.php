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
 * Event controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class EventController extends Action
{
    protected function getUploadPath()
    {
        $config = $this->getServiceLocator()->get('Config');
        $uploadDir = $config['path_to_uploads']['event'];

        if (!is_dir($uploadDir )) 
        {
            $oldmask = umask(0);
            mkdir($uploadDir , 0777);
            umask($oldmask);
        }

        return $uploadDir;
    }
	/**
     * List All Events
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
    	$em = $this->getEntityManager();

		return array(
			'upcomingEvents'    => $em->getRepository('Admin\Entity\Event')->getEvents(10, 0, 'upcoming', 'all', 'all'),
            'pastEvents'        => $em->getRepository('Admin\Entity\Event')->getEvents(10, 0, 'past', 'all', 'all'),
        );
   	}

    /**
     * Create event
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createFirstStepAction()
    {
        $em = $this->getEntityManager();
        $sl = $this->getServiceLocator();
        $user = $this->getAuthenticatedUser();
        $slug = $sl->get('SeoUrl\Slug');
        $uploadDir = $this->getUploadPath();

        $eventForm = new Form\CreateEventFirstStepForm('event', $em, $user);
        $eventForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Event'));
        $eventEntity = new Entity\Event();
        $eventForm->bind($eventEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();

            $eventForm->setData($post);

            $dateEvent      = $eventForm->get('date')->getValue();
            $timeEvent      = $eventForm->get('time')->getValue();
            $titleEvent     = $eventForm->get('title')->getValue();

            $dateTimeEvent  = $dateEvent . " " . $timeEvent;

            if($eventForm->isValid()) 
            {
                $dataForm = $eventForm->getData();

                $dateEventSlug  = $slug->create($dateEvent);
                $titleEventSlug = $slug->create($titleEvent);

                $eventEntity->setDateTimeEvent(new \DateTime($dateTimeEvent));
                $eventEntity->setUser($user);
                $eventEntity->setTitleSlug($titleEventSlug);
                $eventEntity->setDateSlug($dateEventSlug);

                $this->entityManager->persist($eventEntity);
                $this->entityManager->flush();

                $event = $em->getRepository('Admin\Entity\Event')->getLastAddedEvent();
                $eventID = $event->getId();
                $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $eventID;               
                if (!is_dir($currentUploadDir )) 
                {
                    $oldmask = umask(0);
                    mkdir($currentUploadDir, 0777);
                    umask($oldmask);
                }

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'event', 'action' => 'create-second-step', 'id' => $eventID));
            }
        }

        return array(
            'form' => $eventForm,
        );
    }

    /**
     * Create event
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createSecondStepAction()
    {
        $em = $this->getEntityManager();
        $sl = $this->getServiceLocator();
        $user = $this->getAuthenticatedUser();
        $uploadDir = $this->getUploadPath();
        $eventID = $this->params()->fromRoute('id');
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $eventID;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
    	$eventForm = new Form\CreateEventSecondStepForm('event', $em, $user, $currentUploadDir);
        $eventForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Event'));
        $eventEntity = $em->getRepository('Admin\Entity\Event')->findOneBy(array('id' => $eventID));
        $eventForm->bind($eventEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $eventForm->setData($post);

            if($eventForm->isValid()) 
            {
                $dataForm           = $eventForm->getData();

                $city               = $dataForm->getVenue()->getCity();
                $imageData          = $dataForm->getImage();
                $imageName          = end(explode("$currentUploadDir". DIRECTORY_SEPARATOR, $imageData['tmp_name']));
                $thumb              = $thumbnailer->create($imageData['tmp_name'], $options = array(), $plugins = array());
                $thumb_square       = $thumbnailer->create($imageData['tmp_name'], $options = array(), $plugins = array());
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

                $eventEntity->setImage($imageName);
                $eventEntity->setCity($city);
                $eventEntity->setPublished(false);
                $eventEntity->setNewsletterCreated(false);
                $eventEntity->setNewsletterSend(false);

                $this->entityManager->persist($eventEntity);
                $this->entityManager->flush();

                $this->flashMessenger()->addMessage('<strong>Well done!</strong> Event has been successfully created!');

                return $this->redirect()->toRoute('administrator_content/event_preview', array(
                    'country'   => $dataForm->getVenue()->getCity()->getCountry()->getName(), 
                    'city'      => $dataForm->getVenue()->getCity()->getName(), 
                    'dateslug'  => $eventEntity->getDateSlug(), 
                    'titleslug' => $eventEntity->getTitleSlug(), 
                ));
            }
        }

        return array(
        	'form'      => $eventForm,
            'eventID'   => $eventID,
            'event'     => $eventEntity,
        );
    }

    public function preViewAction() 
    {
        $em = $this->getEntityManager();
        $user = $this->getAuthenticatedUser();
        $titleslug = $this->event->getRouteMatch()->getParam('titleslug');
        $dateslug = $this->event->getRouteMatch()->getParam('dateslug');
        $event = $em->getRepository('Admin\Entity\Event')->getEventBySlug($titleslug, $dateslug);
        $eventForm = new Form\PreviewEventForm('event', $em, $user);
        
        return array(
            'event' => $event,
            'form' => $eventForm,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function publishAction()
    {
        $em = $this->getEntityManager();
        $eventID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $eventID;
        $eventEntity = $em->getRepository('Admin\Entity\Event')->findOneBy(array('id' => $eventID));
        
        $eventEntity->setPublished(true);
        
        $em->persist($eventEntity);
        $em->flush();

        $this->flashMessenger()->addMessage('Event published!');

        return $this->redirect()->toRoute('administrator_content/event_preview', array(
            'country'   => $eventEntity->getCity()->getCountry()->getName(), 
            'city'      => $eventEntity->getCity()->getName(), 
            'dateslug'  => $eventEntity->getDateSlug(), 
            'titleslug' => $eventEntity->getTitleSlug(), 
        ));
    }

    public function unPublishAction()
    {
        $em = $this->getEntityManager();
        $eventID = $this->params()->fromRoute('id');
        $eventEntity = $em->getRepository('Admin\Entity\Event')->findOneBy(array('id' => $eventID));
        
        $eventEntity->setPublished(false);
        
        $em->persist($eventEntity);
        $em->flush();

        $this->flashMessenger()->addMessage('Event unpublished!');

        return $this->redirect()->toRoute('administrator_content/event_preview', array(
            'country'   => $eventEntity->getCity()->getCountry()->getName(), 
            'city'      => $eventEntity->getCity()->getName(), 
            'dateslug'  => $eventEntity->getDateSlug(), 
            'titleslug' => $eventEntity->getTitleSlug(), 
        ));
    }

    public function editAction()
    {
        $em = $this->getEntityManager();
        $eventID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $eventID;
        $user = $this->getAuthenticatedUser();
        $eventForm = new Form\EditEventForm('event', $em, $user, $currentUploadDir);
        $eventEntity = $em->getRepository('Admin\Entity\Event')->findOneBy(array('id' => $eventID));
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');

        $request = $this->getRequest();

        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            // remember name and path to the image if new image has not been uploaded
            $imageName = $eventEntity->getImage();
            $imagePath = $currentUploadDir. DIRECTORY_SEPARATOR . $imageName;

            $eventForm->bind($eventEntity);
            $eventForm->setData($post);
            
            if ($eventForm->isValid()) 
            {
                $dataForm               = $eventForm->getData();
                $city                   = $dataForm->getVenue()->getCity();
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

                $eventEntity->setImage($imageName);
                $eventEntity->setCity($city);

                $this->getEntityManager()->persist($eventEntity);
                $this->getEntityManager()->flush();

                $eventEntity = $em->getRepository('Admin\Entity\Event')->findOneBy(array('id' => $eventID));
                
                return $this->redirect()->toRoute('administrator_content/event_preview', array(
                    'country'   => $eventEntity->getCity()->getCountry()->getName(), 
                    'city'      => $eventEntity->getCity()->getName(), 
                    'dateslug'  => $eventEntity->getDateSlug(), 
                    'titleslug' => $eventEntity->getTitleSlug(), 
                ));
            }
        }
        else
        {
            $eventForm->bind($eventEntity);
            $dateTimeEvent = $eventEntity->getDateTimeEvent();
            $eventForm->get('date')->setValue($dateTimeEvent->format('d/m/Y'));
            $eventForm->get('time')->setValue($dateTimeEvent->format('H:i'));
        }

        return array(
            'event' => $eventEntity,
            'form' => $eventForm,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }
}