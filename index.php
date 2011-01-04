<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define module path
defined('MODULE_PATH')
    || define('MODULE_PATH', APPLICATION_PATH . '/modules');
    
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Definování cesty k iCms library
defined('ICMS_PATH') 
	|| define('ICMS_PATH',APPLICATION_PATH . '/../library/iCms');

function getTime()
{
	return gettimeofday();
}
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
	
require_once 'iCms/Manager.php';
use iCms\Manager;

//pro manipulaci s bootstrap instancí v index.php zavolat nejprve bootstrapApp 
//a poté manipulovat, až pak run
Manager::getInstance()->run();

