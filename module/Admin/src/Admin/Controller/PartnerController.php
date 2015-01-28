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
 * Venue controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class PartnerController extends Action
{
    protected $_dir = null;

    protected function getUploadPath()
    {
        $config = $this->getServiceLocator()->get('Config');
        $uploadDir = $config['path_to_uploads']['partner'];

        if (!is_dir($uploadDir )) 
        {
            $oldmask = umask(0);
            mkdir($uploadDir , 0777);
            umask($oldmask);
        }

        return $uploadDir;
    }


    /**
     * List All Venues
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $partners = $em->getRepository('Admin\Entity\Partner')->findAll();

        return array(
            'partners' => $partners,
        );
        
        // return array(
        //     'venues' => $venues,
        // );
        // $this->init();
        // $file = urldecode($this->params()->fromRoute('id'));
        // $filename = $this->_dir . DIRECTORY_SEPARATOR . $file;
        // $filename = './data/uploads/venues/phpQMddxI_541497228e23b';
        // $contents = null;
        // if (file_exists($filename)) {
        //     $handle = fopen($filename, "r"); // "r" - not r but b for Windows "b" - keeps giving me errors no file
        //     $contents = fread($handle, filesize($filename));
        //     fclose($handle);
        // }
        // return array(
        //     'contents' => $contents
        // );
    }

    /**
     * Create venue
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createFirstStepAction()
    {
    	$em = $this->getEntityManager();
        $user = $this->getAuthenticatedUser();
        $uploadDir = $this->getUploadPath();

    	$partnerForm = new Form\CreatePartnerFirstStepForm('partner', $em, $user);
        $partnerForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Partner'));
        $partnerEntity = new Entity\Partner();
        $partnerForm->bind($partnerEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();
            $partnerForm->setData($post);

            if($partnerForm->isValid()) 
            {
                // $data = $partnerForm->getData(); 

                $this->entityManager->persist($partnerEntity);
                $this->entityManager->flush();

                $partner = $em->getRepository('Admin\Entity\Partner')->getLastAddedParner();
                $partnerID = $partner->getId();
                $currentPartnerUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $partnerID;               
                if (!is_dir($currentPartnerUploadDir )) 
                {
                    $oldmask = umask(0);
                    mkdir($currentPartnerUploadDir, 0777);
                    umask($oldmask);
                }

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'partner', 'action' => 'create-second-step', 'id' => $partnerID));
            }
        }

        return array(
        	'form' => $partnerForm,
        );
    }

    /**
     * Create venue
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createSecondStepAction()
    {
        $em = $this->getEntityManager();
        $user = $this->getAuthenticatedUser();
        $partnerID = $this->params()->fromRoute('id');
        $uploadDir = $this->getUploadPath();
        $currentPartnerUploadDir = $uploadDir . DIRECTORY_SEPARATOR . $partnerID;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        $partnerForm = new Form\CreatePartnerSecondStepForm('partner', $em, $user, $currentPartnerUploadDir);
        $partnerForm->setHydrator(new DoctrineHydrator($em, 'Admin\Entity\Partner'));
        $partnerEntity = $em->getRepository('Admin\Entity\Partner')->findOneBy(array('id' => $partnerID));
        $partnerForm->bind($partnerEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $partnerForm->setData($post);

            if($partnerForm->isValid()) 
            {
                $dataForm           = $partnerForm->getData();    
                             
                $logoData           = $dataForm->getLogo();
                $logoName           = end(explode("$currentPartnerUploadDir". DIRECTORY_SEPARATOR, $logoData['tmp_name']));
                $thumb              = $thumbnailer->create($logoData['tmp_name'], $options = array(), $plugins = array());   
                $currentDimantions  = $thumb->getCurrentDimensions();

                if($post['x'] === '' ||
                    $post['y'] === '') 
                {
                    if($currentDimantions['height'] / $currentDimantions['width'] < 0.5) 
                        $thumb->cropFromCenter($currentDimantions['height'] * 2, $currentDimantions['height']);
                    else 
                        $thumb->cropFromCenter($currentDimantions['width'], $currentDimantions['width'] / 2);
                }
                else 
                {
                    $scale = $currentDimantions['width'] / $post['cw'];

                    $thumb->crop($post['x'] * $scale, 
                                 $post['y'] * $scale,
                                 $post['w'] * $scale, 
                                 $post['h'] * $scale
                                );
                }

                $thumb->resize(640, 320);
                $resizedImg = $currentPartnerUploadDir . DIRECTORY_SEPARATOR . 'resize_' . $logoName;
                $thumb->save($resizedImg);

                $thumb->resize(224, 112);
                $resizedImg = $currentPartnerUploadDir . DIRECTORY_SEPARATOR . 'mail_' . $logoName;    
                $thumb->save($resizedImg);

                $thumb->resize(144, 72);
                $resizedImg = $currentPartnerUploadDir . DIRECTORY_SEPARATOR . 'carousel144x72_' . $logoName;    
                $thumb->save($resizedImg);

                $partnerEntity->setLogo($logoName);

                $this->entityManager->persist($partnerEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'partner', 'action' => 'index'));
            }
        }

        return array(
            'form'      => $partnerForm,
            'partnerID' => $partnerID,
            'partner'   => $partnerEntity,
        );
    }
}