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
 * Event form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class CreateEventSecondStepForm extends Form 
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
    public function __construct($name, $entityManager, $user, $dir)
    {
        parent::__construct($name);
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form'
            )
        );
        $this->_dir = $dir;
        $this->entityManager = $entityManager;
        $this->addElements($user);
        $this->addInputFilter();
    }

    public function addElements($user)
    {
        $x = new Element\Hidden('x');
        $x->setAttribute('id', 'event-x');
        $this->add($x);

        $y = new Element\Hidden('y');
        $y->setAttribute('id', 'event-y');
        $this->add($y);

        $w = new Element\Hidden('w');
        $w->setAttribute('id', 'event-w');
        $this->add($w);

        $h = new Element\Hidden('h');
        $h->setAttribute('id', 'event-h');
        $this->add($h);

        $cw = new Element\Hidden('cw');
        $cw->setAttribute('id', 'event-cw');
        $this->add($cw);

        $ch = new Element\Hidden('ch');
        $ch->setAttribute('id', 'event-ch');
        $this->add($ch);

        $image = new Element\File('image');
        $image->setLabel('Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            // ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'event-image')
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value');
        $this->add($image);

        $venue = new Element\Select('venue');
        $venue->setLabel('Venue')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Venue')->getAllVenuesAsOptions($user))
            ->setEmptyOption('Select venue...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'event-venue');
        $this->add($venue);

        $details = new Element\Textarea('details');
        $details->setLabel('Details')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttributes(
                array(
                    'class'     => 'form-control',
                    'rows'      => '7',
                    'id'        => 'event-details',
                )
            );
        $this->add($details);

        $entrancefee = new Element\Text('entrancefee');
        $entrancefee->setLabel('Entrancee Fee (Optional)')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'event-entrancefee');
        $this->add($entrancefee);

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

        // Image Input
        $imageInput = new InputFilter\FileInput('image');
        $imageInput->setRequired(false);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        =>  $this->_dir . DIRECTORY_SEPARATOR . 'original_image',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        

        // $inputFilter->add( 
        //     array(
        //         'name' => 'newsletter',
        //         'required' => false,
        //     ),
        //     array(
        //         'name' => 'newsletter',
        //         'required' => false,
        //     ),
        //     array(
        //         'name' => 'newsletter',
        //         'required' => false,
        //     ),
        // );

        $this->setInputFilter($inputFilter);
    }
}