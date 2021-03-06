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
 * City form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class CreateSubscriberForm extends Form 
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
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form',
            )
        );
        $this->entityManager = $entityManager;
        $this->addElements();
        // $this->addInputFilter();
    }

    public function addElements()
    {
        $name = new Element\Text('name');
        $name->setLabel('Name')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-subscriber-name');
        $this->add($name);

        $email = new Element\Text('email');
        $email->setLabel('Email')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-subscriber-email');
        $this->add($email);

        // $profileImage = new Element\File('profileImage');
        // $profileImage->setLabel('Profile Image')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttribute('class', 'file col-lg-10')
        //     ->setAttribute('id', 'city-profileImage');
        // $this->add($profileImage);

        $city = new Element\Select('city');
        $city->setLabel('City')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\City')->getAllCitiesAsOptions())
            ->setEmptyOption('Select city...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-subscriber-city');
        $this->add($city);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Create')
            ->setAttribute('class', 'btn btn-primary btn-sm col-lg-12')
            ->setAttribute('id', 'create-subscriber-submit');
        $this->add($submit);
    }

    // public function addInputFilter()
    // {
    //     $inputFilter = new InputFilter\InputFilter();

    //     // Image Input
    //     $imageInput = new InputFilter\FileInput('profileImage');
    //     // $imageInput->setRequired(true);
    //     $imageInput->getFilterChain()->attachByName(
    //         'filerenameupload',
    //         array(
    //             'target'        => './public/uploads/cities/kyiv/profileimages/profileimages.jpg',
    //             'randomize'     => true,
    //             // 'overwrite'     => true,
    //             // 'use_upload_name' => true,
    //         )
    //     );
    //     $inputFilter->add($imageInput);

    //     $this->setInputFilter($inputFilter);
    // }
}
