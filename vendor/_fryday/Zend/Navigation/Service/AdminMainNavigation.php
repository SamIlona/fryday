<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Fryday\Zend\Navigation\Service;

// use Fryday\Zend\Navigation\Service\;

/**
 * Admin Main Navigation factory.
 */
class AdminMainNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_main';
    }
}
