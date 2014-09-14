<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Form
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\InputFilter\Factory as InputFilterFactory;

use Main\Entity\VenueCategory;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Venue form
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Form
 */
class VenueForm extends Form 
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
	/**
     * Constructor
     *
     * @param null|string|int $name Optional name for the element
     */
    public function __construct($name = null, $entityManager)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');
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
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-name');
        $this->add($name);

        $category = new Element\Select('category');
        $category->setLabel('Select Category')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setValueOptions($this->getCategories())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-category');
        $this->add($category);

        $phone = new Element\Text('phone');
        $phone->setLabel('Phone')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-phone');
        $this->add($phone);

        $website = new Element\Text('website');
        $website->setLabel('Website')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-website');
        $this->add($website);

        $email = new Element\Text('email');
        $email->setLabel('Email')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control col-lg-10')
            ->setAttribute('id', 'venue-email');
        $this->add($email); 

        $address = new Element\Text('address');
        $address->setLabel('Address')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-address');
        $this->add($address);

        $profileImage = new Element\File('profileImage');
        $profileImage->setLabel('Profile Image')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'venue-profileImage');
        $this->add($profileImage);

        $city = new Element\Select('city');
        $city->setLabel('Select City')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setValueOptions($this->getCities())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-city');
        $this->add($city);

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
        $imageInput = new InputFilter\FileInput('profileImage');
        // $imageInput->setRequired(true);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => './data/uploads/venues',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }

    /**
     * Fetching all categories
     *
     * @return array|$categories
     */
    private function getCategories()
    {
        $categories = array(
            '0' => '...',
        );

        $categoriesSet = $this
            ->entityManager
            ->getRepository('Main\Entity\VenueCategory')
            ->findAll();

        foreach ($categoriesSet as $category) {
            $categories[$category->getId()] = $category->getName();
        }

        return $categories;
    }

    /**
     * Fetching all cities
     *
     * @return array|$cities
     */
    private function getCities()
    {
        $cities = array(
            '0' => '...',
        );

        $citiesSet = $this
            ->entityManager
            ->getRepository('Main\Entity\City')
            ->findAll();

        foreach ($citiesSet as $city) {
            $cities[$city->getId()] = $city->getName();
        }

        return $cities;
    }
}