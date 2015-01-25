<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Fryday\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * User Repository
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 */

class Iso4217Repository extends EntityRepository
{
    public function getAllCurrenciesAsOptions()
	{
		$currenciesSet = $this->getEntityManager()->getRepository('Fryday\Entity\Iso4217')->findAll();

		foreach ($currenciesSet as $currency) {
			$currencies[$currency->getId()] = $currency->getCode() . ' ' . $currency->getCurrency();
		}

		return $currencies;
	}
}