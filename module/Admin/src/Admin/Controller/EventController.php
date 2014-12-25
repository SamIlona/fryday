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
    public function createAction()
    {
    	$this->entityManager = $this->getEntityManager();
        $this->authenticatedUser = $this->getAuthenticatedUser();

        $slug = $this->getServiceLocator()->get('SeoUrl\Slug');

    	$eventForm = new Form\CreateEventForm('event', $this->entityManager, $this->authenticatedUser);
        $eventForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\Event'));
        $eventEntity = new Entity\Event();
        $eventForm->bind($eventEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            // var_dump($post);
            $eventForm->setData($post);

            $date = $eventForm->get('date')->getValue();
            $time = $eventForm->get('time')->getValue();
            $datetime = $date . " " . $time;

            $title = $eventForm->get('title')->getValue();

            $dateslug = $slug->create($date);
            $titleslug = $slug->create($title);

            if($eventForm->isValid()) 
            {
                $data = $eventForm->getData(); // $data is a Entity\Venue object

                // var_dump($profileImage);

                $profileImage = $data->getProfileImage();
                $urlProfileImage = explode("./public", $profileImage['tmp_name']);


                

                // var_dump($profileImage);
                // var_dump($urlProfileImage);
                // var_dump($profileImage['tmp_name']);
                

                $eventEntity->setProfileImage($urlProfileImage[1]);//->setProfileImage($profileImage['tmp_name']);

                $eventEntity->setDateTimeEvent(new \DateTime($datetime));
                $eventEntity->setUser($this->authenticatedUser);
                $eventEntity->setTitleSlug($titleslug);
                $eventEntity->setDateSlug($dateslug);
                $eventEntity->setPubished(false);

                if ($this->authenticatedUser->getRole()->getName() == 'administrator')
                {
                    $eventEntity->setCity($data->getVenue()->getCity());
                }
                else 
                {
                    $eventEntity->setCity($this->authenticatedUser->getCity());
                }

                $this->entityManager->persist($eventEntity);
                $this->entityManager->flush();

                if($eventForm->get('newsletter')->getValue())
                {
                    $eventEntity->setNewsletter(true);
                }

                return $this->redirect()->toRoute('administrator_content/event_preview', array(
                    'country' => $data->getVenue()->getCity()->getCountry()->getName(), 
                    'city' => $data->getVenue()->getCity()->getName(), 
                    'dateslug' => $dateslug, 
                    'titleslug' => $titleslug 
                ));
            }
        }

        return array(
        	'form' => $eventForm,
        );
    }

    public function preViewAction() 
    {
        $em = $this->getEntityManager();
        $this->authenticatedUser = $this->getAuthenticatedUser();

        $titleslug = $this->event->getRouteMatch()->getParam('titleslug');
        $dateslug = $this->event->getRouteMatch()->getParam('dateslug');

        $eventForm = new Form\PreviewEventForm('event', $this->entityManager, $this->authenticatedUser);

        $event = $em->getRepository('Admin\Entity\Event')->getEventBySlug($titleslug, $dateslug);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $event->setPubished(true);

            if($event->getNewsletter())
            {

            
                $title = $event->getTitle();
                $details = $event->getDescription();
                $profileImage = $event->getProfileImage();

                $img2 = explode("/uploads/events/profileimages/", $profileImage);

                $cityID = $event->getVenue()->getCity()->getId();

                    $subscribers = $this->entityManager->getRepository('Admin\Entity\Subscriber')->getSubscribersByCityID($cityID);

                    

                    foreach ($subscribers as $subscriber)
                    {
                        $mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');
                        $mailService->setSubject('FRYDAY NEWSLETER IN ACTION')
                                    ->setTemplate('admin/emails/mail', array(
                                        'imag'      => $img2[1], 
                                        'title'     => $title,
                                        'details'   => $details,
                                    ));

                        $img = 'public' . $profileImage;

                        // var_dump($img );

                        $mailService->setAttachments(array(
                            'public/email/logo_fryday.jpg',
                            $img,
                        ));

                        $message = $mailService->getMessage();
                        $message->setTo($subscriber->getEmail());

                        $result = $mailService->send();
                        if ($result->isValid()) {
                            // echo 'Message sent. Congratulations!';
                        } else {
                            if ($result->hasException()) {
                                echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());

                            } else {
                                echo sprintf('An error occurred. Message: %s', $result->getMessage());
                                
                            }
                            // return $this->redirect()->toRoute('administrator/default');
                            // return new ViewModel();
                        }
                    }
                }
            $em->persist($event);
            $em->flush();

            return $this->redirect()->toRoute('main/event_details', array(
                'country' => $event->getCity()->getCountry()->getName(), 
                'city' => $event->getCity()->getName(), 
                'dateslug' => $event->getDateSlug(), 
                'titleslug' => $event->getTitleSlug()
            ));
        }
        
        return array(
            'event' => $event,
            'form' => $eventForm,
        );
    }
}