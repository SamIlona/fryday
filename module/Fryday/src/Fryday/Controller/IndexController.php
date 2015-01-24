<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Fryday\Controller;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form;
use Fryday\Form as FrydayForm;
use Admin\Entity;

use Zend\Mail;

// for email library  
// use Zend\Mail;  
use Zend\Mime;
use Zend\Mime\Part as MimePart;  
use Zend\Mime\Message as MimeMessage;  

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class IndexController extends Action
{
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $signupForm = new Form\SignUpForm('sign-up-form', $em);

        return array(
            'eventsFirstLine'   => $em->getRepository('Admin\Entity\Event')->getEvents(4, 0, 'upcoming', 1, 'all'),
            'eventsSecondLine'  => $em->getRepository('Admin\Entity\Event')->getEvents(4, 4, 'upcoming', 1, 'all'),
            'signupform' => $signupForm,
        );
    }

    public function signUpAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();
        $response = $this->getResponse();

        $signupForm = new Form\SignUpForm('sign-up-form', $em);

        if ($request->isPost()) 
        {
            $post = $request->getPost();
            $signupForm->setData($post);

            if($signupForm->isValid()) 
            {
                //TODO: entry email to db
                //      send mail to subscriber
                //      redirect to verify your email
                $data = $signupForm->getData();

                $subscriberEntity = new Entity\Subscriber();
                $signupForm->bind($subscriberEntity);

                $subscriberEntity->setEmail($data['email']);

                $this->prepareData($subscriberEntity);
                $this->sendConfirmationEmail($subscriberEntity);

                $em->persist($subscriberEntity);
                $em->flush();

                $response->setContent(\Zend\Json\Json::encode(array(
                    'success' => true,
                    'error_message' => null,
                )));
                // return $this->redirect()->toRoute('administrator/default', array('controller' => 'subscriber', 'action' => 'index'));
            } 
            else 
            {
                $message = $signupForm->getInputFilter()->getMessages();
                
                $response->setContent(\Zend\Json\Json::encode(array(
                    'error_message' => $message,
                    'success' => false,
                )));
            }
        }
        return $response;
    }

    public function venueAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function partnerAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function franchiseAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function advertiseAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function mediaAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function memberAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function speakerAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function aboutAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function eventsAction()
    {
        $em = $this->getEntityManager();

        return array(
            'events' => $em->getRepository('Admin\Entity\Event')->getEvents(10, 0, 'upcoming', 1, 'all'),
        );
    }
    public function viewEventAction()
    {
        $em = $this->getEntityManager();
        $titleslug = $this->event->getRouteMatch()->getParam('titleslug');
        $dateslug = $this->event->getRouteMatch()->getParam('dateslug');

        return array(
            'event' => $em->getRepository('Admin\Entity\Event')->getEventBySlug($titleslug, $dateslug),
        );
    }
    public function cityDispatcherAction()
    {
        $em = $this->getEntityManager();

        $cityName = $this->getEvent()->getRouteMatch()->getParam('city');
        $city = $em->getRepository('Admin\Entity\City')->getCityByName($cityName);

        
        return array(
            'eventsFirstLine'   => $em->getRepository('Admin\Entity\Event')->getEvents(4, 0, 'upcoming', 1, $city),
            'eventsSecondLine'  => $em->getRepository('Admin\Entity\Event')->getEvents(4, 4, 'upcoming', 1, $city),
        );
    }

    public function confirmEmailAction()
    {
        $em = $this->getEntityManager();
        $token = $this->params()->fromRoute('token');
        $form = new FrydayForm\MemberRegistrationForm('member-regisration-form', $em);
        $viewModel = new ViewModel(array(
            'token' => $token,
            'form' => $form,
        ));
        try
        {
            // $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $em = $this->getEntityManager();
            $user = $em->getRepository('Admin\Entity\Subscriber')->findOneBy(array('registrationToken' => $token)); // 
            // $user->setActive(1);
            $user->setEmailConfirmed(1);
            $em->persist($user);
            $em->flush();
        }
        catch(\Exception $e)
        {
            $viewModel->setTemplate('auth-doctrine/registration/confirm-email-error.phtml');
        }
        return $viewModel;
    }

    public function getCityIdAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();
        $response = $this->getResponse();

        $post = $request->getPost();

        // $parsedPlace = str_getcsv($post['place']);

        // if(count($parsedPlace) === 3)
        // {
        //     $countryName = $parsedPlace[2];
        // }

        // $countryName = $parsedPlace[1];

        // $cityName = $parsedPlace[0];

        $country = $em->getRepository('Admin\Entity\Country')->findOneBy(array('name' => $post['country']));

        $response->setContent(\Zend\Json\Json::encode(array(
            'countryId' => $country->getId(),
        )));

        return $response;
    }

    public function prepareData($user)
    {
        // $user->setUsrActive(0);
        // $user->setUsrPasswordSalt($this->generateDynamicSalt());                
        // $user->setUsrPassword($this->encriptPassword(
        //                         $this->getStaticSalt(), 
        //                         $user->getUsrPassword(), 
        //                         $user->getUsrPasswordSalt()
        // ));
        // $user->setUsrlId(2);
        // $user->setLngId(1);
        // $user->setUsrRegistrationDate(new \DateTime());
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true))); // $this->generateDynamicSalt();
//      $user->setUsrRegistrationToken(uniqid(php_uname('n'), true));   
        $user->setEmailConfirmed(0);
        return $user;
    }

    public function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 50; $i++) {
            $dynamicSalt .= chr(rand(33, 126));
        }
        return $dynamicSalt;
    }
    
    public function getStaticSalt()
    {
        $staticSalt = '';
        $config = $this->getServiceLocator()->get('Config');
        $staticSalt = $config['static_salt'];       
        return $staticSalt;
    }

    public function encriptPassword($staticSalt, $password, $dynamicSalt)
    {
        return $password = md5($staticSalt . $password . $dynamicSalt);
    }
    
    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0) {
         // get count of all required minimum special chars
         $count = $c + $n + $s;
         $out = '';
         // sanitize inputs; should be self-explanatory
         if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
              trigger_error('Argument(s) not an integer', E_USER_WARNING);
              return false;
         }
         elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
              trigger_error('Argument(s) out of range', E_USER_WARNING);
              return false;
         }
         elseif($c > $l) {
              trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
              return false;
         }
         elseif($n > $l) {
              trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
              return false;
         }
         elseif($s > $l) {
              trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
              return false;
         }
         elseif($count > $l) {
              trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
              return false;
         }
     
         // all inputs clean, proceed to build password
     
         // change these strings if you want to include or exclude possible password characters
         $chars = "abcdefghijklmnopqrstuvwxyz";
         $caps = strtoupper($chars);
         $nums = "0123456789";
         $syms = "!@#$%^&*()-+?";
     
         // build the base password of all lower-case letters
         for($i = 0; $i < $l; $i++) {
              $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
         }
     
         // create arrays if special character(s) required
         if($count) 
         {
              // split base password to array; create special chars array
              $tmp1 = str_split($out);
              $tmp2 = array();
     
              // add required special character(s) to second array
              for($i = 0; $i < $c; $i++) {
                   array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
              }
              for($i = 0; $i < $n; $i++) {
                   array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
              }
              for($i = 0; $i < $s; $i++) {
                   array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
              }
     
              // hack off a chunk of the base password array that's as big as the special chars array
              $tmp1 = array_slice($tmp1, 0, $l - $count);
              // merge special character(s) array with base password array
              $tmp1 = array_merge($tmp1, $tmp2);
              // mix the characters up
              shuffle($tmp1);
              // convert to string for output
              $out = implode('', $tmp1);
         }
     
         return $out;
    }
    
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Auth\Model\UsersTable');
        }
        return $this->usersTable;
    }

    public function sendConfirmationEmail($user)
    {
        // $view = $this->getServiceLocator()->get('View');
        // $transport = $this->getServiceLocator()->get('mail.transport');
        // $message = new Message();
        // $this->getRequest()->getServer();  //Server vars
        // $message->addTo($user->getUsrEmail())
        //         ->addFrom('praktiki@coolcsn.com')
        //         ->setSubject('Please, confirm your registration!')
        //         ->setBody("Please, click the link to confirm your registration => " . 
        //             $this->getRequest()->getServer('HTTP_ORIGIN') .
        //             $this->url()->fromRoute('fryday/default', array(
        //                 'controller' => 'index', 
        //                 'action' => 'confirm-email', 
        //                 'id' => $user->getRegistrationToken())));
        // $transport->send($message);
        $this->getRequest()->getServer();  //Server vars
        $mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');
        $mailService->setSubject('Email Confirmation')
            ->setBody("Please, click the link to confirm your registration => http://" . 
                $this->getRequest()->getServer('HTTP_HOST') .
                $this->url()->fromRoute('fryday/registration', array(
                    'controller' => 'index', 
                    'action' => 'confirm-email', 
                    'token' => $user->getRegistrationToken()
            )));

        $message = $mailService->getMessage();
        $message->setTo($user->getEmail());

        $result = $mailService->send();
    }

    public function sendPasswordByEmail($usr_email, $password)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();  //Server vars
        $message->addTo($usr_email)
                ->addFrom('praktiki@coolcsn.com')
                ->setSubject('Your password has been changed!')
                ->setBody("Your password at  " . 
                    $this->getRequest()->getServer('HTTP_ORIGIN') .
                    ' has been changed. Your new password is: ' .
                    $password
                );
        $transport->send($message);     
    }
    
    // ToDo Ask yourself 
    // 1) do we need a separate Entity Registration to handle registration
    // 2) do we have to use form
    // 3) do we have to use User Entity and do what we are doing here. Manually adding removing elements 
    // Is not completed
    public function getRegistrationForm($entityManager, $user)
    {
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $form = $builder->createForm( $user );
        $form->setHydrator(new DoctrineHydrator($entityManager,'AuthDoctrine\Entity\User'));
        $filter = $form->getInputFilter();
        $form->remove('usrlId');
        $form->remove('lngId');
        $form->remove('usrActive');
        $form->remove('usrQuestion');
        $form->remove('usrAnswer');
        $form->remove('usrPicture');
        $form->remove('usrPasswordSalt');
        $form->remove('usrRegistrationDate');
        $form->remove('usrRegistrationToken');
        $form->remove('usrEmailConfirmed');
        
        // ... A lot of work of manually building the form
        
        $form->add(array(
            'name' => 'usrPasswordConfirm',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        )); 

        $form->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please verify you are human',
                'captcha' => new \Zend\Captcha\Figlet(),
            ),
        ));
        
        $send = new Element('submit');
        $send->setValue('Register'); // submit
        $send->setAttributes(array(
            'type'  => 'submit'
        ));
        $form->add($send);  
        // ... 
        return $form;       
    }
}
