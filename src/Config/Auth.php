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

    //--------------------------------------------------------------------
    // Views used by Auth Controllers
    //--------------------------------------------------------------------

    public $views = [
        'login'     => 'Myth\Auth\Views\login',
        'register'  => 'Myth\Auth\Views\register',
        'forgot'    => 'Myth\Auth\Views\forgot',
        'reset'     => 'Myth\Auth\Views\reset',
        'emailForgot' => 'Myth\Auth\Views\emails\forgot',
        'emailActivation' => 'Myth\Auth\Views\emails\activation',
    ];

    //--------------------------------------------------------------------
    // Authentication
    //--------------------------------------------------------------------

    // Fields that are available to be used as credentials for login.
    public $validFields = [
        'email', 'username'
    ];

    //--------------------------------------------------------------------
    // Allow User Registration
    //--------------------------------------------------------------------
    // When enabled (default) any unregistered user may apply for a new
    // account. If you disable registration you may need to ensure your
    // controllers and views know not to offer registration.
    //
    public $allowRegistration = true;

    //--------------------------------------------------------------------
    // Require confirmation registration via email
    //--------------------------------------------------------------------
    // When enabled, every registered user will receive an email message
    // with a special link he have to confirm to activate his account.
    //
    public $requireActivation = true;

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
    // Error handling
    //--------------------------------------------------------------------
    // If true, will continue instead of throwing exceptions.
    //
    public $silent = false;

    /*--------------------------------------------------------------------
	 * Encryption Algorithm to use
	 *--------------------------------------------------------------------
	 * Valid values are
	 * - PASSWORD_DEFAULT (default)
	 * - PASSWORD_BCRYPT
	 * - PASSWORD_ARGON2I  - As of PHP 7.2 only if compiled with support for it
	 * - PASSWORD_ARGON2ID - As of PHP 7.3 only if compiled with support for it
	 *
	 * If you choose to use any ARGON algorithm, then you might want to
	 * uncomment the "ARGON2i/D Algorithm" options to suit your needs
     */
	public $hashAlgorithm = PASSWORD_DEFAULT;

	/*--------------------------------------------------------------------
	 * ARGON2i/D Algorithm options
	 *--------------------------------------------------------------------
     * The ARGON2I method of encryption allows you to define the "memory_cost",
     * the "time_cost" and the number of "threads", whenever a password hash is
     * created.
     * This defaults to a value of 10 which is an acceptable number.
     * However, depending on the security needs of your application
     * and the power of your hardware, you might want to increase the
     * cost. This makes the hashing process takes longer.
     */

	public $hashMemoryCost = 2048; 	//PASSWORD_ARGON2_DEFAULT_MEMORY_COST;
	public $hashTimeCost = 4; 		//PASSWORD_ARGON2_DEFAULT_TIME_COST;
	public $hashThreads = 4; 		//PASSWORD_ARGON2_DEFAULT_THREADS;

    //--------------------------------------------------------------------
    // Password Hashing Cost
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
    // Minimum Password Length
    //--------------------------------------------------------------------
    // The minimum length that a password must be to be accepted.
    // Recommended minimum value by NIST = 8 characters.
    //
    public $minimumPasswordLength = 8;

    //--------------------------------------------------------------------
    // Password Check Helpers
    //--------------------------------------------------------------------
    // The PasswordValidater class runs the password through all of these
    // classes, each getting the opportunity to pass/fail the password.
    // You can add custom classes as long as they adhere to the
    // Password\ValidatorInterface.
    //
    public $passwordValidators = [
        'Myth\Auth\Authentication\Passwords\CompositionValidator',
        'Myth\Auth\Authentication\Passwords\DictionaryValidator',
        //'Myth\Auth\Authentication\Passwords\PwnedValidator',
    ];

    //--------------------------------------------------------------------
    // Reset Time
    //--------------------------------------------------------------------
    // The amount of time that a password reset-token is valid for,
    // in seconds.
    //
    public $resetTime = 3600;
}
