<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/administrator',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9:]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    /*
    http://framework.zend.com/manual/2.3/en/modules/zend.service-manager.intro.html
    http://zftutorials.ru/blog/using-zend-framework-service-managers-in-your-application.html
    http://zf2.com.ua/doc/64
    http://habrahabr.ru/post/166657/
    Сервис-менеджер может быть сконфигурирован двумя путями: 
    - класс модуля может вернуть конфигурацию СМ 
    - файл конфигурации модуля (config/module.config.php в большинстве случаев) может вернуть конфигурацию СМ.
    */

    /*
    ServiceManager реализует паттерн Service Locator.  Сервис/объект  Service Locator отвечает за получение других объектов.
    */
    'service_manager' => array(
        /*
        Firstly, we need to tell our application which NavigationFactory to use when using the bundled navigation view helpers. 
        Thankfully, ZF2 comes with a default factory that will suit our needs just fine. To tell ZF2 to use this default factory, 
        we simply add a navigation key to the service manager. Its best to do this in the Application module, 
        because, like the translation data, this is specific to the entire application, and not just to our album pages:
        */
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'admin_main_navigation' => 'Admin\Navigation\Service\AdminMainNavigationFactory',
        ),
        /*
        Абстрактные фабрики (Abstract Factories)
        Если у SM запрашивают сервис, который он не может обнаружить, он станет опрашивать все зарегистрированные абстрактные фабрики, 
        что бы посмотреть сможет ли какая-либо из них создать необходимый объект. Абстрактная фабрика может быть либо строкой, 
        содержащей полное имя класса, либо объектом, реализующим интерфейс Zend\ServiceManager\AbstractFactoryInterface.
        */
        // 'abstract_factories' => array(
        //     'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        //     'Zend\Log\LoggerAbstractServiceFactory',
        // ),
        /* 
        4) Псевдонимы (alias). При обращении к имени на самом деле вызывается что то другое. 
        Вы можете создать псевдоним для известного сервиса, сервиса ленивой загрузки, фабрики и т.д.

        Псевдонимы — это просто указатели с одного названия сервиса на другое и они могут быть рекурсивными.
        Это может показаться бессмысленным, но псевдонимы на самом деле играют очень важную роль в модульном окружении.
        */
        // 'aliases' => array(
        //     'translator' => 'MvcTranslator',
        // ),
        
    ),
    // 'translator' => array(
    //     'locale' => 'en_US',
    //     'translation_file_patterns' => array(
    //         array(
    //             'type'     => 'gettext',
    //             'base_dir' => __DIR__ . '/../language',
    //             'pattern'  => '%s.mo',
    //         ),
    //     ),
    // ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => '<i class="fa fa-dashboard"></i><span class="hidden-xs"> Home</span>',
                'route' => 'admin',
            ),
            array(
                'label' => '<i class="fa fa-bullhorn"></i><span class="hidden-xs"> News</span>',
                'route' => 'admin/default',
                'controller' => 'content',
                'action' => 'news',
                'resource'  => 'Admin\Controller\Admin',
                'pages' => array(
                    array(
                        'label' => '<i class="fa fa-bullhorn"></i><span class="hidden-xs"> News</span>',
                        'route' => 'admin/default',
                        'controller' => 'content',
                        'action' => 'news',
                        'resource'  => 'Admin\Controller\Admin',
                    )
                )
            ),
            array(
                'label' => '<i class="fa fa-glass"></i><span class="hidden-xs"> Events</span>', 
                'route' => 'admin/default',
                'controller' => 'content',
                'action' => 'events',
                'resource'  => 'Admin\Controller\Admin',
            ),
        ),
        
        'admin_main' => array(
            array(
                'label' => '<i class="fa fa-dashboard"></i><span class="hidden-xs"> Home</span>',
                'route' => 'admin',
            ),
            array(
                'label' => '<i class="fa fa-bullhorn"></i><span class="hidden-xs"> News</span>',
                'route' => 'admin/default',
                'controller' => 'content',
                'action' => 'news',
                'resource'  => 'Admin\Controller\Admin',
                'pages' => array(
                    array(
                        'label' => '<i class="fa fa-bullhorn"></i><span class="hidden-xs"> News</span>',
                        'route' => 'admin/default',
                        'controller' => 'content',
                        'action' => 'news',
                        'resource'  => 'Admin\Controller\Admin',
                    )
                )
            ),
            array(
                'label' => '<i class="fa fa-glass"></i><span class="hidden-xs"> Events</span>', 
                'route' => 'admin/default',
                'controller' => 'content',
                'action' => 'events',
                'resource'  => 'Admin\Controller\Admin',
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index'    => 'Admin\Controller\IndexController',
            'Admin\Controller\Content'  => 'Admin\Controller\ContentController',
        ),
    ),
    
    'view_manager' => array(
    //     'display_not_found_reason' => true,
    //     'display_exceptions'       => true,
    //     'doctype'                  => 'HTML5',
    //     'not_found_template'       => 'error/404',
    //     'exception_template'       => 'error/index',
    //     'template_map' => array(
    //         'layout/layout'           => __DIR__ . '/../view/layout/layout-admin.phtml',
    //         'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
    //         'error/404'               => __DIR__ . '/../view/error/404.phtml',
    //         'error/index'             => __DIR__ . '/../view/error/index.phtml',
    //     ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ZfcTwigViewStrategy',
        ),
    ),
    // Placeholder for console routes
    // 'console' => array(
    //     'router' => array(
    //         'routes' => array(
    //         ),
    //     ),
    // ),
);
