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
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * User Repository
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 */

class SubscriberRepository extends EntityRepository
{
    public function getCount()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('count(s.id)')
            ->from('Admin\Entity\Subscriber', 's');

        return  $qb->getQuery()->getSingleScalarResult();
    }

    public function getPagedSubscribers($offset = 0, $limit = 10)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('s')
            ->from('Admin\Entity\Subscriber', 's')
            ->orderBy('s.email')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $qb->getQuery();
        // $query = $qb->getQuery()->getResult();
        $paginator = new Paginator( $query );

        return $paginator;
        // return $query; 
    }

	public function isSubscriberExistByEmail($email)
	{
		$em = $this->getEntityManager();
		$qb = $em->createQueryBuilder();

		$qb->select( 's.id' )
            ->from( 'Admin\Entity\Subscriber',  's' )
            ->where('s.email = :email')
            ->setParameter('email', $email)
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

	public function getSubscriberByEmail($email)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 's.id' )
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