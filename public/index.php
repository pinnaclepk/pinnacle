<?php

// Define path to application directory

defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__)));

defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__)));

defined('INNER_PATH')
    || define('INNER_PATH', '');

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('MODULES_PATH')
    || define('MODULES_PATH', APPLICATION_PATH . '/../modules');
   
    
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

	
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/forms'),
    realpath(APPLICATION_PATH . '/views/helpers'),
    realpath(APPLICATION_PATH . '/modules'),
    realpath(APPLICATION_PATH . '/../plugins'),
     realpath(ROOT_PATH."/../"),
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace(array("Plugins"));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
	    
?>