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
 * City form
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Form
 */
class CityForm extends Form 
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
            ->setAttribute('id', 'city-name');
        $this->add($name);

        $profileImage = new Element\File('profileImage');
        $profileImage->setLabel('Profile Image')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'city-profileImage');
        $this->add($profileImage);

        $country = new Element\Select('country');
        $country->setLabel('Country')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setValueOptions($this->getVenues())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'city-country');
        $this->add($country);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Create')
            ->setAttribute('class', 'btn btn-primary btn-sm col-lg-12')
            ->setAttribute('id', 'venue-submit');
        $this->add($submit);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // Image Input
        $imageInput = new InputFilter\FileInput('profileImage');
        // $imageInput->setRequired(true);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => './data/uploads/cities',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }

    /**
     * Fetching all venues
     *
     * @return array|$venues
     */
    private function getVenues()
    {
        $venues = array(
            '0' => '...',
        );

        $venuesSet = $this
            ->entityManager
            ->getRepository('Main\Entity\Venue')
            ->findAll();

        foreach ($venuesSet as $venue) {
            $venues[$venue->getId()] = $venue->getName();
        }

        return $venues;
    }
}
