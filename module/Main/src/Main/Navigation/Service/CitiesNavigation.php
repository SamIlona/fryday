<?php

namespace Main\Navigation\Service;
 
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

use Doctrine\ORM\EntityManager;
 
class CitiesNavigation extends DefaultNavigationFactory
{
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            //FETCH data from table menu :
            // $fetchMenu = $serviceLocator->get('menu')->fetchAll();
            $em = $serviceLocator->get('Doctrine\ORM\EntityManager');
            $fetchCitiesMenu = $em->getRepository('Admin\Entity\City')->findBy(array(), array('name' => 'ASC'));//->findAll();
 
            $configuration['navigation'][$this->getName()] = array();

            foreach($fetchCitiesMenu as $city)
            {
                $configuration['navigation'][$this->getName()][$city->getName()] = array(
                    'label'     => $city->getLabel(),
                    'route'     => 'main/cities',
                    'params'    => array(
                        'country'   => strtolower($city->getCountry()->getName()),
                        'city'      => strtolower($city->getName()),
                        ),
                );
            }
            
            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }
 
            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
 
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }
}