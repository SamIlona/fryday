<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Role Repository
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Entity
 */

class RoleRepository extends EntityRepository
{
	public function getAllRolesAsOptions()
	{
		$rolesSet = $this->getEntityManager()->getRepository('Backend\Entity\Role')->findAll();

		foreach ($rolesSet as $role) {
			$roles[$role->getId()] = $role->getName();
		}

		return $roles;
	}
}