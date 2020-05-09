# User Authentication

The Authentication library that is included strives to work with known best practices for protecting your users. 
To this end, an `Auth` module has been provided that takes advantage of this for processes like user registration, 
login, logout, password resets, and more. This guide does NOT talk about the included module, since it should be easy 
to digest by looking through it. Instead, this guide goes over how configure the system and how to use the underlying 
technology so that you can intelligently modify the authentication system to meet your site's specific needs.

Do not modify the contents of any of this code directly, including views, config files, etc. Instead, you should
copy the file to the appropriate spot in your application, and modify the namespace of the file. This allows you to 
upgrade to newer versions without overwriting your own code. 

A CLI command has been provided to take care of this for you. Run the following command from the command prompt, and
it will ask you which sections you would like to "publish" into your application. 

    > php spark auth:publish

NOTE: it assumes a standard CodeIgniter 4 application structure, so will need to manually move files if you have changed 
the directory structure.

> DISCLAIMER: I am, by no means, a security expert. Any knowledge that I have has been gathered from reading articles 
from people smarter and more experienced than I am. If you know that I've explained something incorrectly and can tell 
me the correct solution, or can point to research that invalidates anything said here, please feel free to drop me a 
line and correct me. I'll make sure to read through it and try to correct either my docs or the code itself.

## LocalAuthentication Class

The included class, `Myth\Auth\Authentication\LocalAuthentication`, together with the `LoginModel` is the power 
behind the features listed below. If you want to create your own, create a new class that implements the 
`Myth\Auth\Authentication\AuthenticateInterface`. Then modify `app/Config/App.php` to use the new class. This 
will be automatically loaded up and readied for any class that uses the [Auth Trait](auth_trait.md).

## Logging Users In

Use the `attempt()` method to attempt to log users in. The first parameter is an array of credentials to verify the 
user against. The library does not enforce a specific set of credentials. You are free to use any 
combination of fields that exist within the `users` table, but typical uses would be either `email` or `username`. 
You must include a field name `password`, though as it will be verified against the hashed version in the database.

	$auth = Services::authenticate();
	
	$credentials = [
		'email' => $this->request->getPost('email', true),
		'password' => $this->request->getPost('password', true)
	];
	
	$auth->attempt($credentials);

The method returns either `true` or `false` depending on the success/failure of the login. Upon an error, you can 
retrieve the error string by calling the `error()` method.

	if (! $auth->attempt($credentials) )
	{
		$this->setMessage($auth->error(), 'danger');
	}

The second parameter is a boolean value that tells whether we should remember the user. See the section on 
[Remembering Users](#remembering_users) for more details. The system does allow for a user to be remembered on more than 
one device and more than one browser at a time. Which allows them to maintain separate persistent logins at home and 
work and even on their mobile device simultaneously. 

	$auth->attempt($credentials, true);
	
Once a user has been successfully logged in a `logged_in` value will be set in the session's userdata.

## Validating Credentials

If you need to validate a user's credentials without actually logging them in, you can do so with the `validate()`. 
This is the same method  used by the `attempt()` method so the results will be identical. The first parameter is an 
array of credentials as described above.

	$credentials = [
		'email' => $this->input->post('email', true),
		'password' => $this->input->post('password', true)
	];
	
	if ($auth->validate($credentials) ) {...}
	
By default, the method will return either true or false. However, if you want to have the user object returned to you on successfuly validation, you can pass in true as the second parameter.

	if (! $user = $auth->validate($credentials) )
	{
		...
	}

## Determining If Logged In

You can use the `check()` to check if a user is logged in. As long as a user's session is valid -- it hasn't timed out, 
and they haven't logged out -- this is the only check needed to do to ensure that a user is validly logged in.

	if (! $auth->check() )
	{
		$this->session->set('redirect_url', current_url() );
		return redirect()->route('login');
	}

## Current User

Once logged in, the current user is stored in memory as a class variable. This can be accessed at any time with the 
`user()` method. There are no parameters. The method will return an array of all of the user's information.

	$current_user = $auth->user();

## Logging Out
You can log a user out by calling the `logout()` method. This will destroy their current session and invalidate the 
current RememberMe Token. Note this only affects a logout on the current machine. If a user logs out at work, they can 
remain logged in at home.

	$auth->logout();

### Current User Id
Often, you will only need the ID of the current user. You can get this with the `id()` method. It will return either 
an INT with the user's id, or NULL.

	$userId = $auth->id();

## Remembering Users
You can have a user be remembered, through the user of cookies, by passing true in as the second parameter to the 
`attempt()` method, as described above. But what happens then? I have tried to make the process as secure as possible, 
and will describe the process here so that you can understand the flow.

If you do NOT want your users to be able to use persistent logins, you can turn this off in `app/Config/Auth.php`, 
along with a number of other settings. See the section on [Configuration](#configuration), below.

If enabled, the remember-me tokens are checked automatically during the LocalAuthenticator's `check()` method. 
No further action is need on your part. 

### Security Flow

- When a user is set to be remembered, a Token is created that consists of a modified version of the user's email and a random 128-character, alpha-numeric string.
- The Token is saved to a cookie on the user's machine. This will later be used to identify the user when logging in automatically.
- The Token is then salted, hashed and stored in the database. The original token is then discarded and the system doesn't know anything about it anymore.
- When logging in automatically, the Token is retrieved from the cookie, salted and hashed and we attempt to find a match in the database.
- After automatic logins, the old tokens are discarded, both from the cookie and the database, and a new Token is generated and the process continues as described here.
	
## Removing All User's Persistent Logins
To allow a user to remove all login attempts associated with their email address, across all devices they might be 
logged in as, you can use the `purgeRememberTokens()` method. The only parameter is the email address of the user.

	$auth->purgeRememberTokens($email);

## Attaching the User Model
Before the system can work, you must tell it which model to use when working with Users.

	$auth->useModel( new \Myth\Auth\Models\UserModel() );

## Removing All Login Attempts for A User
If you need to remove all failed login attempts for a user you can use the `purgeLoginAttempts()` method. 
The only parameter is the email of the user.

	$auth->purgeLoginAttempts($email);

## User account activation

After the user has registered in our app, we have the option to activate the account immediately or ask for confirmation.
This is done via `$requireActivation` config variable.

Confirmation can be done in many ways. Traditionally, this is usually done by sending an email to the email address that was
used during registration. We use the `Activator` service for this.

	$activator = Services::activator();
	$activator->send($user);

By default, we provide one type of activator and this is `EmailActivator`. You can also prepare your own activator,
which will e.g. use an SMS to confirm activation. There are many possibilities.

## Force a password reset

If you need to force a user to reset their password, you can use the `forcePasswordReset()` method on the User 
entity to generate the required information on the model. This will then be checked during the LocalAuthenticator's
`check()` method, which is used by the AuthTrait and all Filters. At this point, the user will not be able to 
proceed to any protected pages. You must save the changes through the UserModel before the changes will persist.

```
$user->forcePasswordReset();
$userModel->save($user);
```

## Configuration
Many aspects of the system can be configured in the `Config/Auth.php` config file. These options are described here. 

### auth.defaultUserGroup
Specifies the name of the group to which the user will be added during registration. By default, this variable is not set, so users will not be added to any group.

    public $defaultUserGroup;

### auth.authenticationLibs
Specifies the Authorization library that will be used by the Auth Trait. This should include the fully namespaced class name. 

	public $authenticationLibs = [
        'local' => LocalAuthenticator::class
    ];

### auth.validFields
The names of the fields in the user table that are allowed by used when testing credentials in the `validate()` method. 

	public $validFields = [
        'email', 'username'
    ];

### auth.allowRegistration
This can be either true or false, and determines whether or not the system allows unregistered users to make a new
account by accessing `AuthController->register()`. NOTE: This setting is not enforced by `UserModel` so if you add
or edit controller and views you are responsible for checking this value.

### auth.requireActivation
This can be either false or string with a namespaced class name. Using a class name will force `activator` service to use this
class to send an activation message.

	public $requireActivation = 'Myth\Auth\Authentication\Activators\EmailActivator';

### auth.userActivators
This is a list of available activators, along with their optional configuration variables. Class names listed here can be used
by `requireActivation` config variable.

	public $userActivators = [
        'Myth\Auth\Authentication\Activators\EmailActivator' => [
            'fromEmail' => null,
            'fromName' => null,
        ],
    ];

### auth.activeResetter
This can be either false or string with a namespaced class name. Using a class name will force `resetter` service to use this
 class to send a reset message.

	public $activeResetter = 'Myth\Auth\Authentication\Resetters\EmailResetter';

### auth.userResetters
This is a list of available resetters, along with their optional configuration variables. Class names listed here can be used
by `activeResetter` config variable.

	public $userResetters = [
        'Myth\Auth\Authentication\Resetters\EmailResetter' => [
            'fromEmail' => null,
            'fromName' => null,
        ],
    ];

### auth.allowRemembering
This can be either true or false, and determines whether or not the system allows persistent logins (Remember Me). 
For most sites, you will likely want this turned on for your user's convenience. If your site holds extremely 
confidential information and you cannot have your site hacked for any reason, you should set this to false and not 
allow persistent connections ever.

	public $allowRemembering = false;

### auth.rememberLength
This is the number of SECONDS that a persistent login lasts. A quick reference of common values is provided in the config 
file for your convenience. The default value is 30 days.

	public $rememberLength = 30 * DAY;

### auth.hashCost
The BCRYPT method of encryption allows you to define the "cost", or number of iterations made, whenever a password hash 
is created. This defaults to a value of 10 which is an acceptable number. However, depending on the security needs of 
your application and the power of your server, you might want to increase the cost. This makes the hashing process 
takes longer.

Valid range is between 4 - 31.

	public $hashCost = 10;

### auth.passwordValidators
This list holds the validators that will be run when checking that a password is valid. 
These are run one at a time in succession. 
You can easily add your own should you need to customize the validation rules.  

    public $passwordValidators = [
        'Myth\Auth\Authentication\Passwords\CompositionValidator',
        'Myth\Auth\Authentication\Passwords\DictionaryValidator',
        //'Myth\Auth\Authentication\Passwords\PwnedValidator',
    ];

The default validators that come with Myth:Auth are:

- CompositionValidator - Per the latest NIST recommendations checks password length.
- NothingPersonalValidator - Checks for variations on the user's personal info such as username and email in the password.
- DictionaryValidator - Compares the password with over 600,000 leaked passwords and common words in a dictionary included with this module. 
- PwnedValidator - Looks for the password in a list of over 500 million passwords exposed in data breaches.
    Because PwnedValidator uses a request to third-party API not everyone may be comfortable with using it. Therefore, this validator is optional and disabled by default. The decision to use it or not is up to you. More information and technical details can be found [here](https://www.troyhunt.com/ive-just-launched-pwned-passwords-version-2/#cloudflareprivacyandkanonymity).
    To use this validator simply remove the commented item in the $passwordValidators array.
    PwnedValidator is a good replacement for the DictionaryValidator and you probably don't have to use both.
