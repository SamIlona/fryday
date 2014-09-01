<?php
// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

$frydayPath = false;

if (is_dir('vendor')) { // vendor/ZF2/library
    $frydayPath = 'vendor'; // notice how we are in the folder where the library should be
} elseif (getenv('FRYDAY_PATH')) {      // Support for ZF2_PATH environment variable or git submodule
    $frydayPath = getenv('FRYDAY_PATH');
} elseif (get_cfg_var('fryday_path')) { // Support for zf2_path directive value
    $frydayPath = get_cfg_var('fryday_path');
}

if ($frydayPath) {
    if (isset($loader)) {
        $loader->add('Fryday', $frydayPath);
    } else {
	// ToDo fix it so it works with Csn
        include $frydayPath . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true
            )
        ));
    }
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load CSN. Run `php composer.phar install` or define a CSN_PATH environment variable.');
}
