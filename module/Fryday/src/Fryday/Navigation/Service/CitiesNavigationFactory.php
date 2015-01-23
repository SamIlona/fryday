<?php

namespace Fryday\Navigation\Service;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
 
class CitiesNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation =  new CitiesNavigation();
        return $navigation->createService($serviceLocator);
    }
}