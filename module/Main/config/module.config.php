<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

// http://stackoverflow.com/questions/13007477/doctrine-2-and-zf2-integration
namespace Main; // SUPER important for Doctrine othervise can not find the Entities

return array(
    'view_helpers' => array(
        // 'invokables' => array(
        //     'citieshelper' => 'Main\View\Helper\citieshelper',
        // ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Main\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            // 'country' => array(
            //     //'type'    => 'Literal',
            //     'type'    => 'Segment',
            //     'options' => array(
            //         'route'    => '/[:country]',
            //         'constraints' => array(
            //             'country'  => '[a-zA-Z][a-zA-Z0-9_-]*',
            //         ),
            //         'defaults' => array(
            //             '__NAMESPACE__' => 'Main\Controller',
            //             'controller'    => 'Index',
            //             'action'        => 'country',
            //         ),
            //     ),
            //     'may_terminate' => true,
            //     'child_routes' => array(
            //         'city' => array(
            //             'type'    => 'Segment',
            //             'options' => array(
            //                 'route'    => '/[:city]',
            //                 'constraints' => array(
            //                     'city'  => '[a-zA-Z][a-zA-Z0-9_-]*',
            //                 ),
            //                 'defaults' => array(
            //                     '__NAMESPACE__' => 'Main\Controller',
            //                     'controller'    => 'Index',
            //                     'action'        => 'city',
            //                 ),
            //             ),
            //         ),
            //     ),
            // ),
            'main' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Main\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // 'default' => array(
                    //     'type'    => 'Segment',
                    //     'options' => array(
                    //         'route'    => '[:controller[/:action[/:id[/:did]]]]',
                    //         'constraints' => array(
                    //             'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    //             'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    //             'id'         => '[0-9:-]+',
                    //             'did'        => '[0-9_-]+',
                    //         ),
                    //         'defaults' => array(
                    //         ),
                    //     ),
                    // ),
                    'static_pages' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => ':action',
                            'constraints' => array(
                                // '__NAMESPACE__' => 'Main\Controller',
                                'controller'    => 'Index',
                                // 'action'        => 'index',
                                // 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                                // 'id'         => '[0-9:-]+',
                                // 'did'        => '[0-9_-]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'event_details' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => ':country/:city/event/:dateslug/:titleslug',
                            'constraints' => array(
                                'dateslug'      => '[0-9:-]+',
                            ),
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'view-event',
                                // 'id'            => 0,
                            ),
                        ),
                    ),
                    'cities' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => ':country/:city[/]',
                            'constraints' => array(
                                'country' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'city' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'city-dispatcher',
                            ),
                        ),
                    ),
                    'js_test' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'jstest',
                            'constraints' => array(
                            ),
                            'defaults' => array(
                                'controller'    => 'Javascript',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'frontend_navigation' => 'Fryday\Navigation\Service\FrontendNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Main\Controller\Index'     => 'Main\Controller\IndexController',
            // 'Main\Controller\Venues'    => 'Main\Controller\VenuesController',
            'Main\Controller\Partner'   => 'Main\Controller\PartnerController',
            'Main\Controller\Javascript' => 'Main\Controller\JavascriptController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => array(
            // 'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            //'partials/menu'        => __DIR__ . '/../view/partials/menu.phtml',
            'main/index/index'     => __DIR__ . '/../view/main/index/index.phtml',
            'error/404'            => __DIR__ . '/../view/error/404.phtml',
            'error/index'          => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    // extra for Doctrine Put the namespace on top!!!!!!!
    // http://stackoverflow.com/questions/13007477/doctrine-2-and-zf2-integration
    // put namespace User; in the first line of your module.config.php. a namespace should be defined as you use the __NAMESPACE__ constant...
    // from DoctrineModule
    'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            // 'my_annotation_driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),

    // http://stackoverflow.com/questions/12972316/how-to-set-up-2-navigations-in-zf2
    'navigation' => array(
        'frontend' => array(
            array(
                'label' => 'Home',
                'route' => 'main',
            ),
            array(
                'label' => 'Events',
                'route' => 'main/static_pages',
                'action' => 'events',
            ),
            array(
                'label' => 'Franchise',
                'route' => 'main/static_pages',
                'action' => 'franchise',
            ),
            array(
                'label' => 'Advertise',
                'route' => 'main/static_pages',
                'action' => 'advertise',
            ),
            // array(
            //     'label' => 'Partner',
            //     'route' => 'main/static_pages',
            //     'action' => 'partner',
            // ),
            // array(
            //     'label' => 'Media',
            //     'route' => 'main/static_pages',
            //     'action' => 'media',
            // ),
            array(
                'label' => 'Member',
                'route' => 'main/static_pages',
                'action' => 'member',
            ),
            array(
                'label' => 'Venue',
                'route' => 'main/static_pages',
                'action' => 'venue',
            ),
            array(
                'label' => 'Speaker',
                'route' => 'main/static_pages',
                'action' => 'speaker',
            ),
            // array(
            //     'label' => 'About',
            //     'route' => 'main/static_pages',
            //     'action' => 'about',
            // ),
            // array(
            //     'label' => 'Contact',
            //     'route' => 'main/static_pages',
            //     'action' => 'contact',
            // ),
            array(
                'label' => 'Dashboard',
                'route' => 'administrator',
                'resource'      => 'Admin\Controller\Index',
                'privilege'     => 'index'
            ),
        )
    ),
);
