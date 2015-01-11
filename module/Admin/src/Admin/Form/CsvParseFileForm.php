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
class CsvParseFileForm extends Form 
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
    public function __construct($name, $entityManager, $dir)
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
        $csvFile = new Element\File('csvFile');
        $csvFile->setLabel('File *.csv (size < 3 MB)')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setAttributes(
                array(
                    // 'class'         => 'file col-lg-10',
                    'id'            => 'csv-file',
                    'onchange'      => 'this.parentNode.nextSibling.value = this.value',
                )
            );
        $this->add($csvFile);

        $city = new Element\Select('city');
        $city->setLabel('City')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\City')->getAllCitiesAsOptions())
            ->setEmptyOption('Select city...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'csv-parse-city');
        $this->add($city);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Save')
            ->setAttribute('class', 'btn-u')
            ->setAttribute('id', 'csv-parse-submit');
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

        $csvFile = new InputFilter\FileInput('csvFile');
        // $csvFile->setRequired(false);
        $csvFile->getValidatorChain()
            ->attachByName('filesize', array('max' => 40000000))
            ->attach(new \Zend\Validator\File\Extension('csv'));
        $csvFile->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'        =>  $this->_dir . DIRECTORY_SEPARATOR . 'database_',
                'randomize'     => true,
                // 'overwrite'     => true,
                // 'use_upload_name' => true,
            )
        );
        $inputFilter->add($csvFile);

        

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