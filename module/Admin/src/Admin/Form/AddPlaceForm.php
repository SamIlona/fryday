<?php 
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin\Form;

use Zend\Form\Form;

use Main\Entity\Country;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AddPlaceForm extends Form
{
	protected $entityManager;

	public function __construct(ObjectManager $em)
	{
		parent::__construct('add-place-form');

		$this->entityManager = $em;

		$this->setAttribute('method', 'post');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
		));

		$this->add(array(
			'name' => 'name',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Name',
			),
		));

		$this->add(array(
			'name' => 'country',
			'attributes' => array(
				'type' => 'select',
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Select Country',
				'value_options' => $this->getAllCountries(),
			),
		));

		$this->add(array(
			'name' => 'city',
			'attributes' => array(
				'type' => 'select',
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Select City',
				'value_options' => $this->getAllCities(),
			),
		));

		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Create',
                'id' => 'submitbutton',
            ),
        ));
	}

	private function getAllCountries()
    {
    	$countries = array();
    	
        $countriesSet = $this->entityManager
            ->getRepository('Main\Entity\Country')
            ->findAll();

        foreach ($countriesSet as $country) {
            $countries[$country->getId()] = $country->getName();
        }

        return $countries;
    }

    private function getAllCities()
    {
    	$cities = array();

        $citiesSet = $this->entityManager
            ->getRepository('Main\Entity\City')
            ->findAll();

        foreach ($citiesSet as $city) {
            $cities[$city->getId()] = $city->getName();
        }

        return $cities;
    }
}