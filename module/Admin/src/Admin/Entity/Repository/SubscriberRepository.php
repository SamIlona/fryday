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

class SubscriberRepository extends EntityRepository
{
	public function getSubscriberByEmail($email)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 's' )
            ->from( 'Admin\Entity\Subscriber',  's' )
            ->where('s.email = :email')
            ->setParameter('email', $email);

		try
        {
            return  $qb->getQuery()->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e)
        {
            return null;
        }
	}

	public function getSubscribersByCityID($id)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 's' )
            ->from( 'Admin\Entity\Subscriber',  's' )
            ->where('s.city = :id')
            ->setParameter('id', $id);

		return $qb->getQuery()->getResult();
	}
}