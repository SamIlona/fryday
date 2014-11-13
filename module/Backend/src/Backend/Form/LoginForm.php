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
 * Login form
 *
 * @category   Fryday_Application
 * @package    Backend
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
        $this->entityManager = $entityManager;
        $this->addElements();
        // $this->addInputFilter();
    }

    public function addElements()
    {
        $login = new Element\Text('login');
        $login->setLabel('Login')
            ->setLabelAttributes(
                array(
                    'class' => 'required control-label col-lg-4',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'login')
            ->setAttribute('placeholder', 'Login');
        $this->add($login);

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
            ->setAttribute('class', 'btn btn-primary btn-sm col-lg-12')
            ->setAttribute('id', 'venue-submit');
        $this->add($submit);
    }
}
