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
 * Create User form
 *
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Form
 */
class CreateUserForm extends Form 
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
        $firstName = new Element\Text('firstName');
        $firstName->setLabel('First Name')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttributes(
                array(
                    'class'         => 'form-control',
                    'placeholder'   => 'First Name',
                    'id'            => 'create-user-firstname',
                )
            );
        $this->add($firstName);

        $lastName = new Element\Text('lastName');
        $lastName->setLabel('Last Name')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttributes(
                array(
                    'class'         => 'form-control',
                    'placeholder'   => 'Last Name',
                    'id'            => 'create-user-lastname',
                )
            );
        $this->add($lastName);

        $login = new Element\Text('login');
        $login->setLabel('Login')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttributes(
                array(
                    'class'         => 'form-control',
                    'placeholder'   => 'Login',
                    'id'            => 'create-user-login',
                )
            );
        $this->add($login);

        $password = new Element\Password('password');
        $password->setLabel('Password')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttributes(
                array(
                    'class'         => 'form-control',
                    'placeholder'   => 'Password',
                    'id'            => 'create-user-password',
                )
            );
        $this->add($password);

        $profileImage = new Element\File('profileImage');
        $profileImage->setLabel('Profile Image')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttributes(
                array(
                    'class'         => 'file col-lg-10',
                    'id'            => 'create-user-profileImage',
                )
            );
        $this->add($profileImage);

        $role = new Element\Select('role');
        $role->setLabel('Role')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Backend\Entity\Role')->getAllRolesAsOptions())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-user-role');
        $this->add($role);

        $city = new Element\Select('city');
        $city->setLabel('City')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Main\Entity\City')->getAllCitiesAsOptions())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-user-city');
        $this->add($city);

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
                'target'        => './data/uploads/users/profileimages',
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
