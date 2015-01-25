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
 * Event form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class EditEventForm extends Form 
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
    public function __construct($name, $entityManager, $user, $dir)
    {
        parent::__construct($name);
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Admin\Entity\Event'));
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form'
            )
        );
        $this->_dir = $dir;
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

        $image = new Element\File('image');
        $image->setLabel('Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            // ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'image')
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value');
        $this->add($image);

        $venue = new Element\Select('venue');
        $venue->setLabel('Venue')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Venue')->getAllVenuesAsOptions($user))
            ->setEmptyOption('Select venue...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue');
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
                    'rows'      => '7',
                    'id'        => 'details',
                )
            );
        $this->add($details);

        $entrancefee = new Element\Text('entrancefee');
        $entrancefee->setLabel('Entrancee Fee (Optional)')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-entrancefee');
        $this->add($entrancefee);

        $currency = new Element\Select('currency');
        $currency->setLabel('Currency')
            ->setLabelAttributes(array('class' => 'label'))
            ->setValueOptions($this->entityManager->getRepository('Fryday\Entity\Iso4217')->getAllCurrenciesAsOptions())
            ->setEmptyOption('Select currency...')
            ->setOptions(array('disable_inarray_validator' => true));
            // ->setAttribute('class', 'form-control');
        $this->add($currency);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Save')
            ->setAttribute('class', 'btn-u')
            ->setAttribute('id', 'submit');
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

        $inputFilter->add(
            array(
                'name' => 'title',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                ),
            ),
            'title'
        );

        $inputFilter->add(
            array(
                'name' => 'date',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                ),
            ),
            'date'
        );

        $inputFilter->add(
            array(
                'name' => 'time',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                ),
            ),
            'time'
        );

        // Image Input
        $imageInput = new InputFilter\FileInput('image');
        $imageInput->setRequired(false);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        =>  $this->_dir . DIRECTORY_SEPARATOR . 'original_image',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}