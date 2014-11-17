<?php
/**
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Content\Controller;

use Content\Form;

use Content\Entity;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


/**
 * Event controller
 *
 * @category   Fryday_Application
 * @package    Content
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
			'upcomingEvents'    => $em->getRepository('Content\Entity\Event')->getEvents(4, 0, 'upcoming'),
            'pastEvents'        => $em->getRepository('Content\Entity\Event')->getEvents(4, 0, 'past'),
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

    	$eventForm = new Form\CreateEventForm('event', $this->entityManager, $this->authenticatedUser);
        $eventForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Content\Entity\Event'));
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

            if($eventForm->isValid()) 
            {
                $data = $eventForm->getData(); // $data is a Entity\Venue object

                $profileImage = $data->getProfileImage();
                $urlProfileImage = explode("./public", $profileImage['tmp_name']);
                $eventEntity->setProfileImage($urlProfileImage[1]);//->setProfileImage($profileImage['tmp_name']);

                $eventEntity->setDateTimeEvent(new \DateTime($datetime));
                $eventEntity->setUser($this->authenticatedUser);

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

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'event', 'action' => 'index'));
            }
        }

        return array(
        	'form' => $eventForm,
        );
    }
}