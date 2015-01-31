<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\InputFilter\Factory as InputFilterFactory;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Venue form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class EditVenueForm extends Form 
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var _dir
     */
    protected $_dir;

    /**
     * Constructor
     *
     * @param null|string|int $name Optional name for the element
     */
    public function __construct($name = null, $entityManager, $dir)
    {
        parent::__construct($name);
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Admin\Entity\Venue'));
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form',
            )
        );
        $this->_dir = $dir;
        $this->entityManager = $entityManager;
        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * Venue form elements
     *
     * @return void
     */
    public function addElements()
    {
        $name = new Element\Text('name');
        $name->setLabel('Name')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-name');
        $this->add($name);

        $city = new Element\Select('city');
        $city->setLabel('Select City')
            ->setLabelAttributes(array('class' => 'label'))
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\City')->getAllCitiesAsOptions())
            ->setEmptyOption('Select city...')
            ->setOptions(array('disable_inarray_validator' => true))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-city');
        $this->add($city);

        $xStartCrop = new Element\Hidden('xStartCrop');
        $xStartCrop->setAttribute('id', 'x-start-crop');
        $this->add($xStartCrop);

        $yStartCrop = new Element\Hidden('yStartCrop');
        $yStartCrop->setAttribute('id', 'y-start-crop');
        $this->add($yStartCrop);

        $widthCrop = new Element\Hidden('widthCrop');
        $widthCrop->setAttribute('id', 'width-crop');
        $this->add($widthCrop);

        $heightCrop = new Element\Hidden('heightCrop');
        $heightCrop->setAttribute('id', 'height-crop');
        $this->add($heightCrop);

        $widthCurrent = new Element\Hidden('widthCurrent');
        $widthCurrent->setAttribute('id', 'width-current');
        $this->add($widthCurrent);

        $heightCurrent = new Element\Hidden('heightCurrent');
        $heightCurrent->setAttribute('id', 'height-current');
        $this->add($heightCurrent);

        $phone = new Element\Text('phone');
        $phone->setLabel('Phone')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-phone');
        $this->add($phone);

        $website = new Element\Text('website');
        $website->setLabel('Website')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-website');
        $this->add($website);

        $email = new Element\Text('email');
        $email->setLabel('Email')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-email');
        $this->add($email); 

        $address = new Element\Text('address');
        $address->setLabel('Address')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'id' => 'venue-address',
                // 'placeholder' => 'City',
            ));
        $this->add($address);

        $image = new Element\File('image');
        $image->setLabel('Profile Image')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value')
            ->setAttribute('id', 'venue-image');
        $this->add($image);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Create')
            ->setAttribute('class', 'btn btn-primary btn-sm col-lg-12')
            ->setAttribute('id', 'venue-submit');
        $this->add($submit);
    }

    /**
     * Venue InputFilter
     *
     * @return null
     */
    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // Image Input
        $imageInput = new InputFilter\FileInput('image');
        $imageInput->setRequired(false);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => $this->_dir . DIRECTORY_SEPARATOR . 'original_image',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}