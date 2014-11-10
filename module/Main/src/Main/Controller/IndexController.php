<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Main\Controller;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends Action
{
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $eventsFirstLine = $this->entityManager->getRepository('Content\Entity\Event')->getEventsForIndexPage(4, 0);
        $eventsSecondLine = $this->entityManager->getRepository('Content\Entity\Event')->getEventsForIndexPage(4, 4);
        return array(
            'eventsFirstLine'    => $eventsFirstLine,
            'eventsSecondLine'    => $eventsSecondLine,
        );
    }
    public function venueAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function partnerAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function franchiseAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function advertiseAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function mediaAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function memberAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function speakerAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function aboutAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function eventAction()
    {
        $em = $this->getEntityManager();

        return array(
            'events' => $em->getRepository('Content\Entity\Event')->findAll(),
        );
    }
    public function viewEventAction()
    {
        $em = $this->getEntityManager();
        $id = $this->event->getRouteMatch()->getParam('id');
        $event = $this->getEntityManager()->find('Content\Entity\Event', $id);

        return array(
            'event' => $event,
        );
    }
    public function cityDispatcherAction()
    {
        // throw new Zend_Controller_Action_Exception('404 Page not found',404);
        // return $this->notFoundAction();
        return array();
    }
    // public function countryAction()
    // {
    //     $country = $this->params()->fromRoute('country');

    //     return new ViewModel(array(
    //         'country' => $country
    //         )
    //     );
    // }
    // public function listCountriesAction()
    // {
    //     return new ViewModel();
    // }

    // public function cityAction()
    // {
    //     $city = $this->params()->fromRoute('city');

    //     return new ViewModel(array(
    //         'city' => $city
    //         )
    //     );
    // }

    // public function listCitiesAction()
    // {
    //     return new ViewModel();
    // }
}
