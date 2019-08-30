# Myth:Auth

[![Build Status](https://travis-ci.org/codeigniter4/CodeIgniter4.svg?branch=develop)](https://travis-ci.org/codeigniter4/CodeIgniter4)

Flexible, Powerful, Secure auth package for CodeIgniter 4.

**NOTE: This package is under early development and is not ready for prime-time.**

## Intended Features

This is meant to be a one-stop shop for 99% of your web-based authentication needs with CI4. It includes
the following primary features: 

- [x] Password-based authentication with remember-me functionality for web apps
- [x] Flat RBAC per NIST standards. (Will link it when I find it again)
- [x] all views/javascript necessary in cross-browser manner
- [x] easy to "publish" files to the main application for easy customization. Done via a CLI command.
- [x] Debug Toolbar integration

## Installation

Installation is intended to be done via Composer. Assuming Composer is installed globally, you may use
the following command: 

    > composer require myth/auth

### Configuration

Once installed you need to let your CodeIgniter 4 application know where to find the libraries. In your application,
perform the following setup: 

1. Edit **app/Config/Autoload.php** and add the **Myth\Auth** namespace to the **$psr4** array.
2. Edit **app/Config/Routes.php** and set **discoverLocal** to **true**.
3. Edit **app/Config/Email.php** and verify that a **fromName** and **fromEmail** are set 
    as that is used when sending emails for password reset, etc. 
4. Edit **app/Config/Validation.php** and add the following value to the **ruleSets** array: 
    `\Myth\Auth\Authentication\Passwords\ValidationRules::class`
4. Ensure your database is setup correctly, then run the Auth migrations: 

    > php spark migrate -all  

## Overview

When first installed, Myth:Auth is setup to provide all of the basic authentication services 
for you, including new user registration, login/logout, and forgotten password flows.

Remember me functionality is turned off by default, though that can be turned on in Config/Auth.php
by setting the `$allowRemembering` variable to be `true`.

### Routes

Routes are defined in Auth's **Config/Routes.php** file. This file is automatically located by CodeIgniter
when it is processing the routes. If you would like to customize the routes, you should copy the file
to the **app/Config** directory and make your changes there.

### Views

Basic views are provided that are based on [Bootstrap 4](http://getbootstrap.com/) for all features.

You can easily override the views used by editing Config/Auth.php, and changing the appropriate values
within the `$views` variable: 

    public $views = [
        'login'     => 'Myth\Auth\Views\login',
        'register'  => 'Myth\Auth\Views\register',
        'forgot'    => 'Myth\Auth\Views\forgot',
        'reset'     => 'Myth\Auth\Views\reset',
        'emailForgot' => 'Myth\Auth\Views\emails\forgot',
    ];

NOTE: If you're not familiar with how views can be namespaced in CodeIgniter, please refer to 
[the user guide](https://codeigniter4.github.io/CodeIgniter4/general/modules.html) for CI4's 
Code Module support. 

## Services

The following Services are provided by the package: 

**authentication** 

Provides access to any of the authenticacation packages that Myth:Auth knows about. By default
it will return the "Local Authentication" library, which is the basic password-based system.

    $authenticate = Myth\Auth\Config\Services::authentication();
    
You can specify the library to use as the first argument:

    $authenticate = Myth\Auth\Config\Services::authentication('jwt');
    
**authorization**

Provides access to any of the authorization libraries that Myth:Auth knows about. By default
it will return the "Flat" authorization library, which is a Flat RBAC (role-based access control)
as defined by NIST. It provides user-specific permissions as well as group (role) based permissions.

    $authorize = $auth = Myth\Auth\Config\Services::authorization();

**passwords**

Provides direct access to the Password validation system. This is an expandable system that currently
supports many of [NIST's latest Digital Identity guidelines](https://pages.nist.gov/800-63-3/). The 
validator comes with a dictionary of over 620,000 common/leaked passwords that can be checked against.
A handful of variations on the user's email/username are automatically checked against. 

    $authenticate = Myth\Auth\Config\Services::passwords();
   
Most of the time you should not need to access this library directly, though, as a new Validation rule
is provided that can be used with the Validation library, `strong_password`. In order to enable this, 
you must first edit **app/Config/Validation.php** and add the new ruleset to the available rule sets:

     public $ruleSets = [
        \CodeIgniter\Validation\Rules::class,
        \CodeIgniter\Validation\FormatRules::class,
        \CodeIgniter\Validation\FileRules::class,
        \CodeIgniter\Validation\CreditCardRules::class,
        \Myth\Auth\Authentication\Passwords\ValidationRules::class,
    ];
    
Now you can use `strong_password` in any set of rules for validation:

    $validation->setRules([
        'username' => 'required',
        'password' => 'required|strong_password'
    ]);

## Helper Functions

Myth:Auth comes with its own [Helper](https://codeigniter4.github.io/CodeIgniter4/general/helpers.html) 
that includes the following helper functions to ease access to basic features. Be sure to
load the helper before using these functions: `helper('auth');`

**Hint**: Add `'auth'` to any controller's `$helper` property to have it loaded automatically,
or the same in **app/Controllers/BaseController.php** to have it globally available.

**logged_in()**

* Function: Checks to see if any user is logged in.
* Parameters: None
* Returns: `true` or `false`

**user()**

* Function: Returns the User instance for the current logged in user.
* Parameters: None
* Returns: The current User entity, or `null`

**user_id()**

* Function: Returns the User ID for the current logged in user.
* Parameters: None
* Returns: The current User's integer ID, or `null`

**in_groups()**

* Function: Ensures that the current user is in at least one of the passed in groups.
* Parameters: Group IDs or names, as either a single item or an array of items.
* Returns: `true` or `false`

**has_permission()**

* Function: Ensures that the current user has at least one of the passed in permissions.
* Parameters: Permission ID or name.
* Returns: `true` or `false`


## Users

Myth:Auth uses [CodeIgniter Entities](https://codeigniter4.github.io/CodeIgniter4/models/entities.html) 
for it's User object, and your application must also use that class. This class
provides automatic password hashing as well as utility methods for banning/un-banning, password
reset hash generation, and more. 

It also provides a UserModel that should be used as it provides methods needed during the 
password-reset flow, as well as basic validation rules. You are free to extend this class
or modify it as needed.

### Toolbar

Myth:Auth includes a toolbar collector to make it easy for developers to work with and troubleshoot
the authentication process. To enable the collector, edit **app/Config/Toolbar.php** and add it to
the list of active collectors:

```
	public $collectors = [
		\CodeIgniter\Debug\Toolbar\Collectors\Timers::class,
		\CodeIgniter\Debug\Toolbar\Collectors\Database::class,
        ...
		\Myth\Auth\Collectors\Auth::class,
	];
```

## Restricting by Route

If you specify each of your routes within the `app/Config/Routes.php` file, you can restrict access
to users by group/role or permission with [Controller Filters](https://codeigniter4.github.io/CodeIgniter4/incoming/filters.html).

First, edit `application/Config/Filters.php` and add the following entries to the `aliases` property:

```
    'login'      => \Myth\Auth\Filters\LoginFilter::class,
    'role'       => \Myth\Auth\Filters\RoleFilter::class,
    'permission' => \Myth\Auth\Filters\PermissionFilter::class,
```

**Global restrictions**

The role and permission filters require additional parameters, but `LoginFilter` can be used to
restrict portions of a site (or the entire site) to any authenticated user. If no logged in user is detected
then the filter will redirect users to the login form.

Restrict routes based on their URI pattern by editing **app/Config/Filters.php** and adding them to the
`$filters` array, e.g.:

```
public filters = [
    'login' => ['before' => ['account/*']],
];
```

Or restrict your entire site by adding the `LoginFilter` to the `$globals` array:
```
    public $globals = [
        'before' => [
            'honeypot',
            'login',
    ...
```

**Restricting a single route**

Any single route can be restricted by adding the `filter` option to the last parameter in any of the route definition
methods:

```
$routes->get('admin/users', 'UserController::index', ['filter' => 'permission:manage-user'])
$routes->get('admin/users', 'UserController::index', ['filter' => 'role:admin,superadmin'])
``` 

The filter can be either `role` or `permission`, which restricts the route by either group or permission. 
You must add a comma-separated list of groups or permissions to check the logged in user against. 

**Restricting Route Groups**

In the same way, entire groups of routes can be restricted within the `group()` method:

```
$routes->group('admin', ['filter' => 'role:admin,superadmin'], function($routes) {
    ...
});
```

## Customization

This library is intentionally slim on user identifying information, having only the fields necessary for
authentication and authorization. You will likely want to add fields like a user's name or phone number.
You can create your own migration to add these fields (see: [an example migration](bin/20190603101528_alter_table_users.php).
If you used `auth:publish` you can also add these fields to your `UserModel`'s `$allowedFields` property.
