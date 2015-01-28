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
class CreateUserSecondStepForm extends Form 
{
    /**
     * @var _dir
     */
    protected $_dir;

    /**
     * @var EntityManager
     */
    protected $entityManager;
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

    public function addElements()
    {
        $x = new Element\Hidden('x');
        $x->setAttribute('id', 'user-x');
        $this->add($x);

        $y = new Element\Hidden('y');
        $y->setAttribute('id', 'user-y');
        $this->add($y);

        $w = new Element\Hidden('w');
        $w->setAttribute('id', 'user-w');
        $this->add($w);

        $h = new Element\Hidden('h');
        $h->setAttribute('id', 'user-h');
        $this->add($h);

        $cw = new Element\Hidden('cw');
        $cw->setAttribute('id', 'user-cw');
        $this->add($cw);

        $ch = new Element\Hidden('ch');
        $ch->setAttribute('id', 'user-ch');
        $this->add($ch);

        $image = new Element\File('image');
        $image->setLabel('Profile Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttributes(
                array(
                    // 'class'         => 'file col-lg-10',
                    'id'            => 'create-user-image',
                    'onchange'      => 'this.parentNode.nextSibling.value = this.value',
                )
            );
        $this->add($image);

        $password = new Element\Password('password');
        $password->setLabel('Password')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttributes(
                array(
                    'class'         => 'form-control',
                    // 'placeholder'   => 'Password',
                    'id'            => 'create-user-password',
                )
            );
        $this->add($password);

        $role = new Element\Select('role');
        $role->setLabel('Role')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Role')->getAllRolesAsOptions())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-user-role');
        $this->add($role);

        // $city = new Element\Select('city');
        // $city->setLabel('City')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setValueOptions($this->entityManager->getRepository('Admin\Entity\City')->getAllCitiesAsOptions())
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'create-user-city');
        // $this->add($city);

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
        $imageInput = new InputFilter\FileInput('image');
        $imageInput->setRequired(false);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => $this->_dir . DIRECTORY_SEPARATOR . 'user_image',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}
