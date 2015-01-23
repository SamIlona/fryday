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
class CreatePartnerFirstStepForm extends Form 
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
    public function __construct($name = null, $entityManager, $user)
    {
        parent::__construct($name);
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form'
            )
        );
        $this->entityManager = $entityManager;
        $this->addElements($user);
        $this->addInputFilter();
    }

    public function addElements($user)
    {
        $name = new Element\Text('name');
        $name->setLabel('Name')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'partner-name');
        $this->add($name);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Save')
            ->setAttribute('class', 'btn-u')
            ->setAttribute('id', 'event-submit');
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
                'name' => 'name',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                    array(
                        'name' => '\DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->entityManager->getRepository('Admin\Entity\Partner'),
                            'fields' => array('name'),
                            'messages' => array(
                                \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'Partner with this name already exist',
                            ),
                        ),
                    ),
                ),
                'name'
            )
        );

        $this->setInputFilter($inputFilter);
    }
}