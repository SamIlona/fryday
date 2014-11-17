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

class EventRepository extends EntityRepository
{
	public function getEvents($limit, $offset, $type, $city = null)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

        $timeNow = new \DateTime();

		$qb->select( 'e' )
            ->from( 'Content\Entity\Event',  'e' )
            ->setMaxResults( $limit )
            ->setFirstResult( $offset )
            ->orderBy('e.dateTimeEvent', 'ASC');

        if($type == 'upcoming')
        {
        $qb->where('e.dateTimeEvent > :time');
        } 
        elseif ($type == 'past') 
        {
        $qb->where('e.dateTimeEvent < :time');
        }
        
        $qb->setParameter('time', $timeNow);

        if($city != null)
        {
        	// var_dump($city);

        	$qb->andWhere('e.city = :cityID')
        		->setParameter('cityID', $city->getId());
        }

        $events = $qb->getQuery()->getResult();

		return $events;
	}
}
