<?php
/**
 * @link      https://github.com/VadimIzmalkov/fryday
 * @copyright Copyright (c) Vadim Izmalkov 399115@gmail.com
 */

namespace Admin\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Admin Main Navigation factory.
 */
class AdminMainNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_main';
    }
}
