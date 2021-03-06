<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

// http://stackoverflow.com/questions/13007477/doctrine-2-and-zf2-integration
namespace Fryday; // SUPER important for Doctrine othervise can not find the Entities

return array(
    'view_helpers' => array(
        // 'invokables' => array(
        //     'citieshelper' => 'Fryday\View\Helper\citieshelper',
        // ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        // '__NAMESPACE__' => 'Fryday\Controller',
                        'controller' => 'Fryday\Controller\Index',
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
            //             '__NAMESPACE__' => 'Fryday\Controller',
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
            //                     '__NAMESPACE__' => 'Fryday\Controller',
            //                     'controller'    => 'Index',
            //                     'action'        => 'city',
            //                 ),
            //             ),
            //         ),
            //     ),
            // ),
            'fryday' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fryday\Controller',
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
                            'route'    => ':action[/:token]',
                            'constraints' => array(
                                // '__NAMESPACE__' => 'Fryday\Controller',
                                'controller'    => 'Index',
                                // 'action'        => 'index',
                                // 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'token'         => '[a-zA-Z][a-zA-Z0-9_-]*',
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
                    'registration' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'registration/:action[/:token]',
                            'constraints' => array(
                            ),
                            'defaults' => array(
                                'controller'    => 'Index',
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
            'Fryday\Controller\Index'     => 'Fryday\Controller\IndexController',
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
            'fryday/index/index'     => __DIR__ . '/../view/fryday/index/index.phtml',
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
                'route' => 'fryday',
            ),
            array(
                'label' => 'Events',
                'route' => 'fryday/static_pages',
                'action' => 'events',
                'pages' => array(
                    array(
                        'label' => 'View Event', // 'Child #1',
                        'route' => 'fryday/event_details',
                        // 'controller' => 'index',
                        // 'action' => 'profile',
                        // 'params' => array('action' => 'view-event'),
                    ),
                ),
            ),
            array(
                'label' => 'Franchise',
                'route' => 'fryday/static_pages',
                'action' => 'franchise',
            ),
            array(
                'label' => 'Advertise',
                'route' => 'fryday/static_pages',
                'action' => 'advertise',
            ),
            // array(
            //     'label' => 'Partner',
            //     'route' => 'fryday/static_pages',
            //     'action' => 'partner',
            // ),
            // array(
            //     'label' => 'Media',
            //     'route' => 'fryday/static_pages',
            //     'action' => 'media',
            // ),
            array(
                'label' => 'Member',
                'route' => 'fryday/static_pages',
                'action' => 'member',
            ),
            array(
                'label' => 'Venue',
                'route' => 'fryday/static_pages',
                'action' => 'venue',
            ),
            array(
                'label' => 'Speaker',
                'route' => 'fryday/static_pages',
                'action' => 'speaker',
            ),
            // array(
            //     'label' => 'About',
            //     'route' => 'fryday/static_pages',
            //     'action' => 'about',
            // ),
            // array(
            //     'label' => 'Contact',
            //     'route' => 'fryday/static_pages',
            //     'action' => 'contact',
            // ),
            array(
                'label' => 'Dashboard',
                'route' => 'administrator',
                'resource'      => 'Admin\Controller\Index',
                'privilege'     => 'index',
                // 'class' => 'list-group-item',
                'pages' => array(
                    array(
                        'label' => '<i class="fa fa-tachometer"></i> Dashboard',
                        'route' => 'administrator',
                        'class' => 'list-group-item',
                    ),
                    array(
                        'label' => '<i class="fa fa-user"></i> Profile',
                        'route' => 'administrator/default',
                        'controller' => 'user',
                        'action' => 'profile',
                        'class' => 'list-group-item',
                        'pages' => array(
                            array(
                                'label' => 'Edit Profile', // 'Child #1',
                                'route' => 'administrator/default',
                                'controller' => 'user',
                                'params' => array('action' => 'edit-profile'),
                            ),
                        ),
                    ),
                    array(
                        'label' => '<i class="fa fa-group"></i> Users',
                        'route' => 'administrator/default',
                        'controller' => 'user',
                        'action' => 'index',
                        'class' => 'list-group-item',
                        'resource'      => 'Admin\Controller\User',
                        'privilege'     => 'index',
                        'pages' => array(
                            array(
                                'label' => 'View', // 'Child #1',
                                'route' => 'administrator/default',
                                'controller' => 'user',
                                // 'action' => 'profile',
                                'params'        => array('action' => 'view'),
                            ),
                        ),
                    ),
                    array(
                        'label' => '<i class="fa fa-glass"></i> Events',
                        'route' => 'administrator/default',
                        'controller' => 'event',
                        'class' => 'list-group-item',
                        'pages' => array(
                            array(
                                'label' => 'Preview', // 'Child #1',
                                'route' => 'administrator/event_preview',
                                // 'controller' => 'user',
                                // 'action' => 'profile',
                                // 'params' => array('action' => 'profile'),
                                // 'resource' => 'Album\Controller\Album',
                                // 'privilege'    => 'add',
                            ),
                        ),
                    ),
                    array(
                        'label' => '<i class="fa fa-briefcase"></i> Partners',
                        'route' => 'administrator/default',
                        'controller' => 'partner',
                        'class' => 'list-group-item',
                    ),
                    array(
                        'label' => '<i class="fa fa-envelope"></i> Mailer',
                        'route' => 'administrator/default',
                        'controller' => 'mailer',
                        'class' => 'list-group-item',
                        'resource' => 'Admin\Controller\Mailer',
                        'privilege'    => 'index',
                    ),
                    array(
                        'label' => '<i class="fa fa-database "></i> Subscribers',
                        'route' => 'administrator/paginator',
                        'controller' => 'subscriber',
                        'class' => 'list-group-item',
                        'resource' => 'Admin\Controller\Subscribers',
                        'privilege'    => 'index',
                    ),
                    array(
                        'label' => '<i class="fa fa-map-marker"></i> Cities',
                        'route' => 'administrator/default',
                        'controller' => 'city',
                        'class' => 'list-group-item',
                        'resource' => 'Admin\Controller\City',
                        'privilege'    => 'index',
                    ),
                    array(
                        'label' => '<i class="fa fa-building"></i> Venues',
                        'route' => 'administrator/default',
                        'controller' => 'venue',
                        'class' => 'list-group-item',
                    ),
                ),
            ),
        )
    ),
);
