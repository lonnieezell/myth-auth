<?php

//require 'vendor/autoload.php';
//require 'tests/_support/Common.php';
//
//define('BASEPATH', realpath('vendor/codeigniter4/framework/app/').'/');
//
//if (! defined('WEEK'))
//{
//    /*
//    |--------------------------------------------------------------------------
//    | Timing Constants
//    |--------------------------------------------------------------------------
//    |
//    | Provide simple ways to work with the myriad of PHP functions that
//    | require information to be in seconds.
//    */
//        defined('SECOND')   || define('SECOND',                 1);
//        defined('MINUTE')   || define('MINUTE',                60);
//        defined('HOUR')     || define('HOUR',                3600);
//        defined('DAY')      || define('DAY',                86400);
//        defined('WEEK')     || define('WEEK',              604800);
//        defined('MONTH')    || define('MONTH',            2592000);
//        defined('YEAR')     || define('YEAR',            31536000);
//        defined('DECADE')   || define('DECADE',         315360000);
//}

ini_set('error_reporting', E_ALL);
;
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Make sure it recognizes that we're testing.
$_SERVER['CI_ENVIRONMENT'] = 'testing';
define('ENVIRONMENT', 'testing');

// Load our paths config file
require __DIR__ . '/../support/Config/Paths.php';

define('APPPATH', realpath(__DIR__ .'/../vendor/codeigniter4/framework/app'). DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath(__DIR__ .'/../vendor/codeigniter4/framework/system'). DIRECTORY_SEPARATOR);
define('FCPATH', realpath(__DIR__ . '/../vendor/codeigniter4/framework/public'). DIRECTORY_SEPARATOR);

define('TESTPATH', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);

define('SUPPORTPATH', realpath(TESTPATH . '_support/') . DIRECTORY_SEPARATOR);

// Set environment values that would otherwise stop the framework from functioning during tests.
if (! isset($_SERVER['app.baseURL']))
{
    $_SERVER['app.baseURL'] = 'http://example.com';
}

// Ensure the autoloader uses our autoload config to get our namespaces found
require __DIR__ .'/../support/Config/Autoload.php';
require __DIR__ .'/../support/Config/Constants.php';

\CodeIgniter\Config\Config::injectMock('Autoload', new Config\Autoload());
