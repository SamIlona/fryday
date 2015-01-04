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
                $eventEntity->setNewsletter(true);

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
}