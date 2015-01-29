<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Fryday\Form;

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
class ContinueRegistrationForm extends Form 
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
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Admin\Entity\User'));
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form',
            )
        );
        $this->entityManager = $entityManager;
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $firstName = new Element\Text('firstName');
        $firstName->setLabel('First Name')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('placeholder' => 'First Name'));
        $this->add($firstName);

        $lastName = new Element\Text('lastName');
        $lastName->setLabel('Last Name')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('placeholder' => 'Last Name'));
        $this->add($lastName);

        $password = new Element\Text('password');
        $password->setLabel('Password')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('placeholder' => 'Password'));
        $this->add($password);

        $phone = new Element\Text('phone');
        $phone->setLabel('Phone')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array('placeholder' => 'Phone'));
        $this->add($phone);

        $city = new Element\Text('city');
        $city->setLabel('City')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'placeholder' => 'City',
                'id' => 'member-registration-city',
        ));
        $this->add($city);

        $country = new Element\Select('country');
        $country->setLabel('Country')
            ->setLabelAttributes(array('class' => 'label'))
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Country')->getAllCountriesAsOptions())
            ->setEmptyOption('Select country...')
            ->setOptions(array('disable_inarray_validator' => true))
            ->setAttribute('id', 'member-registration-country');
        $this->add($country);

        $facebook = new Element\Text('facebook');
        $facebook->setLabel('Facebook')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'placeholder' => 'Facebook',
                'class' => 'form-control'
            ));
        $this->add($facebook);

        $linkedin = new Element\Text('linkedin');
        $linkedin->setLabel('LinkedIn')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'placeholder' => 'LinkedIn',
                'class' => 'form-control'
            ));
        $this->add($linkedin);

        $skype = new Element\Text('skype');
        $skype->setLabel('Skype')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'placeholder' => 'Skype',
                'class' => 'form-control'
            ));
        $this->add($skype);

        $twitter = new Element\Text('twitter');
        $twitter->setLabel('Twitter')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'placeholder' => 'Twitter',
                'class' => 'form-control'
            ));
        $this->add($twitter);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Finish')
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

        $this->setInputFilter($inputFilter);
    }
}
