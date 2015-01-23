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
class CreatePartnerSecondStepForm extends Form 
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
        // $name = new Element\Text('name');
        // $name->setLabel('Title')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'partner-name');
        // $this->add($name);

        $x = new Element\Hidden('x');
        $x->setAttribute('id', 'partner-x');
        $this->add($x);

        $y = new Element\Hidden('y');
        $y->setAttribute('id', 'partner-y');
        $this->add($y);

        $w = new Element\Hidden('w');
        $w->setAttribute('id', 'partner-w');
        $this->add($w);

        $h = new Element\Hidden('h');
        $h->setAttribute('id', 'partner-h');
        $this->add($h);

        $cw = new Element\Hidden('cw');
        $cw->setAttribute('id', 'partner-cw');
        $this->add($cw);

        $ch = new Element\Hidden('ch');
        $ch->setAttribute('id', 'partner-ch');
        $this->add($ch);

        // $time = new Element\Text('time');
        // $time->setLabel('Time')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'event-time');
        // $this->add($time);

        $logo = new Element\File('logo');
        $logo->setLabel('Logo')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            // ->setAttribute('class', 'file col-lg-10')
            ->setAttribute('id', 'partner-logo')
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value');
        $this->add($logo);

        $city = new Element\Select('city');
        $city->setLabel('Select City')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\City')->getAllCitiesAsOptions())
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'partner-city');
        $this->add($city);

        // $details = new Element\Textarea('details');
        // $details->setLabel('Details')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttributes(
        //         array(
        //             'class'     => 'form-control',
        //             'rows'      => '5',
        //             'id'        => 'event-details',
        //         )
        //     );
        // $this->add($details);

        // $description = new Element\Textarea('description');
        // $description->setLabel('Description')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttributes(
        //         array(
        //             'class'     => 'form-control',
        //             'rows'      => '2',
        //             'id'        => 'event-description',
        //         )
        //     );
        // $this->add($description);

        // $entrancefee = new Element\Text('entrancefee');
        // $entrancefee->setLabel('Entry Fee')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'event-entrancefee');
        // $this->add($entrancefee);

        // $newsletter = new Element\Checkbox('newsletter');
        // $newsletter->setLabel('Newsletter')
        //     // ->setLabelAttributes(
        //     //     array(
        //     //         'class' => 'label',
        //     //     )
        //     // )
        //     // ->setAttribute('class', 'form-control')
        //     ->setUseHiddenElement(false)
        //     ->setCheckedValue(1)
        //     ->setUncheckedValue(0)
        //     ->setAttribute('id', 'preview-event-newsletter');
        // $this->add($newsletter);

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
        $imageInput = new InputFilter\FileInput('logo');
        $imageInput->setRequired(false);
        $imageInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        => $this->_dir . DIRECTORY_SEPARATOR . 'partner_image',
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