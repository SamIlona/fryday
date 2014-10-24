<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Controller;

use Fryday\Mvc\Controller\Action;

use Zend\Mail;

// for email library  
// use Zend\Mail;  
Use Zend\Mime;
use Zend\Mime\Part as MimePart;  
use Zend\Mime\Message as MimeMessage;  

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Sendmailtest
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Controller
 */
class SendmailtestController extends Action 
{
	public function indexAction()
	{
		// HTML 
		$this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
		$content = $this->renderer->render('email/tpl/template', null);
		$html = new MimePart($content);  
		$html->type = "text/html";   

		// ATTACH
		// $fileContent = null;
		$fileContent = fopen('./public/img/anders_avatar.jpg', 'r');
		$attachment = new MimePart($fileContent);
		$attachment->type = 'image/jpg';
		// $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;

		// BODY
		$body = new MimeMessage();  
		$body->setParts(array($html,)); 
		// $body->setParts(array($html,$attachment)); 
		  
		// instance mail   
		$message = new Message();  
		$message->setBody($body) // will generate our code html from template.phtml  
		 		->addTo('399115@gmail.com')
		        ->addFrom('fryday-team@fryday.net')
		        ->setSubject('FRYDAY NEWSLETTERS TEST');

		$transport = $this->getServiceLocator()->get('mail.transport');
		$transport->send($message);

		$this->flashMessenger()->addMessage('Newsletter successfully send to recipients');

		return $this->redirect()->toRoute('backend/default', array('controller' => 'event', 'action' => 'index'));
	}
}