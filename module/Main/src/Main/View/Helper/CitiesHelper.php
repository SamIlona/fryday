<?php
// http://stackoverflow.com/questions/12510636/how-to-get-entity-manager-in-view-helper-using-doctrine2-zf2
// http://stackoverflow.com/questions/12562538/zf2-creation-of-simple-service-and-access-it-through-viewhelper
namespace Main\View\Helper;

use Zend\View\Helper\AbstractHelper;

use Doctrine\ORM\EntityManager;

class CitiesHelper extends AbstractHelper
{
	protected $sm;
	protected $em;

    public function test()
    {
        $em = $this->sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $em->getRepository('Content\Entity\City')->findAll();
    }
    
    public function __construct($sm) 
    {
        $this->sm = $sm;
    }
}