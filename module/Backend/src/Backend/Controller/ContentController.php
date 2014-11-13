<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Contorller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Controller;

use Backend\Form;

use Main\Entity;

use Fryday\Mvc\Controller\Action; // use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Content controller
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Controller
 */
class ContentController extends Action
{
	/**
     * List All Events
     *
     * @return \Zend\View\Model\ViewModel|array
     */
	public function indexAction()
    {
		return array(

        );
    }

    public function createAction()
    {
    	$this->entityManager = $this->getEntityManager();
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