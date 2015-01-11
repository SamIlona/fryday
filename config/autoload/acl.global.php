<?php
// http://p0l0.binware.org/index.php/2012/02/18/zend-framework-2-authentication-acl-using-eventmanager/
// First I created an extra config for ACL (could be also in module.config.php, but I prefer to have it in a separated file)
return array(
    'acl' => array(
        'roles' => array(
            'guest'         => null,
            'administrator' => 'guest',
            'franchisor'    => 'guest',
        ),
        'resources' => array(
            'allow' => array(
                'Admin\Controller\Index' => array(
                    'index'         => array('administrator', 'franchisor'),
                    'login'         => 'guest',
                    'logout'        => array('administrator', 'franchisor'),
                ),
                'Admin\Controller\User' => array(
                    'all'         => 'administrator',
                ),
                'Admin\Controller\Mailer' => array(
                    'all'         => 'administrator',
                ),
                'Admin\Controller\Subscriber' => array(
                    'all'                   => 'administrator',
                    'do-parse-file-console' => 'guest'
                ),
                'Main\Controller\Index' => array(
                    'all'         => 'guest',
                ),
                'Main\Controller\Javascript' => array(
                    'all'         => 'guest',
                ),
                'Admin\Controller\City' => array(
                    'all'         => 'administrator',
                ),
                'Admin\Controller\Venue' => array(
                    'all'         => array('administrator', 'franchisor'),
                ),
                'Admin\Controller\Event' => array(
                    'all'      => array('administrator', 'franchisor'),
                ),
                'Admin\Controller\Partner' => array(
                    'all'      => array('administrator', 'franchisor'),
                ),
                'Admin\Controller\Newsletter' => array(
                    'all'      => array('administrator', 'franchisor'),
                ),
                'MyModule\Controller\Do' => array(
                    'all'       => 'guest',
                ),
            )
        )
    )
);
