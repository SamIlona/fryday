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
                'Main\Controller\Index' => array(
                    'all'         => 'guest',
                ),
                'Content\Controller\City' => array(
                    'all'         => 'administrator',
                ),
                'Content\Controller\Venue' => array(
                    'all'         => array('administrator', 'franchisor'),
                ),
                'Content\Controller\Event' => array(
                    'all'      => array('administrator', 'franchisor'),
                ),
            )
        )
    )
);
