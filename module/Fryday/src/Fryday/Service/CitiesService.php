<?php
namespace Fryday\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CitiesService implements ServiceLocatorAwareInterface
{
	protected $sm;

    public function __construct()
    {

    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {

    }    

    public function getServiceLocator()
    {
    	return $this->sm->getServiceLocator();
    }
}