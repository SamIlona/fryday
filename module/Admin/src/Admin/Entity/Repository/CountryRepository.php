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
 * Country Repository
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 */

class CountryRepository extends EntityRepository
{
	public function getAllCountriesAsOptions()
	{
		$countriesSet = $this->getEntityManager()->getRepository('Admin\Entity\Country')->findBy(array(), array('name'=>'asc'));

		foreach ($countriesSet as $country) {
			$countries[$country->getId()] = $country->getName();
		}

		return $countries;
	}
}