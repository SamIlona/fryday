<?php
/**
 * @category   Fryday
 * @package    Library
 * @subpackage Mvc\Controller
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Fryday\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Doctrine\ORM\EntityManager;

/**
 * Extension of AbstractActionController
 *
 * @category   Fryday
 * @package    Library
 * @subpackage Mvc\Controller
 */

class Action extends AbstractActionController
{
	/**
     * @var Entity Manager
     */
    protected $entityManager; 

    /**
     * @var Authenticated User
     */
    protected $authenticatedUser; 

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

    /**
     * Sets the Authenticated User
     *
     * @access protected
     * @return PostController
     */
    protected function setAuthenticatedUser()
    {
        $this->authenticatedUser = $this->identity();
    }

    /**
     * Returns the Authenticated User
     *
     * @access protected
     * @return Authenticated User
     */
    protected function getAuthenticatedUser()
    {
        if (null === $this->authenticatedUser) {
            $this->setAuthenticatedUser();
        }
        return $this->authenticatedUser;
    }
}