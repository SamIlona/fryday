<?php
/**
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Form
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Content\Form;

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
 * @package    Content
 * @subpackage Form
 */
class CreateEventForm extends Form 
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
    public function __construct($name = null, $entityManager, $user)
    {
        parent::__construct($name);
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form'
            )
        );
        $this->entityManager = $entityManager;
        $this->addElements($user);
        $this->addInputFilter();
    }

    public function addElements($user)
    {
        $title = new Element\Text('title');
        $title->setLabel('Title')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'event-title');
        $this->add($title);

        $date = new Element\Text('date');
        $date->setLabel('Date')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'event-date');
        $this->add($date);

        $time = new Element\Text('time');
        $time->setLabel('Time')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'event-time');
        $this->add($time);

        $profileImage = new Element\File('profileImage');
        $profileImage->setLabel('Profile Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            // ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'file')
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value');
        $this->add($profileImage);

        $venue = new Element\Select('venue');
        $venue->setLabel('Venue')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Content\Entity\Venue')->getAllVenuesAsOptions($user))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'event-venue');
        $this->add($venue);

        $details = new Element\Textarea('details');
        $details->setLabel('Details')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttributes(
                array(
                    'class'     => 'form-control',
                    'rows'      => '5',
                    'id'        => 'event-details',
                )
            );
        $this->add($details);

        $description = new Element\Textarea('description');
        $description->setLabel('Description')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttributes(
                array(
                    'class'     => 'form-control',
                    'rows'      => '2',
                    'id'        => 'event-description',
                )
            );
        $this->add($description);

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
        $inputFilter = new InputFilter\InputFilter();

        // Image Input
        $imageInput = new InputFilter\FileInput('profileImage');
        // $imageInput->setRequired(true);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => './public/uploads/events/profileimages/profileimages.jpg',
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
    // private function getVenues()
    // {
    //     $venues = array(
    //         '0' => '...',
    //     );

    //     $venuesSet = $this
    //         ->entityManager
    //         ->getRepository('Main\Entity\Venue')
    //         ->findAll();

    //     foreach ($venuesSet as $venue) {
    //         $venues[$venue->getId()] = $venue->getName();
    //     }

    //     return $venues;
    // }
}