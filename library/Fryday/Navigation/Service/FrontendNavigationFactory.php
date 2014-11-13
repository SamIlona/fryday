<?php
/**
 * @category   Fryday
 * @package    Library
 * @subpackage Navigation\Service
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Fryday\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Frontend navigation factory.
 */
class FrontendNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'frontend';
    }
}
