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

class EventRepository extends EntityRepository
{
    public function getLastAddedEvent()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'e' )
            ->from( 'Admin\Entity\Event',  'e' )
            ->orderBy('e.id', 'DESC')
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
	public function getEvents($limit, $offset, $type, $published, $city = null)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

        $timeNow = new \DateTime();

		$qb->select( 'e' )
            ->from( 'Admin\Entity\Event',  'e' )
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

        if($city != 'all')
        {
        	$qb->andWhere('e.city = :cityID')
        		->setParameter('cityID', $city->getId());
        }

        if($published != 'all')
        {
            $qb->andWhere('e.published = :published')
                ->setParameter('published', $published);
        }

        $events = $qb->getQuery()->getResult();

		return $events;
	}

    public function getEventBySlug($titleslug, $dateslug)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();

        $timeNow = new \DateTime();

        $qb->select( 'e' )
            ->from( 'Admin\Entity\Event',  'e' )
            ->where('e.titleslug = :titleslug')
            ->andWhere('e.dateslug = :dateslug')
            ->setParameter('titleslug', $titleslug)
            ->setParameter('dateslug', $dateslug);

        $event = $qb->getQuery()->getSingleResult();

        return $event;
    }
}
