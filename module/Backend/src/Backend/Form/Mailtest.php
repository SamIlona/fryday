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
 * Event form
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Form
 */
class Mailtest extends Form 
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
        // $this->addInputFilter();
    }

    public function addElements()
    {
        // $title = new Element\Text('title');
        // $title->setLabel('Title')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'required control-label col-lg-4',
        //         )
        //     )
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'event-title');
        // $this->add($title);

        // $profileImage = new Element\File('profileImage');
        // $profileImage->setLabel('Profile Image')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'required control-label col-lg-4',
        //         )
        //     )
        //     ->setAttribute('class', 'file col-lg-10')
        //     ->setAttribute('id', 'event-profileImage');
        // $this->add($profileImage);

        // $venue = new Element\Select('venue');
        // $venue->setLabel('Venue')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'required control-label col-lg-4',
        //         )
        //     )
        //     ->setValueOptions($this->getVenues())
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'event-venue');
        // $this->add($venue);

        // $text = new Element\Textarea('text');
        // $text->setLabel('Details')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'required control-label col-lg-4',
        //         )
        //     )
        //     ->setAttributes(
        //         array(
        //             'class'     => 'form-control',
        //             'rows'      => '5',
        //             'id'        => 'event-text',
        //         )
        //     );
        // $this->add($text);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Create')
            ->setAttribute('class', 'btn btn-primary btn-sm col-lg-12')
            ->setAttribute('id', 'venue-submit');
        $this->add($submit);
    }

    /**
     * Event InputFilter
     *
     * @return null
     */
    public function addInputFilter()
    {
        // $inputFilter = new InputFilter\InputFilter();

        // // Image Input
        // $imageInput = new InputFilter\FileInput('profileImage');
        // // $imageInput->setRequired(true);
        // $imageInput->getFilterChain()->attachByName(
        //     'filerenameupload',
        //     array(
        //         'target'        => './data/uploads/events/',
        //         'randomize'     => true,
        //         // 'overwrite'     => true,
        //         // 'use_upload_name' => true,
        //     )
        // );
        // $inputFilter->add($imageInput);

        // $this->setInputFilter($inputFilter);
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