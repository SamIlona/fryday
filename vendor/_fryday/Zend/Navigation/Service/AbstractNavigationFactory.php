<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Fryday\Zend\Navigation\Service;

// use Zend\Config;
// use Zend\Http\Request;
// use Zend\Mvc\Router\RouteMatch;
// use Zend\Mvc\Router\RouteStackInterface as Router;
// use Zend\Navigation\Exception;
// use Zend\Navigation\Navigation;
// use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Zend\Navigation\Navigation;

/**
 * Abstract navigation factory
 */
// abstract class AbstractNavigationFactory implements FactoryInterface
abstract class AbstractNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @override
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pages = $this->getPages($serviceLocator);
        return new Navigation($pages);
    }
}
