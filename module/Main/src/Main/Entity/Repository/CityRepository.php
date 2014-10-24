<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Main\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * City Repository
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Entity
 */

class CityRepository extends EntityRepository
{
	public function getAllCitiesAsOptions()
	{
		$citiesSet = $this->getEntityManager()->getRepository('Main\Entity\City')->findAll();

		foreach ($citiesSet as $city) {
			$cities[$city->getId()] = $city->getName();
		}

		return $cities;
	}
}