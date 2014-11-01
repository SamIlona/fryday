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
}