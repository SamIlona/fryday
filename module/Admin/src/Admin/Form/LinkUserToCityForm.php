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
use Zend\InputFilter\Input;
use Zend\Validator;

use Admin\Entity\VenueCategory;
use Admin\Validator as UserLinkedValidator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use Zend\InputFilter\InputFilterProviderInterface;

/**
 * City form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class LinkUserToCityForm extends Form implements InputFilterProviderInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var _dir
     */
    protected $cityID;

	/**
     * Constructor
     *
     * @param null|string|int $name Optional name for the element
     */
    public function __construct($name, $entityManager, $user, $city)
    {
        parent::__construct($name);
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form',
            )
        );
        $this->cityID = $city;
        $this->entityManager = $entityManager;
        $this->addElements();
        // $this->addInputFilter();
    }

    public function addElements()
    {
        $user = new Element\Select('user');
        $user->setLabel('User')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\User')->getAllUsersAsOptions())
            ->setEmptyOption('Select user...')
            ->setOptions(
                array(
                    'disable_inarray_validator' => true,
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'city-user');
        $this->add($user);
    }

    public function getInputFilterSpecification()
    {
        return array(
            'user' => array(
                'required'    => true,
                'filters'     => array(),
                'validators'  => array(
                        array(
                            'name'    => 'Admin\Validator\UserLinked',
                            'options' => array(
                                'em' => $this->entityManager,
                                'cityID' => $this->cityID,
                            ),
                        ),
                ),
            ),
        );
    }

    // public function addInputFilter()
    // {
    //     $inputFilter = new InputFilter\InputFilter();

    //     // $user = new Input('user');
    //     // $user->getValidatorChain()
    //     //      ->attach(new Validator\NotEmpty())
    //     //      ->attach(new UserLinkedValidator\UserLinked($this->cityID));

    //     $inputFilter->add($user);
    //     $inputFilter->add(
    //         array(
    //             'name' => 'user',
    //             'required' => true,
    //             'validators' => array(
    //                 array(
    //                     'name' => 'not_empty', 
    //                     'options' => array(
    //                         'messages' => array(
    //                             \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select user!' 
    //                         ),
    //                     ),
    //                 ),
    //                 array(
    //                     'name' => 'Admin\Validator\UserLinked',
    //                     'options' => array(
                            
    //                     ),
    //                 ),
    //             ),
    //         ),
    //         'user'
    //     );

    //     $this->setInputFilter($inputFilter);
    // }
}
