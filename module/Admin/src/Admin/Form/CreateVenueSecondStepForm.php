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

use Main\Entity\VenueCategory;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Venue form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class CreateVenueSecondStepForm extends Form 
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
        $x = new Element\Hidden('x');
        $x->setAttribute('id', 'venue-x');
        $this->add($x);

        $y = new Element\Hidden('y');
        $y->setAttribute('id', 'venue-y');
        $this->add($y);

        $w = new Element\Hidden('w');
        $w->setAttribute('id', 'venue-w');
        $this->add($w);

        $h = new Element\Hidden('h');
        $h->setAttribute('id', 'venue-h');
        $this->add($h);

        $cw = new Element\Hidden('cw');
        $cw->setAttribute('id', 'venue-cw');
        $this->add($cw);

        $ch = new Element\Hidden('ch');
        $ch->setAttribute('id', 'venue-ch');
        $this->add($ch);

        // $category = new Element\Select('category');
        // $category->setLabel('Select Category')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setValueOptions($this->getCategories())
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'venue-category');
        // $this->add($category);

        $phone = new Element\Text('phone');
        $phone->setLabel('Phone')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-phone');
        $this->add($phone);

        $website = new Element\Text('website');
        $website->setLabel('Website')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-website');
        $this->add($website);

        $email = new Element\Text('email');
        $email->setLabel('Email')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-email');
        $this->add($email); 

        $address = new Element\Text('address');
        $address->setLabel('Address')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'venue-address');
        $this->add($address);

        $image = new Element\File('image');
        $image->setLabel('Profile Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'file col-lg-10')
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
        // $imageInput->setRequired(true);
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