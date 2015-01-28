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
class SignUpForm extends Form 
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
        $this->setAttributes(array(
                'method'    => 'post',
                'class'     => 'sky-form sky-form-without-border'
        ));
        $this->entityManager = $entityManager;
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $email = new Element\Text('email');
        $email->setLabel('E-mail')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'signup-email')
            ->setAttribute('placeholder', 'E-mail');
        $this->add($email);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Save')
            ->setAttribute('class', 'btn-u')
            ->setAttribute('id', 'event-submit');
        $this->add($submit);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        $inputFilter->add(
            array(
                'name' => 'signup-email',
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