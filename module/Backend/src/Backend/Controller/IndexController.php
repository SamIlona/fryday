<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Backend\Form\VenueForm;

use Main\Entity\Venue;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}