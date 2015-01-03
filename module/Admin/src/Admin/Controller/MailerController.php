<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin\Controller;


use Admin\Form;

use Fryday\Mvc\Controller\Action;

use Zend\Mail;

// for email library  
// use Zend\Mail;  
use Zend\Mime;
use Zend\Mime\Part as MimePart;  
use Zend\Mime\Message as MimeMessage;  

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Zend\View\Model\ViewModel;

/**
 * Sendmailtest
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Controller
 */
class MailerController extends Action 
{
	public function indexAction()
	{
		$this->entityManager = $this->getEntityManager();

        $mailerForm = new Form\MailerForm('mailer-form', $this->entityManager);
        // $createSubscriberForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\Subscriber'));
        
        // $subscriberEntity = new Entity\Subscriber();
        // $createSubscriberForm->bind($subscriberEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
        //     // // Make certain to merge the files info!
        //     // $post = array_merge_recursive(
        //     //     $request->getPost()->toArray(),
        //     //     $request->getFiles()->toArray()
        //     // );
            $mailerForm->setData($request->getPost());

            if($mailerForm->isValid()) 
            {
            	$cityID = $mailerForm->get('city')->getValue();
	            $subscribers = $this->entityManager->getRepository('Admin\Entity\Subscriber')->getSubscribersByCityID($cityID);
				// var_dump($subscribers);

				foreach ($subscribers as $subscriber) {

					$this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
					$content = $this->renderer->render('email/tpl/template', null);
					$html = new MimePart($content);  
					$html->type = "text/html"; 

					$pathToImage = "public/img/anders_avatar.jpg";
					$image = new MimePart(fopen($pathToImage, 'r'));
					$image->type = "image/jpeg";

					$body = new MimeMessage();
					$body->setParts(array($html, $image));

					$mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');
	            	$mailService->setSubject('FRYDAY NEWSLETER IN ACTION')
	            				// ->setBody('Body');
	            				->setBody($body);
	            				// ->setTemplate('admin/emails/mail');
	            				// ->setTemplate('email/tpl/template');


		            $message = $mailService->getMessage();
					$message->setTo($subscriber->getEmail());

					$result = $mailService->send();
					if ($result->isValid()) {
					    echo 'Message sent. Congratulations!';
					} else {
					    if ($result->hasException()) {
					        echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());

					    } else {
					        echo sprintf('An error occurred. Message: %s', $result->getMessage());
					        
					    }
					    // return $this->redirect()->toRoute('administrator/default');
					    // return new ViewModel();
					}
				}
	   //          $mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');
	   //          $mailService->setSubject('This is the subject')->setBody('This is the body');

	   //          $message1 = $mailService->getMessage();
				// $message1->addTo('399115@gmail.com');

				// $result = $mailService->send();
				// if ($result->isValid()) {
				//     echo 'Message sent. Congratulations!';
				// } else {
				//     if ($result->hasException()) {
				//         echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());

				//     } else {
				//         echo sprintf('An error occurred. Message: %s', $result->getMessage());
				        
				//     }
				//     // return $this->redirect()->toRoute('administrator/default');
				//     return new ViewModel();
				// }

        //         $data = $createSubscriberForm->getData();

        //         // var_dump($data);

        // //         $profileImage = $data->getProfileImage();
        // //         $urlProfileImage = explode("./public", $profileImage['tmp_name']);
        // //         $userEntity->setProfileImage($urlProfileImage[1]);

        //         $this->entityManager->persist($subscriberEntity);
        //         $this->entityManager->flush();

                // return $this->redirect()->toRoute('administrator/default', array('controller' => 'mailer', 'action' => 'index'));
            }
        }

        return array(
            'form' => $mailerForm,
        );
		// $mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');

		// $mailService->setSubject('This is the subject')
  //           ->setBody('This is the body'); // This can be a string, HTML or even a zend\Mime\Message or a Zend\Mime\Part

  //       $message = $mailService->getMessage();
		// $message->addTo('399115@gmail.com');

		// $result = $mailService->send();
		// if ($result->isValid()) {
		//     echo 'Message sent. Congratulations!';
		// } else {
		//     if ($result->hasException()) {
		//         echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());

		//     } else {
		//         echo sprintf('An error occurred. Message: %s', $result->getMessage());
		        
		//     }
		//     // return $this->redirect()->toRoute('administrator/default');
		//     return new ViewModel();
		// }

		// return new ViewModel();
		// HTML 
		// $this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
		// $content = $this->renderer->render('email/tpl/template', null);
		// $html = new MimePart($content);  
		// $html->type = "text/html";   

		// // ATTACH
		// // $fileContent = null;
		// $fileContent = fopen('./public/img/anders_avatar.jpg', 'r');
		// $attachment = new MimePart($fileContent);
		// $attachment->type = 'image/jpg';
		// $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;

		// BODY
		// $body = new MimeMessage();  
		// $body->setParts(array($html,)); 
		// // $body->setParts(array($html,$attachment)); 
		  
		// // instance mail   
		// $message = new Message();  
		// // $message->setBody($body) // will generate our code html from template.phtml  
		// $message->addTo('399115@gmail.com')
		//         ->addFrom('info@test.net')
		//         ->setSubject('FRYDAY NEWSLETTERS TEST');

		// $transport = $this->getServiceLocator()->get('mail.transport');
		// $transport->send($message);

		// $this->flashMessenger()->addMessage('Newsletter successfully send to recipients');

		// return $this->redirect()->toRoute('administrator/default');
		


		// return new ViewModel();
	}
}