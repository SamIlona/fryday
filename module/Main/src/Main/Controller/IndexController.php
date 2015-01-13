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
            'eventsFirstLine'   => $em->getRepository('Admin\Entity\Event')->getEvents(4, 0, 'upcoming', 1, 'all'),
            'eventsSecondLine'  => $em->getRepository('Admin\Entity\Event')->getEvents(4, 4, 'upcoming', 1, 'all'),
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
            'events' => $em->getRepository('Admin\Entity\Event')->getEvents(10, 0, 'upcoming', 1, 'all'),
        );
    }
    public function viewEventAction()
    {
        $em = $this->getEntityManager();
        $titleslug = $this->event->getRouteMatch()->getParam('titleslug');
        $dateslug = $this->event->getRouteMatch()->getParam('dateslug');

        return array(
            'event' => $em->getRepository('Admin\Entity\Event')->getEventBySlug($titleslug, $dateslug),
        );
    }
    public function cityDispatcherAction()
    {
        $em = $this->getEntityManager();

        $cityName = $this->getEvent()->getRouteMatch()->getParam('city');
        $city = $em->getRepository('Admin\Entity\City')->getCityByName($cityName);

        
        return array(
            'eventsFirstLine'   => $em->getRepository('Admin\Entity\Event')->getEvents(4, 0, 'upcoming', 1, $city),
            'eventsSecondLine'  => $em->getRepository('Admin\Entity\Event')->getEvents(4, 4, 'upcoming', 1, $city),
        );
    }
}
