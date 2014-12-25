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
 * Event form
 *
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Form
 */
class PreviewEventForm extends Form 
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
    }

    public function addElements($user)
    {
        // $publish = new Element\Checkbox('publish');
        // $publish->setLabel('Publish')
        //     // ->setLabelAttributes(
        //     //     array(
        //     //         'class' => 'label',
        //     //     )
        //     // )
        //     // ->setAttribute('class', 'form-control')
        //     ->setUseHiddenElement(false);
        //     // ->setAttribute('id', 'preview-event-publish');
        // $this->add($publish);

        // $newsletter = new Element\Checkbox('newsletter');
        // $newsletter->setLabel('Publish')
        //     ->setLabelAttributes(
        //         array(
        //             'class' => 'label',
        //         )
        //     )
        //     ->setAttribute('class', 'form-control')
        //     ->setAttribute('id', 'preview-event-newsletter');
        // $this->add($newsletter);

        $submit = new Element\Submit('submit');
        $submit
            ->setValue('Save')
            ->setAttribute('class', 'btn-u')
            ->setAttribute('id', 'event-submit');
        $this->add($submit);
    }
}