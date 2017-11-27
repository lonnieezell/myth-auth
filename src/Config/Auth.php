<?php namespace Myth\Auth\Config;

use CodeIgniter\Config\BaseConfig;
use Myth\Auth\Authentication\LocalAuthenticator;

class Auth extends BaseConfig
{
    //--------------------------------------------------------------------
    // Libraries
    //--------------------------------------------------------------------

    public $authenticationLibs = [
        'local' => LocalAuthenticator::class
    ];

    public $authorizationLibs = [
        'flat' => ''
    ];

    //--------------------------------------------------------------------
    // Views used by Auth Controllers
    //--------------------------------------------------------------------

    public $views = [
        'login'     => 'Myth\Auth\Views\login',
        'register'  => 'Myth\Auth\Views\register',
        'forgot'    => 'Myth\Auth\Views\forgot',
        'reset'     => 'Myth\Auth\Views\reset'
    ];

    //--------------------------------------------------------------------
    // PASSWORD HASHING COST
    //--------------------------------------------------------------------
    // The BCRYPT method of encryption allows you to define the "cost"
    // or number of iterations made, whenever a password hash is created.
    // This defaults to a value of 10 which is an acceptable number.
    // However, depending on the security needs of your application
    // and the power of your hardware, you might want to increase the
    // cost. This makes the hashing process takes longer.
    //
    // Valid range is between 4 - 31.
    public $hashCost = 10;
}
