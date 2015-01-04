<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Config
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin;

return array(
    'path_to_uploads' => array(
        'partner'   => 'public/uploads/partners',
        'event'     => 'public/uploads/events',
        'city'      => 'public/uploads/cities',
        'user'      => 'public/uploads/users',
        'venue'     => 'public/uploads/venues'
    ),
    'htimg' => [
        'filters' => [
            'my_thumbnail' => [ // this is  filter service
                'type' => 'thumbnail', // this is a filter loader
                'options' => [  // filter loader passes these options to a Filter which manipulates the image
                    'width' => 100,
                    'height' => 100,
                    'format' => 'jpeg' // format is optional and defaults to the format of given image
                ]
            ]        
        ]
    ],
    'service_manager' => array(
        'factories' => array(
            'navigation'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'admin_navigation'  => 'Fryday\Navigation\Service\AdminNavigationFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index'        => 'Admin\Controller\IndexController',
            'Admin\Controller\User'         => 'Admin\Controller\UserController',
            'Admin\Controller\Mailer'       => 'Admin\Controller\MailerController',
            'Admin\Controller\Subscriber'   => 'Admin\Controller\SubscriberController',
            'Admin\Controller\City'         => 'Admin\Controller\CityController',
            'Admin\Controller\Event'        => 'Admin\Controller\EventController',
            'Admin\Controller\Venue'        => 'Admin\Controller\VenueController',
            'Admin\Controller\Partner'      => 'Admin\Controller\PartnerController',
            'Admin\Controller\Newsletter'   => 'Admin\Controller\NewsletterController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __NAMESPACE__ => __DIR__ . '/../view'
        ),
    ),
    'doctrine' => array(
        'authentication' => array( // this part is for the Auth adapter from DoctrineModule/Authentication
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                // object_repository can be used instead of the object_manager key
                'identity_class' => 'Admin\Entity\User', //'Application\Entity\User',
                'identity_property' => 'email', // 'username', // 'email',
                'credential_property' => 'password', // 'password',
                'credential_callable' => function(Entity\User $user, $passwordGiven) { // not only User
                    // return my_awesome_check_test($user->getPassword(), $passwordGiven);
                    // echo '<h1>callback user->getPassword = ' .$user->getPassword() . ' passwordGiven = ' . $passwordGiven . '</h1>';
                    //- if ($user->getPassword() == md5($passwordGiven)) { // original
                    // ToDo find a way to access the Service Manager and get the static salt from config array
                    // if ($user->getUsrPassword() == md5('aFGQ475SDsdfsaf2342' . $passwordGiven . $user->getUsrPasswordSalt()) &&
                        // $user->getUsrActive() == 1) {
                    if($user->getPassword() == $passwordGiven) {
                        return true;
                    }
                    else {
                        return false;
                    }
                },
            ),
        ),
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
//            'my_annotation_driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    // __DIR__ . '/../module/AuthDoctrine/src/AuthDoctrine/Entity' // 'path/to/my/entities',
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                    // 'H:\PortableApps\PortableGit\projects\btk\module\Auth\src\Auth\Entity' // Stoyan added to use doctrine in Auth module
//-                 __DIR__ . '/../../Auth/src/Auth/Entity', // Stoyan added to use doctrine in Auth module
                    // 'another/path'
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    // 'My\Namespace' => 'my_annotation_driver'
                    // 'AuthDoctrine' => 'my_annotation_driver'
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
//-                 'Auth\Entity' => __NAMESPACE__ . '_driver' // Stoyan added to allow the module Auth also to use Doctrine
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            // 'home' => array(
            //     'type' => 'Zend\Mvc\Router\Http\Literal',
            //     'options' => array(
            //         'route'    => '/',
            //         'defaults' => array(
            //             'controller' => 'Backend\Controller\Index',
            //             'action'     => 'index',
            //         ),
            //     ),
            // ),
            'administrator' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin',
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
                                'controller'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'            => '[0-9:]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'administrator_content' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/content',
                    'defaults' => array(
                        // '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Admin\Controller\Index',
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
                                'controller'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'            => '[0-9:]+',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Admin\Controller',
                            ),
                        ),
                    ),
                    'event_preview' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:country/:city/previewevent/:dateslug/:titleslug',
                            'constraints' => array(
                                'dateslug'      => '[0-9:-]+',
                            ),
                            'defaults' => array(
                                'controller'    => 'Admin\Controller\Event',
                                'action'        => 'pre-view',
                            ),
                        ),
                    ),
                ),
            ),
            'administrator_login' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // 'default' => array(
                    //     'type'    => 'Segment',
                    //     'options' => array(
                    //         'route'    => '[/:controller[/:action[/:id]]]',
                    //         'constraints' => array(
                    //             'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    //             'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    //             'id'         => '[0-9:]+',
                    //         ),
                    //         'defaults' => array(
                    //         ),
                    //     ),
                    // ),
                    // 'admin_login' => array(
                    //     'type'    => 'Segment',
                    //     'options' => array(
                    //         'route'    => '/login',
                    //         'constraints' => array(
                    //             '__NAMESPACE__' => 'Admin\Controller',
                    //             'controller'    => 'Index',
                    //             'action'        => 'login',
                    //         ),
                    //         'defaults' => array(
                    //         ),
                    //     ),
                    // ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'admin' => array(
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
                // 'pages' => array(
                //     array(
                //         'label' => 'Profile', // 'Child #1',
                //         // 'route' => 'album',
                //         'params' => array('action' => 'profile'),
                //         // 'resource' => 'Album\Controller\Album',
                //         // 'privilege'    => 'add',
                //     ),
                // ),
            ),
            array(
                'label' => '<i class="fa fa-group"></i> Users',
                'route' => 'administrator/default',
                'controller' => 'user',
                'action' => 'index',
                'class' => 'list-group-item',
                // 'pages' => array(
                //     array(
                //         'label' => '<i class="fa fa-user"></i> Profile', // 'Child #1',
                //         'route' => 'administrator/default',
                //         'controller' => 'user',
                //         'action' => 'profile',
                //         // 'params' => array('action' => 'profile'),
                //         // 'resource' => 'Album\Controller\Album',
                //         // 'privilege'    => 'add',
                //     ),
                // ),
            ),
            array(
                'label' => '<i class="fa fa-glass"></i> Events',
                'route' => 'administrator_content/default',
                'controller' => 'event',
                'class' => 'list-group-item',
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
            ),
            array(
                'label' => '<i class="fa fa-database "></i> Subscribers',
                'route' => 'administrator/default',
                'controller' => 'subscriber',
                'class' => 'list-group-item',
            ),
            array(
                'label' => '<i class="fa fa-map-marker"></i> Cities',
                'route' => 'administrator_content/default',
                'controller' => 'city',
                'class' => 'list-group-item',
            ),
            array(
                'label' => '<i class="fa fa-building"></i> Venues',
                'route' => 'administrator_content/default',
                'controller' => 'venue',
                'class' => 'list-group-item',
            ),
            // array(
            //     'label' => '<i class="fa fa-cog"></i> Settings',
            //     'route' => 'administrator/default',
            //     'controller' => 'profile',
            //     'class' => 'list-group-item',
            // ),
        ),
    ),
);