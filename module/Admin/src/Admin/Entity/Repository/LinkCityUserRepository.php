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

class LinkCityUserRepository extends EntityRepository
{
    public function getUsersLinkedToCity($cityID)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'l' )
            ->from( 'Admin\Entity\LinkCityUser',  'l' )
            ->where('l.city = :city')
            ->setParameter('city', $cityID);

        return $qb->getQuery()->getResult();
    }

    public function isUserLinked($cityID, $userID)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'l' )
            ->from( 'Admin\Entity\LinkCityUser',  'l' )
            ->where('l.city = :city')
            ->andWhere('l.user = :user')
            ->setParameter('city', $cityID)
            ->setParameter('user', $userID);

        try
        {
            $qb->getQuery()->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e)
        {
            return true;
        }

        return false;
    }
}