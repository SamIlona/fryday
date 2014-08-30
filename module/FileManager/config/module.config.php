<?php

namespace FileManager;

return array(
	'controllers' => array(
        'invokables' => array(
            'FileManager\Controller\Index' => 'FileManager\Controller\IndexController',		
        ),
    ),	
    'router' => array(
        'routes' => array(
			'file-manager' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/file-manager',
					'defaults' => array(
						'__NAMESPACE__' => 'FileManager\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action[/:id]]]', // there is no constraints for id!
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),
		),
	),
    'view_manager' => array(
        'template_path_stack' => array(
            'csn-cms' => __DIR__ . '/../view'
        ),
		
		'display_exceptions' => true,
    ),
	/* uncoment when you have the Entity folder created in the module structure
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
	*/
);