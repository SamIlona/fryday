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
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $xStartCrop = new Element\Hidden('xStartCrop');
        $xStartCrop->setAttribute('id', 'x-start-crop');
        $this->add($xStartCrop);

        $yStartCrop = new Element\Hidden('yStartCrop');
        $yStartCrop->setAttribute('id', 'y-start-crop');
        $this->add($yStartCrop);

        $widthCrop = new Element\Hidden('widthCrop');
        $widthCrop->setAttribute('id', 'width-crop');
        $this->add($widthCrop);

        $heightCrop = new Element\Hidden('heightCrop');
        $heightCrop->setAttribute('id', 'height-crop');
        $this->add($heightCrop);

        $widthCurrent = new Element\Hidden('widthCurrent');
        $widthCurrent->setAttribute('id', 'width-current');
        $this->add($widthCurrent);

        $heightCurrent = new Element\Hidden('heightCurrent');
        $heightCurrent->setAttribute('id', 'height-current');
        $this->add($heightCurrent);

        $image = new Element\File('image');
        $image->setLabel('Image')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttribute('id', 'event-image')
            ->setAttribute('onchange', 'this.parentNode.nextSibling.value = this.value');
        $this->add($image);

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

        $currency = new Element\Select('currency');
        $currency->setLabel('Currency')
            ->setLabelAttributes(array('class' => 'label'))
            ->setValueOptions($this->entityManager->getRepository('Fryday\Entity\Iso4217')->getAllCurrenciesAsOptions())
            ->setEmptyOption('Select currency...')
            ->setOptions(array('disable_inarray_validator' => true));
            // ->setAttribute('class', 'form-control');
        $this->add($currency);

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
                // 'target'        =>  'public' . DIRECTORY_SEPARATOR . 'original_image',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($imageInput);

        $this->setInputFilter($inputFilter);
    }
}