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
 * City form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class CreateNewsletterForm extends Form 
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
        $this->setAttributes(
            array(
                'method'    => 'post',
                'class'     => 'sky-form',
            )
        );
        $this->entityManager = $entityManager;
        $this->addElements();
        // $this->addInputFilter();
    }

    public function addElements()
    {
        $partner1 = new Element\Select('partner1');
        $partner1->setLabel('Partner 1')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Partner')->getAllPartnersAsOptions())
            ->setEmptyOption('Select partner...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-newsletter-city');
        $this->add($partner1);

        $partner2 = new Element\Select('partner2');
        $partner2->setLabel('Partner 2')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Partner')->getAllPartnersAsOptions())
            ->setEmptyOption('Select partner...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-newsletter-city');
        $this->add($partner2);

        $partner3 = new Element\Select('partner3');
        $partner3->setLabel('Partner 3')
            ->setLabelAttributes(
                array(
                    'class' => 'label',
                )
            )
            ->setValueOptions($this->entityManager->getRepository('Admin\Entity\Partner')->getAllPartnersAsOptions())
            ->setEmptyOption('Select partner...')
            ->setAttribute('class', 'form-control')
            ->setAttribute('id', 'create-newsletter-city');
        $this->add($partner3);


        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Create')
            ->setAttribute('class', 'btn btn-primary btn-sm col-lg-12')
            ->setAttribute('id', 'create-subscriber-submit');
        $this->add($submit);
    }

    // public function addInputFilter()
    // {
    //     $inputFilter = new InputFilter\InputFilter();

    //     // Image Input
    //     $imageInput = new InputFilter\FileInput('profileImage');
    //     // $imageInput->setRequired(true);
    //     $imageInput->getFilterChain()->attachByName(
    //         'filerenameupload',
    //         array(
    //             'target'        => './public/uploads/cities/kyiv/profileimages/profileimages.jpg',
    //             'randomize'     => true,
    //             // 'overwrite'     => true,
    //             // 'use_upload_name' => true,
    //         )
    //     );
    //     $inputFilter->add($imageInput);

    //     $this->setInputFilter($inputFilter);
    // }
}
