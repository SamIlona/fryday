<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin\Controller;

use Fryday\Mvc\Controller\Action; //use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Form\LoginForm;

class IndexController extends Action
{
    public function indexAction()
    {
    	$em = $this->getEntityManager();

        return new ViewModel(array(
        	'overallSubscribers' => $em->getRepository('Admin\Entity\Subscriber')->getCount(),
        	'overallCities' => $em->getRepository('Admin\Entity\City')->getCount(),
   		));
    }

    public function loginAction()
    {
    	$this->entityManager = $this->getEntityManager();

		$form = new LoginForm('login-form', $this->entityManager);
		$form->get('submit')->setValue('Login');
		$messages = null;

		// return $this->redirect()->toRoute('fryday/default', array('controller' => 'index', 'action' => 'index'));

		$request = $this->getRequest();
        if ($request->isPost()) {
            //- $authFormFilters = new User(); // we use the Entity for the filters
			// TODO fix the filters
            //- $form->setInputFilter($authFormFilters->getInputFilter());

			// Filters have been fixed
			// $form->setInputFilter(new LoginFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
			// echo "<h1>I am here1</h1>";
            if ($form->isValid()) {
				$data = $form->getData();			
				// $data = $this->getRequest()->getPost();
				
				// If you used another name for the authentication service, change it here
				// it simply returns the Doctrine Auth. This is all it does. lets first create the connection to the DB and the Entity
				$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');		
				// Do the same you did for the ordinar Zend AuthService	
				$adapter = $authService->getAdapter();
				$adapter->setIdentityValue($data['email']); //$data['usr_name']
				$adapter->setCredentialValue($data['password']); // $data['usr_password']
				$authResult = $authService->authenticate();
				// echo "<h1>I am here</h1>";
				if ($authResult->isValid()) {
					$identity = $authResult->getIdentity();
					$authService->getStorage()->write($identity);
					$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
//-					if ($data['rememberme']) $authService->getStorage()->session->getManager()->rememberMe($time); // no way to get the session
					// if ($data['rememberme']) {
					// 	$sessionManager = new \Zend\Session\SessionManager();
					// 	$sessionManager->rememberMe($time);
					// }
					// return $this->redirect()->toRoute('home');
					return $this->redirect()->toRoute('administrator');
				}
				foreach ($authResult->getMessages() as $message) {
					$messages .= "$message\n"; 
				}	

		/*
				$identity = $authenticationResult->getIdentity();
				$authService->getStorage()->write($identity);

				$authenticationService = $this->serviceLocator()->get('Zend\Authentication\AuthenticationService');
				$loggedUser = $authenticationService->getIdentity();
		*/
			}
		}

		$this->layout('layout/main'); 

		return new ViewModel(array(
			'error' => 'Your authentication credentials are not valid',
			'form'	=> $form,
			'messages' => $messages,
		));
    }


    public function logoutAction()
    {
        // in the controller
        // $auth = new AuthenticationService();
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

        // @todo Set up the auth adapter, $authAdapter


        if ($auth->hasIdentity()) {
            // Identity exists; get it
            $identity = $auth->getIdentity();
//-         echo '<pre>';
//-         print_r($identity);
//-         echo '</pre>';
        }
        $auth->clearIdentity();
//-     $auth->getStorage()->session->getManager()->forgetMe(); // no way to get to the sessionManager from the storage
        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();
        
        // $view = new ViewModel(array(
        //    'message' => 'Hello world',
        // ));
        // $view->setTemplate('foo/baz-bat/do-something-crazy');
        // return $view;
        
        // return $this->redirect()->toRoute('home');
        return $this->redirect()->toRoute('fryday', array('controller' => 'index', 'action' => 'index'));
    }
}