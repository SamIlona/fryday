<?php
/**
 * @category   Fryday_Application
 * @package    
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
 * City controller
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Controller
 */
class CityController extends Action
{
	/**
     * List All Cities
     *
     * @return \Zend\View\Model\ViewModel|array
     */
	public function indexAction()
    {
        $this->entityManager = $this->getEntityManager();
        $cities = $this->entityManager->getRepository('Admin\Entity\City')->findAll();

        return array(
            'cities' => $cities,
            // 'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function createAction()
    {
    	$this->entityManager = $this->getEntityManager();

        $createCityForm = new Form\CreateCityForm('new-city-form', $this->entityManager);
        $createCityForm->setHydrator(new DoctrineHydrator($this->entityManager, 'Admin\Entity\City'));
        $cityEntity = new Entity\City();
        $createCityForm->bind($cityEntity);

        $request = $this->getRequest();
        if($request->isPost())
        {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            // var_dump($post);

            $createCityForm->setData($post);

            if($createCityForm->isValid()) 
            {
                $data = $createCityForm->getData();
                $profileImage = $data->getProfileImage();
                $urlProfileImage = explode("./public", $profileImage['tmp_name']);
                $cityEntity->setProfileImage($urlProfileImage[1]);//->setProfileImage($profileImage['tmp_name']);
                $cityEntity->setLabel($data->getName());
                $cityEntity->setRoute('/' . strtolower($data->getCountry()->getName()) . '/' . strtolower($data->getName()));

                $this->entityManager->persist($cityEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('administrator_content/default', array('controller' => 'city', 'action' => 'index'));
            }
        }

        return array(
            'form' => $createCityForm,
        );
    }

    public function viewAction()
    {
        $cityId = $this->params()->fromRoute('id');
        // if (!empty($parentId)) {
        //     $routeName = 'content/document/create-w-parent';
        // } else {
        //     $routeName = 'content/document/create';
        // }

		return array(
			'cityId' => $cityId,
        );
    }
}