<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin;

// for Acl
use Admin\Acl\Acl;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__   => __DIR__ . '/src/' . __NAMESPACE__,
                    'Fryday'        => __DIR__ . '/../../library/Fryday/',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'mail.transport' => function (ServiceManager $serviceManager) {
                    $config = $serviceManager->get('Config');
                    $transport = new SmtpTransport();                
                    // via GMAIL
                    //$transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                    // via LOCAL
                    $transport->setOptions(new SmtpOptions(
                            array(
                                'name' => 'localhost.localdomain',
                                'host' => '127.0.0.1',
                                'port' => 25,
                            )
                        )
                    );
                    return $transport;
                },
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ),
        );
    }

    // FOR Authorization
    public function onBootstrap(\Zend\EventManager\EventInterface $e) // use it to attach event listeners
    {
        $application = $e->getApplication();
        $em = $application->getEventManager();
        $em->attach('route', array($this, 'onRoute'), -100);
    }
    
    // WORKING the main engine for ACL
    public function onRoute(\Zend\EventManager\EventInterface $e) // Event manager of the app
    {
        $application = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $application->getServiceManager();
        $auth = $sm->get('Zend\Authentication\AuthenticationService');
        $config = $sm->get('Config');
        $acl = new Acl($config);
        // everyone is guest untill it gets logged in
        $role = Acl::DEFAULT_ROLE; // The default role is guest $acl
/* Without Doctrine
        if ($auth->hasIdentity()) {
            $usr = $auth->getIdentity();
            $usrl_id = $usr->usrl_id; // Use a view to get the name of the role
            // TODO we don't need that if the names of the roles are comming from the DB
            switch ($usrl_id) {
                case 1 :
                    $role = Acl::DEFAULT_ROLE; // guest
                    break;
                case 2 :
                    $role = 'member';
                    break;
                case 3 :
                    $role = 'admin';
                    break;
                default :
                    $role = Acl::DEFAULT_ROLE; // guest
                    break;
            }
        }
*/
        // with Doctrine
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            // $usrlId = $user->getRole(); // Use a view to get the name of the role
            //     // TODO we don't need that if the names of the roles are comming from the DB
            //     switch ($usrlId) {
            //         case 0 :
            //             $role = Acl::DEFAULT_ROLE; // guest
            //             break;
            //         case 1 :
            //             $role = 'franchisor';
            //             break;
            //         case 2 :
            //             $role = 'administrator';
            //             break;
            //         // case 3 :
            //         //     $role = 'admin';
            //         //     break;
            //         default :
            //             $role = Acl::DEFAULT_ROLE; // guest
            //             break;
            // }
            $role = $user->getRole()->getName();
        }

        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        // if (!$acl->hasResource($controller)) {
        //  throw new \Exception('Resource ' . $controller . ' not defined');
        // }
        
        if (!$acl->isAllowed($role, $controller, $action)) {
            $url = $e->getRouter()->assemble(array(), array(
                'name' => 'administrator_login'
                ));
            $response = $e->getResponse();

            $response->getHeaders()->addHeaderLine('Location', $url);
            // The HTTP response status code 302 Found is a common way of performing a redirection.
            // http://en.wikipedia.org/wiki/HTTP_302
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }
}