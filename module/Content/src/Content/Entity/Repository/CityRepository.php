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
 * City Repository
 *
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Entity
 */

class CityRepository extends EntityRepository
{
	public function getAllCitiesAsOptions()
	{
		$citiesSet = $this->getEntityManager()->getRepository('Content\Entity\City')->findAll();

		foreach ($citiesSet as $city) {
			$cities[$city->getId()] = $city->getName();
		}

		return $cities;
	}

	public function getCityByName($name)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 'c' )
            ->from( 'Content\Entity\City',  'c' )
            ->where('c.name = :name')
            ->setParameter('name', $name);

        $events = $qb->getQuery()->getSingleResult();

		return $events;
	}

	// TODO: Check the correctness!!
	public function getCityByID($id)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 'c' )
            ->from( 'Content\Entity\City',  'c' )
            ->where('c.id = :id')
            ->setParameter('id', $id);

        $events = $qb->getQuery()->getSingleResult();

		return $events;
	}
}