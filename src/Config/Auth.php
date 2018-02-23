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
        'reset'     => 'Myth\Auth\Views\reset',
        'emailForgot' => 'Myth\Auth\Views\emails\forgot',
    ];

    //--------------------------------------------------------------------
    // Authentication
    //--------------------------------------------------------------------

    // Fields that are available to be used as credentials for login.
    public $validFields = [
        'email', 'username'
    ];

    //--------------------------------------------------------------------
    // Allow Persistent Login Cookies (Remember me)
    //--------------------------------------------------------------------
    // While every attempt has been made to create a very strong protection
    // with the remember me system, there are some cases (like when you
    // need extreme protection, like dealing with users financials) that
    // you might not want the extra risk associated with this cookie-based
    // solution.
    //
    public $allowRemembering = false;

    //--------------------------------------------------------------------
    // Remember Length
    //--------------------------------------------------------------------
    // The amount of time, in seconds, that you want a login to last for.
    // Defaults to 30 days.
    //
    public $rememberLength = 30 * DAY;

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

    //--------------------------------------------------------------------
    // MINIMUM PASSWORD LENGTH
    //--------------------------------------------------------------------
    // The minimum length that a password must be to be accepted.
    // Recommended minimum value by NIST = 8 characters.
    //
    public $minimumPasswordLength = 8;

    //--------------------------------------------------------------------
    // PASSWORD CHECK HELPERS
    //--------------------------------------------------------------------
    // The PasswordValidater class runs the password through all of these
    // classes, each getting the opportunity to pass/fail the password.
    // You can add custom classes as long as they adhere to the
    // Password\ValidatorInterface.
    //
    public $passwordValidators = [
        'Myth\Auth\Authentication\Passwords\CompositionValidator',
        'Myth\Auth\Authentication\Passwords\DictionaryValidator',
    ];
}
