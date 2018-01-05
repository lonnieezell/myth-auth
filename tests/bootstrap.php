<?php

require 'vendor/autoload.php';
require 'tests/_support/Common.php';

define('BASEPATH', realpath('../../CodeIgniter4/application/').'/');

if (! defined('WEEK'))
{
    /*
    |--------------------------------------------------------------------------
    | Timing Constants
    |--------------------------------------------------------------------------
    |
    | Provide simple ways to work with the myriad of PHP functions that
    | require information to be in seconds.
    */
        defined('SECOND')   || define('SECOND',                 1);
        defined('MINUTE')   || define('MINUTE',                60);
        defined('HOUR')     || define('HOUR',                3600);
        defined('DAY')      || define('DAY',                86400);
        defined('WEEK')     || define('WEEK',              604800);
        defined('MONTH')    || define('MONTH',            2592000);
        defined('YEAR')     || define('YEAR',            31536000);
        defined('DECADE')   || define('DECADE',         315360000);
}
