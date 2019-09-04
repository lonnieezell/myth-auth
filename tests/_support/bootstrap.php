<?php

ini_set('error_reporting', E_ALL);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Make sure it recognizes that we're testing.
$_SERVER['CI_ENVIRONMENT'] = 'testing';
define('ENVIRONMENT', 'testing');

// Load our paths config file
require __DIR__ . '/../../vendor/codeigniter4/codeigniter4/app/Config/Paths.php';
$paths = new Config\Paths();

// Define necessary framework path constants
define('APPPATH',       realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
define('ROOTPATH',      realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
define('FCPATH',        realpath(ROOTPATH . 'public') . DIRECTORY_SEPARATOR);
define('SYSTEMPATH',    realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
define('WRITEPATH',     realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);

// Define necessary module test path constants
define('SUPPORTPATH',   realpath(__DIR__) . DIRECTORY_SEPARATOR);
define('TESTPATH',      realpath(SUPPORTPATH . '../') . DIRECTORY_SEPARATOR);
define('MODULEPATH',    realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR);
define('COMPOSER_PATH', MODULEPATH . 'vendor/autoload.php');

// Set environment values that would otherwise stop the framework from functioning during tests.
if (! isset($_SERVER['app.baseURL']))
{
	$_SERVER['app.baseURL'] = 'http://example.com';
}

// Load necessary modules
require_once APPPATH . 'Config/Autoload.php';
require_once APPPATH . 'Config/Constants.php';
require_once APPPATH . 'Config/Modules.php';

require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
require_once SYSTEMPATH . 'Config/BaseService.php';
require_once APPPATH . 'Config/Services.php';

// Use Config\Services as CodeIgniter\Services
if (! class_exists('CodeIgniter\Services', false))
{
	class_alias('Config\Services', 'CodeIgniter\Services');
}

// Launch the autoloader to gather namespaces (includes composer.json's "autoload-dev")
$loader = \CodeIgniter\Services::autoloader();
$loader->initialize(new Config\Autoload(), new Config\Modules());
$loader->register();    // Register the loader with the SPL autoloader stack.
