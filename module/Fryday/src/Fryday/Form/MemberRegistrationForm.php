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
class MemberRegistrationForm extends Form 
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

        $email = new Element\Text('email');
        $email->setLabel('E-mail')
            ->setLabelAttributes(array('class' => 'label'))
            ->setAttributes(array(
                'placeholder' => 'E-mail',
                'id' => 'member-registration-email',
        ));
        $this->add($email);

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

        $inputFilter->add(
            array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                    array('name' => 'email_address'),
                    array(
                        'name' => '\DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->entityManager->getRepository('Admin\Entity\User'),
                            'fields' => array('email'),
                            'messages' => array(
                                \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'Email Address Already in Use',
                            ),
                        ),
                    ),
                ),
            ),
            'email'
        );

        $this->setInputFilter($inputFilter);
    }
}
