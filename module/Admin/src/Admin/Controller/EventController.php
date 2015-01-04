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
			'upcomingEvents'    => $em->getRepository('Admin\Entity\Event')->getEvents(10, 0, 'upcoming'),
            'pastEvents'        => $em->getRepository('Admin\Entity\Event')->getEvents(10, 0, 'past'),
            'flashMessages'     => $this->flashMessenger()->getMessages(),
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
                $mailImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'mail224x112_' . $imageName;    
                $thumb->save($mailImg);

                if($currentDimantions['height'] / $currentDimantions['width'] < 1)
                    $thumb_square->cropFromCenter($currentDimantions['height'], $currentDimantions['height']);
                else 
                    $thumb_square->cropFromCenter($currentDimantions['width'], $currentDimantions['width']);

                $thumb_square->resize(60, 60);
                $mailImg = $currentUploadDir . DIRECTORY_SEPARATOR . 'square60x60_' . $imageName;    
                $thumb_square->save($mailImg);     

                $eventEntity->setImage($imageName);
                $eventEntity->setCity($city);
                $eventEntity->setPubished(false);
                $eventEntity->setNewsletterCreated(false);
                $eventEntity->setNewsletterSend(false);

                $this->entityManager->persist($eventEntity);
                $this->entityManager->flush();

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
        $this->authenticatedUser = $this->getAuthenticatedUser();
        $user = $this->getAuthenticatedUser();
        $titleslug = $this->event->getRouteMatch()->getParam('titleslug');
        $dateslug = $this->event->getRouteMatch()->getParam('dateslug');

        $event = $em->getRepository('Admin\Entity\Event')->getEventBySlug($titleslug, $dateslug);


        // // We encourage to use Dependency Injection instead of Service Locator
        // $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        // $imagePath   = 'public/frontend/img/prev_event_3.png';
        // $thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());

        // $thumb->resize(231, 144);

        // $thumb->save('module/Admin/view/email/tpl/prev_event_3_resized.png');

        // $thumb->show();
        // // or/and
        

        $eventForm = new Form\PreviewEventForm('event', $em, $user);

        
        // $request = $this->getRequest();
        // if($request->isPost())
        // {
        //     $event->setPubished(true);

        //     if($event->getNewsletter())
        //     {

            
        //         $title = $event->getTitle();
        //         $details = $event->getDescription();
        //         $profileImage = $event->getProfileImage();

        //         $img2 = explode("/uploads/events/profileimages/", $profileImage);

        //         $cityID = $event->getVenue()->getCity()->getId();

        //             $subscribers = $this->entityManager->getRepository('Admin\Entity\Subscriber')->getSubscribersByCityID($cityID);

                    

        //             foreach ($subscribers as $subscriber)
        //             {
        //                 $mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');
        //                 $mailService->setSubject('FRYDAY NEWSLETER IN ACTION')
        //                             ->setTemplate('admin/emails/mail', array(
        //                                 'imag'      => $img2[1], 
        //                                 'title'     => $title,
        //                                 'details'   => $details,
        //                             ));

        //                 $img = 'public' . $profileImage;

        //                 // var_dump($img );

        //                 $mailService->setAttachments(array(
        //                     'public/email/logo_fryday.jpg',
        //                     $img,
        //                 ));

        //                 $message = $mailService->getMessage();
        //                 $message->setTo($subscriber->getEmail());

        //                 $result = $mailService->send();
        //                 if ($result->isValid()) {
        //                     // echo 'Message sent. Congratulations!';
        //                 } else {
        //                     if ($result->hasException()) {
        //                         echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());

        //                     } else {
        //                         echo sprintf('An error occurred. Message: %s', $result->getMessage());
                                
        //                     }
        //                     // return $this->redirect()->toRoute('administrator/default');
        //                     // return new ViewModel();
        //                 }
        //             }
        //         }
        //     $em->persist($event);
        //     $em->flush();

        //     return $this->redirect()->toRoute('main/event_details', array(
        //         'country' => $event->getCity()->getCountry()->getName(), 
        //         'city' => $event->getCity()->getName(), 
        //         'dateslug' => $event->getDateSlug(), 
        //         'titleslug' => $event->getTitleSlug()
        //     ));
        // }
        
        return array(
            'event' => $event,
            'form' => $eventForm,
        );
    }
}