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
class CreateCitySecondStepForm extends Form 
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
    public function __construct($name = null, $entityManager, $user, $dir)
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
        $x->setAttribute('id', 'city-x');
        $this->add($x);

        $y = new Element\Hidden('y');
        $y->setAttribute('id', 'city-y');
        $this->add($y);

        $w = new Element\Hidden('w');
        $w->setAttribute('id', 'city-w');
        $this->add($w);

        $h = new Element\Hidden('h');
        $h->setAttribute('id', 'city-h');
        $this->add($h);

        $cw = new Element\Hidden('cw');
        $cw->setAttribute('id', 'city-cw');
        $this->add($cw);

        $ch = new Element\Hidden('ch');
        $ch->setAttribute('id', 'city-ch');
        $this->add($ch);

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

        $image = new Element\File('image');
        $image->setLabel('Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'city-image');
        $this->add($image);

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
                'target'        => $this->_dir . DIRECTORY_SEPARATOR . 'city_image',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}
