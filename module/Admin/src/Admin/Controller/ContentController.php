<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Main\Entity\News;
use Main\Entity\City;
use Main\Entity\Country;

use Admin\Form\AddNewsForm;
use Admin\Form\AddPlaceForm;

use Doctrine\ORM\EntityManager;

class ContentController extends AbstractActionController
{
    // directory for images store
    protected $_dir = null;

    /**
     * @var EntityManager
     */
    private $entityManager; 

    /**
    * Sets the EntityManager
    *
    * @param EntityManager $em
    * @access protected
    * @return PostController
    */
    protected function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    /**
    * Returns the EntityManager
    *
    * Fetches the EntityManager from ServiceLocator if it has not been initiated
    * and then returns it
    *
    * @access protected
    * @return EntityManager
    */
    protected function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
        }
        return $this->entityManager;
    }

    public function init() 
    {
        $config = $this->getServiceLocator()->get('Config');
        $fileManagerDir = $config['file_manager']['dir'];

        $this->_dir = realpath($fileManagerDir) . DIRECTORY_SEPARATOR . 'ukraine' . DIRECTORY_SEPARATOR . 'kyiv';
    }

	public function eventsAction()
    {
        
        return new ViewModel();
    }

   	public function newsAction()
    {

        return new ViewModel();
    }

    public function placesAction()
    {

        return new ViewModel();
    }

    public function addPlaceAction()
    {
        $this->entityManager = $this->getEntityManager();
        $qb = $this->entityManager->createQueryBuilder();

        $form = new AddPlaceForm($this->entityManager);

        $place = new City();

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            // appling changes
            $city = $form->get('city')->getValue();
            $place->setCity($city);

            $countryId = $form->get('country')->getValue();
            $country = $qb
                ->select('c')
                ->from('Main\Entity\Country', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $countryId)
                ->getQuery()
                ->getSingleResult();

            $place->setCountry($country);            

            if($form->isValid()) {
                $this->entityManager->persist($place);
                $this->entityManager->flush();
            }

            return $this
                ->redirect()
                ->toRoute('admin/default', 
                    array(
                        'controller' => 'content',
                        'action' => 'places'
                    )
                );
        }
        
        return array(
            'form' => $form,
        );
    }

    public function addEventAction()
    {
        return new ViewModel();
    }
    
    public function addNewsAction()
    {
        $this->entityManager = $this->getEntityManager();
        $form = new AddNewsForm();

        $news = new News();

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            // appling changes
            $title = $form->get('title')->getValue();
            $news->setTitle($title);

            if($form->isValid()) {
                $this->entityManager->persist($news);
                $this->entityManager->flush();
            }

            return $this
                ->redirect()
                ->toRoute('admin/default', 
                    array(
                        'controller' => 'content',
                        'action' => 'news'
                    )
                );
        }

        return array(
            'form' => $form,
        );
    }

    public function editNewsAction()
    {
        return new ViewModel();
    }

    public function addPlace()
    {
        return new ViewModel();
    }
}
