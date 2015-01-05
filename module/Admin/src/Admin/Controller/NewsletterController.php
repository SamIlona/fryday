<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin\Controller;

use Admin\Form;

use Admin\Entity;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


/**
 * Subscriber controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class NewsletterController extends Action
{
    /**
     * List All Users
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $subscribers = $this->entityManager->getRepository('Admin\Entity\Subscriber')->findAll();

        return array(
            'subscribers' => $subscribers,
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createAction()
    {
        $em = $this->getEntityManager();
        $eventID = $this->params()->fromRoute('id');
        $eventEntity = $em->getRepository('Admin\Entity\Event')->findOneBy(array('id' => $eventID));

        $createNewsletterForm = new Form\CreateNewsletterForm('create-subscriber-form', $em);
        $createNewsletterForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Newsletter'));
        $newletterEntity = new Entity\Newsletter();
        $createNewsletterForm->bind($newletterEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();

            $createNewsletterForm->setData($post);

            if($createNewsletterForm->isValid()) 
            {
                $data = $createNewsletterForm->getData();
                $newletterEntity->setEvent($eventEntity);
                $eventEntity->setNewsletterCreated(true);
                $eventEntity->setNewsletter($newletterEntity);

                $this->entityManager->persist($eventEntity);
                $this->entityManager->persist($newletterEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator_content/event_preview', array(
                    'country'   => $eventEntity->getCity()->getCountry()->getName(), 
                    'city'      => $eventEntity->getCity()->getName(), 
                    'dateslug'  => $eventEntity->getDateSlug(), 
                    'titleslug' => $eventEntity->getTitleSlug(), 
                    )
                );
            }
        }

        return array(
            'form' => $createNewsletterForm,
            'event' => $eventEntity,
        );
    }

    public function viewAction()
    {
        $em                 = $this->getEntityManager();
        $newsletterID       = $this->params()->fromRoute('id');
        $newsletterEntity   = $em->getRepository('Admin\Entity\Newsletter')->findOneBy(array('id' => $newsletterID));

        $eventEntity    = $newsletterEntity->getEvent();
        $partner1Entity = $newsletterEntity->getPartner1();
        $partner2Entity = $newsletterEntity->getPartner2();
        $partner3Entity = $newsletterEntity->getPartner3();

        $this->layout('layout/mail-template-1'); 

        return array(
            'event' => $newsletterEntity->getEvent(),
            'partner1' => $newsletterEntity->getPartner1(),
            'partner2' => $newsletterEntity->getPartner2(),
            'partner3' => $newsletterEntity->getPartner3(),
        );
    }

    public function sendAction()
    {
        $em                 = $this->getEntityManager();
        $newsletterID       = $this->params()->fromRoute('id');
        $newsletterEntity   = $em->getRepository('Admin\Entity\Newsletter')->findOneBy(array('id' => $newsletterID));

        $eventEntity        = $newsletterEntity->getEvent();
        $cityID             = $eventEntity->getCity()->getId();
        $subscribers        = $this->entityManager->getRepository('Admin\Entity\Subscriber')->getSubscribersByCityID($cityID);

        foreach ($subscribers as $subscriber) 
        {
            $mailService = $this->getServiceLocator()->get('AcMailer\Service\MailService');
            $mailService->setSubject($eventEntity->getTitle())
                // ->setTemplate('email/tpl/template', 
                ->setTemplate('admin/newsletter/view',
                    array(
                        'event'      => $eventEntity, 
                        'partner1' => $newsletterEntity->getPartner1(),
                        'partner2' => $newsletterEntity->getPartner2(),
                        'partner3' => $newsletterEntity->getPartner3(),
        //                                 'title'     => $title,
        //                                 'details'   => $details,
                    )
                );

            $message = $mailService->getMessage();
            $message->setTo($subscriber->getEmail());

            $result = $mailService->send();
                        // if ($result->isValid()) {
                        //     // echo 'Message sent. Congratulations!';
                        // } else {
                        //     if ($result->hasException()) {
                        //         echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());

                        //     } else {
                        //         echo sprintf('An error occurred. Message: %s', $result->getMessage());
                                
                        //     }
                        //     // return $this->redirect()->toRoute('administrator/default');
                        //     // return new ViewModel();
                        // }
        }

        return $this->redirect()->toRoute('administrator_content/event_preview', 
            array(
                'country'   => $eventEntity->getCity()->getCountry()->getName(), 
                'city'      => $eventEntity->getCity()->getName(), 
                'dateslug'  => $eventEntity->getDateSlug(), 
                'titleslug' => $eventEntity->getTitleSlug(), 
            )
        );
    }
}