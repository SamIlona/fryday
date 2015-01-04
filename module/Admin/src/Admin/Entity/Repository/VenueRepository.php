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

class VenueRepository extends EntityRepository
{
    public function getLastAddedVenue()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'v' )
            ->from( 'Admin\Entity\Venue',  'v' )
            ->orderBy('v.id', 'DESC')
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

	public function getAllVenuesAsOptions($user = null)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$venues = array();

		$qb->select( 'v' )
            ->from( 'Admin\Entity\Venue',  'v' );

        if($user->getRole()->getName() != 'administrator')
        {
        	$qb->where('v.city = :cityID')
        		->setParameter('cityID', $user->getCity()->getId());
        }

        $venuesSet = $qb->getQuery()->getResult();

		// $venuesSet = $this->getEntityManager()->getRepository('Admin\Entity\Venue')->findAll();

		foreach ($venuesSet as $venue) {
			$venues[$venue->getId()] = $venue->getName();
		}

		return $venues;
	}
}