<?php
/**
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Content\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Venue Repository
 *
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Entity
 */

class VenueRepository extends EntityRepository
{
	public function getAllVenuesAsOptions($user = null)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$venues = array();

		$qb->select( 'v' )
            ->from( 'Content\Entity\Venue',  'v' );

        if($user->getRole()->getName() != 'administrator')
        {
        	$qb->where('v.city = :cityID')
        		->setParameter('cityID', $user->getCity()->getId());
        }

        $venuesSet = $qb->getQuery()->getResult();

		// $venuesSet = $this->getEntityManager()->getRepository('Content\Entity\Venue')->findAll();

		foreach ($venuesSet as $venue) {
			$venues[$venue->getId()] = $venue->getName();
		}

		return $venues;
	}
}