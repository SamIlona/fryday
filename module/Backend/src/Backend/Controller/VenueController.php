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
 * Venue controller
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Controller
 */
class VenueController extends Action
{
    /**
     * List All Venues
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $venuesRepository = $this->entityManager->getRepository('Main\Entity\Venue');
        $venues = $venuesRepository->findAll();

        return array(
            'venues' => $venues, 
        );
        
        // return array(
        //     'venues' => $venues,
        // );
        // $this->init();
        // $file = urldecode($this->params()->fromRoute('id'));
        // $filename = $this->_dir . DIRECTORY_SEPARATOR . $file;
        // $filename = './data/uploads/venues/phpQMddxI_541497228e23b';
        // $contents = null;
        // if (file_exists($filename)) {
        //     $handle = fopen($filename, "r"); // "r" - not r but b for Windows "b" - keeps giving me errors no file
        //     $contents = fread($handle, filesize($filename));
        //     fclose($handle);
        // }
        // return array(
        //     'contents' => $contents
        // );
    }

    /**
     * Create venue
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createAction()
    {
    	$this->entityManager = $this->getEntityManager();

    	$venueForm = new Form\VenueForm('venue', $this->entityManager);
        $venueForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Main\Entity\Venue'));
        $venueEntity = new Entity\Venue();
        $venueForm->bind($venueEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            // var_dump($post);

            $venueForm->setData($post);

            if($venueForm->isValid()) 
            {
                $data = $venueForm->getData(); // $data is a Entity\Venue object
                $profileImage = $data->getProfileImage();
                $venueEntity->setProfileImage($profileImage['tmp_name']);

                $this->entityManager->persist($venueEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('backend/default', array('controller' => 'venue', 'action' => 'index'));
            }
        }

        return array(
        	'form' => $venueForm,
        );
    }
}