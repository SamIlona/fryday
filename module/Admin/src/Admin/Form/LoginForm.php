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

use Main\Entity\VenueCategory;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Login form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class LoginForm extends Form 
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
        $this->setAttribute('class', 'reg-page');
        $this->entityManager = $entityManager;
        $this->addElements();
        // $this->addInputFilter();
    }

    public function addElements()
    {
        $email = new Element\Text('email');
        $email->setLabel('E-mail')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'email')
            ->setAttribute('placeholder', 'E-mail');
        $this->add($email);

        $password = new Element\Password('password');
        $password->setLabel('Password')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            // ->setValueOptions($this->getVenues())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'password')
            ->setAttribute('placeholder', 'Password');
        $this->add($password);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Create')
            ->setAttribute('class', 'btn-u pull-right')
            ->setAttribute('id', 'venue-submit');
        $this->add($submit);
    }
}
