<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * User Repository
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 */

class UserRepository extends EntityRepository
{
    // TODO: Check the correctness!!
    public function getUserByID($id)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();

        $qb->select( 'u' )
            ->from( 'Admin\Entity\User',  'u' )
            ->where('u.id = :id')
            ->setParameter('id', $id);

        $city = $qb->getQuery()->getSingleResult();

        return $city;
    }

	public function getAllUsersAsOptions()
	{
		$usersSet = $this->getEntityManager()->getRepository('Admin\Entity\User')->findAll();

		foreach ($usersSet as $user) {
			$users[$user->getId()] = $user->getFirstName() . " " . $user->getLastName();
		}

		return $users;
	}

    public function getLastAddedUser()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'u' )
            ->from( 'Admin\Entity\User',  'u' )
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1);

        try
        {
            return  $qb->getQuery()->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e)
        {
            return null;
        }
    }
}