<?php
/**
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Config
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Content;

return array(
    'controllers' => array(
        'invokables' => array(
            'Content\Controller\City'   => 'Content\Controller\CityController',
            'Content\Controller\Venue'  => 'Content\Controller\VenueController',
            'Content\Controller\Event'  => 'Content\Controller\EventController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __NAMESPACE__ => __DIR__ . '/../view'
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
);