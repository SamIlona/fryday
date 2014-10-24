<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Main\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function venueAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function partnerAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function franchiseAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function advertiseAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function mediaAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function memberAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function speakerAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    public function aboutAction()
    {
        return new ViewModel(
            array(
            )
        );
    }
    // public function countryAction()
    // {
    //     $country = $this->params()->fromRoute('country');

    //     return new ViewModel(array(
    //         'country' => $country
    //         )
    //     );
    // }
    // public function listCountriesAction()
    // {
    //     return new ViewModel();
    // }

    // public function cityAction()
    // {
    //     $city = $this->params()->fromRoute('city');

    //     return new ViewModel(array(
    //         'city' => $city
    //         )
    //     );
    // }

    // public function listCitiesAction()
    // {
    //     return new ViewModel();
    // }
}
