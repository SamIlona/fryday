<?php

namespace MyModule;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
                // Describe available commands
                'get happen [--verbose|-v] <doname>'    => 'Get Process already happen',
     
                // Describe expected parameters
                array( 'doname',            'Process Name' ),
                array( '--verbose|-v',     '(optional) turn on verbose mode'        ),
     
        );
    }
}