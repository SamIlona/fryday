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

use Admin\Entity\VenueCategory;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * City form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class CreateCityFirstStepForm extends Form 
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
        $name = new Element\Text('name');
        $name->setLabel('Name')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'city-name');
        $this->add($name);

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

        $country = new Element\Select('country');
        $country->setLabel('Country')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Country')->getAllCountriesAsOptions())
            ->setEmptyOption('Select country...')
            ->setOptions(
                array(
                    'disable_inarray_validator' => true,
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'city-country');
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
                'name' => 'country',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'not_empty', 
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select country!' 
                            ),
                        ),
                    ),
                ),
            ),
            'country'
        );

        $inputFilter->add(
            array(
                'name' => 'name',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'not_empty', 
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter city name!' 
                            ),
                        ),
                    ),
                    array(
                        'name' => '\DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->entityManager->getRepository('Admin\Entity\City'),
                            'fields' => array('name'),
                            'messages' => array(
                                \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'City with this name already exist',
                            ),
                        ),
                    ),
                ),
            ),
            'name'
        );
        // // Image Input
        // $imageInput = new InputFilter\FileInput('profileImage');
        // // $imageInput->setRequired(true);
        // $imageInput->getFilterChain()->attachByName(
        //     'filerenameupload',
        //     array(
        //         'target'        => './public/uploads/cities/kyiv/profileimages/profileimages.jpg',
        //         'randomize'     => true,
        //         // 'overwrite'     => true,
        //         // 'use_upload_name' => true,
        //     )
        // );
        // $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}
