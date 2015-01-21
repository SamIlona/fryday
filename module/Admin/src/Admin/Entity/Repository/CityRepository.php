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
 * City Repository
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 */

class CityRepository extends EntityRepository
{
    public function getCount()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('count(c.id)')
            ->from('Admin\Entity\City', 'c');

        return  $qb->getQuery()->getSingleScalarResult();
    }

    public function getLastAddedCity()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select( 'c' )
            ->from( 'Admin\Entity\City',  'c' )
            ->orderBy('c.id', 'DESC')
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

	public function getAllCitiesAsOptions()
	{
		$citiesSet = $this->getEntityManager()->getRepository('Admin\Entity\City')->findAll();

        if($citiesSet != null) 
        {
    		foreach ($citiesSet as $city) 
            {
    			$cities[$city->getId()] = $city->getName();
    		}
            return $cities;
        }
        else
        {
            return array();
        }
	}

	public function getCityByName($name)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 'c' )
            ->from( 'Admin\Entity\City',  'c' )
            ->where('c.name = :name')
            ->setParameter('name', $name);

        $city = $qb->getQuery()->getSingleResult();

		return $city;
	}

	// TODO: Check the correctness!!
	public function getCityByID($id)
	{
		$em = $this->getEntityManager();

		$qb = $em->createQueryBuilder();

		$qb->select( 'c' )
            ->from( 'Admin\Entity\City',  'c' )
            ->where('c.id = :id')
            ->setParameter('id', $id);

        $city = $qb->getQuery()->getSingleResult();

		return $city;
	}
}