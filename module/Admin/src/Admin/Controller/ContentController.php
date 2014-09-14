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
use Main\Entity\Place;

use Admin\Form\AddNewsForm;
use Admin\Form\AddPlaceForm;
use Admin\Form\UploadForm;

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
        $fileManagerDir = $config['files_dir']['dir']; // 44:00

        $this->_dir = realpath($fileManagerDir) . DIRECTORY_SEPARATOR . 'ukraine' . DIRECTORY_SEPARATOR . 'test';
    }

	public function eventsAction()
    {
        
        return new ViewModel();
    }

   	public function newsAction()
    {
        $news = $this->getEntityManager()->getRepository('Main\Entity\News')->findAll();
        return array(
            'tableData' => $news,
        );
    }

    public function placesAction()
    {
        $places = $this->getEntityManager()->getRepository('Main\Entity\Place')->findAll();
        return array(
            'placesTableData' => $places,
        );
    }

    public function addPlaceAction()
    {
        $em = $this->getEntityManager();
        
        $qb = $em->createQueryBuilder();

        $form = new AddPlaceForm($em);

        $place = new Place();

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            // appling changes
            $cityId = $form->get('city')->getValue();
            $city = $qb
                ->select('ci')
                ->from('Main\Entity\City', 'ci')
                ->where('ci.id = :id')
                ->setParameter('id', $cityId)
                ->getQuery()
                ->getSingleResult();
            $place->setCity($city);

            $countryId = $form->get('country')->getValue();
            $country = $qb
                ->select('co')
                ->from('Main\Entity\Country', 'co')
                ->where('co.id = :id')
                ->setParameter('id', $countryId)
                ->getQuery()
                ->getSingleResult();
            $place->setCountry($country);

            $name = $form->get('name')->getValue();
            $place->setName($name);

            if($form->isValid()) {
                $this->entityManager->persist($place);
                $this->entityManager->flush();
            }

            return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'places' ));
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
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $this->init();

        if (!is_dir($this->_dir)) {
            mkdir($this->_dir, 0777);
        }
        $dir = $this->_dir;

        $form = new AddNewsForm($em, $dir, 'admin-add-news');
        $news = new News();

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            // appling changes
            $title = $form->get('title')->getValue();
            $news->setTitle($title);

            $text = $form->get('text')->getValue();
            $news->setText($text);

            $placeId = $form->get('place')->getValue();
            $place = $qb
                ->select('p')
                ->from('Main\Entity\Place', 'p')
                ->where('p.id = :id')
                ->setParameter('id', $placeId)
                ->getQuery()
                ->getSingleResult();
            $news->setPlace($place);

            if($form->isValid()) {
                $data = $form->getData();
                $news->setImage($data['picture-selection-for-news']['tmp_name']);
                // var_dump($data);
                $em->persist($news);
                $em->flush();
                return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'news'));
                // return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'add-news'));
            }

            // return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'add-news'));
        }

        return array(
            'form' => $form,
        );
    }

    public function veiwNewsAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) return $this->redirect()->toRoute('admin/default', array('controller' => 'user-doctrine-dbal', 'action' => 'index'));
    }

    public function editNewsAction()
    {
        return new ViewModel();
    }

    public function addPlace()
    {
        return new ViewModel();
    }

    protected function setFileNames($data)
    {
        unset($data['submit']);
        foreach ($data['image-file'] as $key => $file) {
            rename($file['tmp_name'], $this->_dir . DIRECTORY_SEPARATOR . $file['name']);
        }       
    }   
}
