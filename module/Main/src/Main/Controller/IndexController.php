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
        $em = $this->getEntityManager();

        return array(
            'eventsFirstLine'   => $em->getRepository('Content\Entity\Event')->getEventsForIndexPage(4, 0),
            'eventsSecondLine'  => $em->getRepository('Content\Entity\Event')->getEventsForIndexPage(4, 4),
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
    public function eventsAction()
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
        $em = $this->getEntityManager();

        $cityName = $this->getEvent()->getRouteMatch()->getParam('city');
        $city = $em->getRepository('Content\Entity\City')->getCityByName($cityName);

        
        return array(
            'eventsFirstLine'   => $em->getRepository('Content\Entity\Event')->getEventsForIndexPage(4, 0, $city),
            'eventsSecondLine'  => $em->getRepository('Content\Entity\Event')->getEventsForIndexPage(4, 4, $city),
        );
    }
}
