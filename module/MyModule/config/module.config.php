<?php

return array(
 
    'controllers' => array(
        'invokables' => array(
            'MyModule\Controller\Do' => 'MyModule\Controller\DoController',
            )
    ),
 
    'console' => array(
        'router' => array(
            'routes' => array(
                'get-happen-use' => array(
                    'options' => array(
                                    // add [ and ] if optional ( ex : [<doname>] )
                        // 'route' => 'get happen [--verbose|-v] <doname>',
                        'route' => 'get happen',
                        'defaults' => array(
                            '__NAMESPACE__' => 'MyModule\Controller',
                            'controller' => 'do',
                            'action' => 'donow'
                        ),
                    ),
                ),
            )
        )
    ),
 
);