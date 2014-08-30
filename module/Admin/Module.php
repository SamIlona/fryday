<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    // public function onBootstrap($e)
    // {
    //     $eventManager        = $e->getApplication()->getEventManager();
    //     $moduleRouteListener = new ModuleRouteListener();
    //     $moduleRouteListener->attach($eventManager);

    //     $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
    //         $controller = $e->getTarget();
    //         $controllerClass = get_class($controller);
    //         $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
    //         $config = $e->getApplication()->getServiceManager()->get('config');
    //         if (isset($config['module_layouts'][$moduleNamespace])) {
    //             $controller->layout($config['module_layouts'][$moduleNamespace]);
    //         }
    //     }, 100);
    // }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

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

    /*
    Сервис-менеджер может быть сконфигурирован двумя путями: 
    - класс модуля может вернуть конфигурацию СМ 
    - файл конфигурации модуля (config/module.config.php в большинстве случаев) может вернуть конфигурацию СМ. 
    Результат обоих способов идентичен, так что использование того или иного, — лишь дело вкуса.
    */
    // public function getServiceConfig()
    // {
    //     return array(
    //         'invokables' => array(
    //             'my-foo' => 'MyModule\Foo\Bar',
    //         ),
    //     );
    // }
}
