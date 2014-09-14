<?php
/*
За перенаправление всех пользовательских запросов на сайт отвечает файл public/index.php. 
Затем получает массив настроек приложения, расположенный в config/application.config.php. 
После запускает Приложение (Application) вызовом функции run(), 
которое обрабатывает запросы и в итоге отсылает полученный результат обратно пользователю.
*/
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';
// require 'init_fryday_autoloader.php';
// require 'init_csn_autoloader.php';


// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
