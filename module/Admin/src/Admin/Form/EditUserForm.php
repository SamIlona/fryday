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

// use Admin\Entity\VenueCategory;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Create User form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class EditUserForm extends Form 
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
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Admin\Entity\User'));
        $this->setAttributes(array(
            'method'    => 'post',
            'class'     => 'sky-form',
        ));
        $this->_dir = $dir;
        $this->entityManager = $entityManager;
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $firstName = new Element\Text('firstName');
        $firstName->setLabel('First Name')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($firstName);

        $lastName = new Element\Text('lastName');
        $lastName->setLabel('Last Name')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($lastName);

        $facebook = new Element\Text('facebook');
        $facebook->setLabel('Facebook')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($facebook);

        $linkedin = new Element\Text('linkedin');
        $linkedin->setLabel('LinkedIn')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($linkedin);

        $skype = new Element\Text('skype');
        $skype->setLabel('Skype')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($skype);

        $phone = new Element\Text('phone');
        $phone->setLabel('Phone')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($phone);

        $twitter = new Element\Text('twitter');
        $twitter->setLabel('Twitter')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('class' => 'form-control'));
        $this->add($twitter);

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
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttribute('id', 'image')
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value');
        $this->add($image);

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

        $inputFilter->add(
            array(
                'name' => 'firstName',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                ),
            ),
            'firstName'
        );

        $inputFilter->add(
            array(
                'name' => 'lastName',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                ),
            ),
            'lastName'
        );

        // Image Input
        $imageInput = new InputFilter\FileInput('image');
        $imageInput->setRequired(false);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => $this->_dir . DIRECTORY_SEPARATOR . 'img_',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}
