<?php 
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

use Main\Entity\Place;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AddNewsForm extends Form
{
	protected $entityManager;
	protected $_dir;

	public function __construct(ObjectManager $em, $dir, $name = null)
	{
		parent::__construct($name);

		$this->_dir = $dir;
		$this->entityManager = $em;
		// $this->addElements();
		$this->setAttribute('method', 'post');
		// $this->addInputFilter();

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
			'name' => 'picture-selection-for-news',
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
			'name' => 'place',
			'attributes' => array(
				'type' => 'select',
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Select Place',
				'value_options' => $this->getAllPlaces(),
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

        $this->addInputFilter();
	}

	private function getAllPlaces()
    {
    	$places = array();

        $placesSet = $this->entityManager
            ->getRepository('Main\Entity\Place')
            ->findAll();

        foreach ($placesSet as $place) {
            $places[$place->getId()] = $place->getName();
        }

        return $places;
    }

    /**
	 * Adding a RenameUpload filter to our form’s file input, with details on where the valid files should be stored
	 */
    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('picture-selection-for-news');
        $fileInput->setRequired(true);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 4194304));
            // ->attachByName('filemimetype',  array('mimeType' => 'image/png, image/x-png, image/jpeg'));
//            ->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => $this->_dir, // './data/tmpuploads/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}