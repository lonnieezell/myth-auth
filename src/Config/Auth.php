<?php namespace Myth\Auth\Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
	/**
	 * --------------------------------------------------------------------
	 * Default User Group
	 * --------------------------------------------------------------------
	 *
	 * The name of a group a user will be added to when they register,
	 * i.e. $defaultUserGroup = 'guests'.
	 *
	 * @var string
	 */
	public $defaultUserGroup;

	/**
	 * --------------------------------------------------------------------
	 * Libraries
	 * --------------------------------------------------------------------
	 *
	 * @var array
	 */
	public $authenticationLibs = [
		'local' => 'Myth\Auth\Authentication\LocalAuthenticator',
	];

	/**
	 * --------------------------------------------------------------------
	 * Views used by Auth Controllers
	 * --------------------------------------------------------------------
	 *
	 * @var array
	 */
	public $views = [
		'login'		   => 'Myth\Auth\Views\login',
		'register'		=> 'Myth\Auth\Views\register',
		'forgot'		  => 'Myth\Auth\Views\forgot',
		'reset'		   => 'Myth\Auth\Views\reset',
		'emailForgot'	 => 'Myth\Auth\Views\emails\forgot',
		'emailActivation' => 'Myth\Auth\Views\emails\activation',
	];

	/**
	 * --------------------------------------------------------------------
	 * Layout for the views to extend
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $viewLayout = 'Myth\Auth\Views\layout';

	/**
	 * --------------------------------------------------------------------
	 * Authentication
	 * --------------------------------------------------------------------
	 *
	 * Fields that are available to be used as credentials for login.
	 *
	 * @var string[]
	 */
	public $validFields = [
		'email',
		'username',
	];

	/**
	 * --------------------------------------------------------------------
	 * Additional Fields for "Nothing Personal"
	 * --------------------------------------------------------------------
	 *
	 * The `NothingPersonalValidator` prevents personal information from
	 * being used in passwords. The email and username fields are always
	 * considered by the validator. Do not enter those field names here.
	 *
	 * An extend User Entity might include other personal info such as
	 * first and/or last names. `$personalFields` is where you can add
	 * fields to be considered as "personal" by the NothingPersonalValidator.
	 *
	 * For example:
	 *	 $personalFields = ['firstname', 'lastname'];
	 *
	 * @var string[]
	 */
	public $personalFields = [];

	/**
	 * --------------------------------------------------------------------
	 * Password / Username Similarity
	 * --------------------------------------------------------------------
	 *
	 * Among other things, the NothingPersonalValidator checks the
	 * amount of sameness between the password and username.
	 * Passwords that are too much like the username are invalid.
	 *
	 * The value set for $maxSimilarity represents the maximum percentage
	 * of similarity at which the password will be accepted. In other words, any
	 * calculated similarity equal to, or greater than $maxSimilarity
	 * is rejected.
	 *
	 * The accepted range is 0-100, with 0 (zero) meaning don't check similarity.
	 * Using values at either extreme of the *working range* (1-100) is
	 * not advised. The low end is too restrictive and the high end is too permissive.
	 * The suggested value for $maxSimilarity is 50.
	 *
	 * You may be thinking that a value of 100 should have the effect of accepting
	 * everything like a value of 0 does. That's logical and probably true,
	 * but is unproven and untested. Besides, 0 skips the work involved
	 * making the calculation unlike when using 100.
	 *
	 * The (admittedly limited) testing that's been done suggests a useful working range
	 * of 50 to 60. You can set it lower than 50, but site users will probably start
	 * to complain about the large number of proposed passwords getting rejected.
	 * At around 60 or more it starts to see pairs like 'captain joe' and 'joe*captain' as
	 * perfectly acceptable which clearly they are not.
	 *
	 *
	 * To disable similarity checking set the value to 0.
	 *	  public $maxSimilarity = 0;
	 *
	 * @var int
	 */
	public $maxSimilarity = 50;

	/**
	 * --------------------------------------------------------------------
	 * Allow User Registration
	 * --------------------------------------------------------------------
	 *
	 * When enabled (default) any unregistered user may apply for a new
	 * account. If you disable registration you may need to ensure your
	 * controllers and views know not to offer registration.
	 *
	 * @var bool
	 */
	public $allowRegistration = true;

	/**
	 * --------------------------------------------------------------------
	 * Require Confirmation Registration via Email
	 * --------------------------------------------------------------------
	 *
	 * When enabled, every registered user will receive an email message
	 * with an activation link to confirm the account.
	 *
	 * @var string Name of the ActivatorInterface class
	 */
	public $requireActivation = 'Myth\Auth\Authentication\Activators\EmailActivator';

	/**
	 * --------------------------------------------------------------------
	 * Allow Password Reset via Email
	 * --------------------------------------------------------------------
	 *
	 * When enabled, users will have the option to reset their password
	 * via the specified Resetter. Default setting is email.
	 *
	 * @var string Name of the ResetterInterface class
	 */
	public $activeResetter = 'Myth\Auth\Authentication\Resetters\EmailResetter';

	/**
	 * --------------------------------------------------------------------
	 * Allow Persistent Login Cookies (Remember me)
	 * --------------------------------------------------------------------
	 *
	 * While every attempt has been made to create a very strong protection
	 * with the remember me system, there are some cases (like when you
	 * need extreme protection, like dealing with users financials) that
	 * you might not want the extra risk associated with this cookie-based
	 * solution.
	 *
	 * @var bool
	 */
	public $allowRemembering = false;

	/**
	 * --------------------------------------------------------------------
	 * Remember Length
	 * --------------------------------------------------------------------
	 *
	 * The amount of time, in seconds, that you want a login to last for.
	 * Defaults to 30 days.
	 *
	 * @var int
	 */
	public $rememberLength = 30 * DAY;

	/**
	 * --------------------------------------------------------------------
	 * Error handling
	 * --------------------------------------------------------------------
	 *
	 * If true, will continue instead of throwing exceptions.
	 *
	 * @var bool
	 */
	public $silent = false;

	/**
	 * --------------------------------------------------------------------
	 * Encryption Algorithm to Use
	 * --------------------------------------------------------------------
	 *
	 * Valid values are
	 * - PASSWORD_DEFAULT (default)
	 * - PASSWORD_BCRYPT
	 * - PASSWORD_ARGON2I  - As of PHP 7.2 only if compiled with support for it
	 * - PASSWORD_ARGON2ID - As of PHP 7.3 only if compiled with support for it
	 *
	 * If you choose to use any ARGON algorithm, then you might want to
	 * uncomment the "ARGON2i/D Algorithm" options to suit your needs
	 *
	 * @var string|int
	 */
	public $hashAlgorithm = PASSWORD_DEFAULT;

	/*
	 * --------------------------------------------------------------------
	 * ARGON2i/D Algorithm options
	 * --------------------------------------------------------------------
	 *
	 * The ARGON2I method of encryption allows you to define the "memory_cost",
	 * the "time_cost" and the number of "threads", whenever a password hash is
	 * created.
	 *
	 * This defaults to a value of 10 which is an acceptable number.
	 * However, depending on the security needs of your application
	 * and the power of your hardware, you might want to increase the
	 * cost. This makes the hashing process takes longer.
	 */

	/** @var int */
	public $hashMemoryCost = 2048; // PASSWORD_ARGON2_DEFAULT_MEMORY_COST;

	/** @var int */
	public $hashTimeCost = 4; // PASSWORD_ARGON2_DEFAULT_TIME_COST;

	/** @var int */
	public $hashThreads = 4; // PASSWORD_ARGON2_DEFAULT_THREADS;

	/**
	 * --------------------------------------------------------------------
	 * Password Hashing Cost
	 * --------------------------------------------------------------------
	 *
	 * The BCRYPT method of encryption allows you to define the "cost"
	 * or number of iterations made, whenever a password hash is created.
	 * This defaults to a value of 10 which is an acceptable number.
	 * However, depending on the security needs of your application
	 * and the power of your hardware, you might want to increase the
	 * cost. This makes the hashing process takes longer.
	 *
	 * Valid range is between 4 - 31.
	 *
	 * @var int
	 */
	public $hashCost = 10;

	/**
	 * --------------------------------------------------------------------
	 * Minimum Password Length
	 * --------------------------------------------------------------------
	 *
	 * The minimum length that a password must be to be accepted.
	 * Recommended minimum value by NIST = 8 characters.
	 *
	 * @var int
	 */
	public $minimumPasswordLength = 8;

	/**
	 * --------------------------------------------------------------------
	 * Password Check Helpers
	 * --------------------------------------------------------------------
	 *
	 * The PasswordValidater class runs the password through all of these
	 * classes, each getting the opportunity to pass/fail the password.
	 *
	 * You can add custom classes as long as they adhere to the
	 * Password\ValidatorInterface.
	 *
	 * @var string[]
	 */
	public $passwordValidators = [
		'Myth\Auth\Authentication\Passwords\CompositionValidator',
		'Myth\Auth\Authentication\Passwords\NothingPersonalValidator',
		'Myth\Auth\Authentication\Passwords\DictionaryValidator',
		// 'Myth\Auth\Authentication\Passwords\PwnedValidator',
	];

	/**
	 * --------------------------------------------------------------------
	 * Activator classes
	 * --------------------------------------------------------------------
	 *
	 * Available activators with config settings
	 *
	 * @var array
	 */
	public $userActivators = [
		'Myth\Auth\Authentication\Activators\EmailActivator' => [
			'fromEmail' => null,
			'fromName' => null,
		],
	];

	/**
	 * --------------------------------------------------------------------
	 * Resetter Classes
	 * --------------------------------------------------------------------
	 *
	 * Available resetters with config settings
	 *
	 * @var array
	 */
	public $userResetters = [
		'Myth\Auth\Authentication\Resetters\EmailResetter' => [
			'fromEmail' => null,
			'fromName' => null,
		],
	];

	/**
	 * --------------------------------------------------------------------
	 * Reset Time
	 * --------------------------------------------------------------------
	 *
	 * The amount of time that a password reset-token is valid for,
	 * in seconds.
	 *
	 * @var int
	 */
	public $resetTime = 3600;
}
