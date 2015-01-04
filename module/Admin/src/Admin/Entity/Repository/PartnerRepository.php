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
 * Venue Repository
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 */

class PartnerRepository extends EntityRepository
{
    public function getAllPartnersAsOptions()
    {
        $partnersSet = $this->getEntityManager()->getRepository('Admin\Entity\Partner')->findAll();

        foreach ($partnersSet as $partner) {
            $partners[$partner->getId()] = $partner->getName();
        }

        return $partners;
    }

    public function getLastAddedParner()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'p' )
            ->from( 'Admin\Entity\Partner',  'p' )
            ->orderBy('p.id', 'DESC')
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
