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
	public function getEventsForIndexPage($limit, $offset)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 'e' )
            ->from( 'Content\Entity\Event',  'e' )
            ->setMaxResults( $limit )
            ->setFirstResult( $offset )
            ->orderBy('e.dateTimeEvent', 'ASC');

        $events = $qb->getQuery()->getResult();

		return $events;
	}
}