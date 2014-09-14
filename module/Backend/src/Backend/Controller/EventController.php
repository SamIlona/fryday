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

use Main\Entity;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


/**
 * Event controller
 *
 * @category   Fryday_Application
 * @package    Backend
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
    	$this->entityManager = $this->getEntityManager();
        $eventsRepository = $this->entityManager->getRepository('Main\Entity\Event');
        $events = $eventsRepository->findAll();

		return array(
			'events' => $events, 
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

    	$eventForm = new Form\EventForm('event', $this->entityManager);
        $eventForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Main\Entity\Event'));
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

        //     // var_dump($post);

            $eventForm->setData($post);

            if($eventForm->isValid()) 
            {
                $data = $eventForm->getData(); // $data is a Entity\Venue object
                $profileImage = $data->getProfileImage();
                $venueEntity->setProfileImage($profileImage['tmp_name']);

                $this->entityManager->persist($venueEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('backend/default', array('controller' => 'event', 'action' => 'index'));
            }
        }

        return array(
        	'form' => $eventForm,
        );
    }
}