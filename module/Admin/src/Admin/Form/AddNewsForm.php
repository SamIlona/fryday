<?php 
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin\Form;

use Zend\Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AddNewsForm extends Form
{
	public function __construct()
	{
		parent::__construct('add-news-form');

		$this->setAttribute('method', 'post');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
		));

		$this->add(array(
			'name' => 'title',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Title',
			),
		));

		$this->add(array(
			'name' => 'picture',
			'attributes' => array(
				'type' => 'file',
				'id' => 'picture-selection-for-news',
			),
			'options' => array(
				'label' => 'Select Picture',
			),
		));

		$this->add(array(
			'name' => 'text',
			'type' => 'textarea',
			'options' => array(
				'label' => 'News Text',
			),
		));

		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Publish',
                'id' => 'submitbutton',
            ),
        ));
	}
}