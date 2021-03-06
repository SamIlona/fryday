<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Fryday;

use Zend\View\HelperPluginManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/*
stipulated - предусмотрено

The Module class is assigned to the namespace that is stipulated by our module.
The class itself is a normal PHP class, which can have a series of methods, which
can be called up by different Framework managers and components, for example in the scope of the
initialisation.
*/
class Module
{
    public function getServiceConfig()
    {    
         return array(
            'factories' => array(
                'Fryday\Service\CitiesService' => function ($sm) {
                    return new \Fryday\Service\CitiesService($sm);
                },
                'Navigation_c' => 'Fryday\Navigation\Service\CitiesNavigationFactory',
            )
        );   
    }

    public function onBootstrap($e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 100);

        $serviceManager = $e->getApplication()->getServiceManager();

        $serviceManager->get('viewhelpermanager')->setFactory('CitiesHelper', function ($sm) use ($e) {
            return new \Fryday\View\Helper\CitiesHelper($sm); 
        });
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                // This will overwrite the native navigation helper
                'navigation' => function(HelperPluginManager $pm) {
                    $sm = $pm->getServiceLocator();
                    $config = $sm->get('Config');

                    // Setup ACL:
                    $acl = new \Admin\Acl\Acl($config);

                    // Get the AuthenticationService
                    $auth = $sm->get('Zend\Authentication\AuthenticationService');
                    $role = \Admin\Acl\Acl::DEFAULT_ROLE;

                    if ($auth->hasIdentity()) {
                        // $user = $auth->getIdentity();
                        $role = $auth->getIdentity()->getRole()->getName();
                    //     $usrlId = $user->getUsrlId(); // Use a view to get the name of the role
                    //     // TODO we don't need that if the names of the roles are comming from the DB
                    //     switch ($usrlId) {
                    //         case 0 :
                    //             $role = Acl::DEFAULT_ROLE; // guest
                    //             break;
                    //         case 1 :
                    //             $role = 'qualifier';
                    //             break;
                    //         case 2 :
                    //             $role = 'sale';
                    //             break;
                    //         case 3 :
                    //             $role = 'admin';
                    //             break;
                    //         default :
                    //             $role = Acl::DEFAULT_ROLE; // guest
                    //             break;
                    //     }
                    }

                    // Get an instance of the proxy helper
                    $navigation = $pm->get('Zend\View\Helper\Navigation');
                    
                    // Store ACL and role in the proxy helper:
                    $navigation->setAcl($acl)->setRole($role); // 'member'

                    // Return the new navigation helper instance
                    return $navigation;
                }
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /*
    We should specify in the Module.php how the autoloading of the individual module classes
    it to function. To achieve this, we implement the getAutoloaderConfig() method that will
    be processed during the initialisation of the ModuleManager‘—once again, according to
    convention.

    As a general rule, classes must initially be made available by means of a require() call (or something
    similar) before they can be used the first time, for it is indeed so that during the execution of a script
    only the code that was previously made available to the PHP interpreter is accessible there. Thus,
    it is not enough to program a PHP class, to deposit the former somewhere in the file system and
    the latter at another location, to refer to the script that is being executed without having made the
    class known beforehand. And now a brief digression: One must absolutely differentiate between
    code that is part of the PHP core and the so-called “userland” code. Whereas, for example, the core
    class allows \DateTime to be used without previous registration, this does not apply for classes that
    you have written yourself, i.e. userland code. Such code must always be made known to the PHP
    interpreter initially, and the respective PHP files in which the class definitions are located must have
    been loaded.
    Zend Framework 2 makes intensive use of autoloading. Autoloading simply means that the
    registration of classes is performed automatically; the respective PHP files with the classes that
    are defined there are thus automatically loaded when needed. In order for this to function, a certain
    configuration must be performed—as we have already seen in the getAutoloaderConfig() method
    in the Module.php file.
    Indeed, we avoid a great deal of typing on every page with autoloading because we would otherwise
    have to load each file with an explicit require() ; but, on the other hand, we burden the system
    additionally with the autoloading function. Thus, autoloading has certain costs that can make
    themselves felt in the execution time of a Framework 2 application. The good thing is that are
    different ways, among them high-performance ones, in which autoloading can be implemented,
    which are all supported by Framework. Framework has two essential classes that are used for
    autoloading.

    */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
