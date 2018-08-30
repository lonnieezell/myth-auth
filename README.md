# Myth:Auth

Flexible, Powerful, Secure auth package for CodeIgniter 4.

**NOTE: This package is under early development and is not ready for prime-time.**

## Intended Features

This is meant to be a one-stop shop for 99% of your authentication needs with CI4. The plan is 
to include the following primary features: 

- [x] Password-based authentication with remember-me functionality for web apps
- [ ] JWT authentication for APIs that should work with password-based accounts
- [ ] Social login integration by integrating [HybridAuth](https://hybridauth.github.io/). Works well with other accounts.
- [x] Flat RBAC per NIST standards. (Will link it when I find it again)
- [ ] all views/javascript necessary in cross-browser manner
- [x] easy to "publish" files to the main application for easy customization. Done via a CLI command.
- [ ] Debug Toolbar integration

## Installation

Since this version is still under heavy development, installation is not as smooth as it will be. 
Your best bet is to simply clone the repo for now:

    > git clone https://github.com/lonnieezell/myth-auth.git ./auth 

### Configuration

Once installed you need to let your CodeIgniter 4 application know where to find the libraries. In your application,
perform the following setup: 

1. Edit **application/Config/Autoload.php** and add the **Myth\Auth** namespace to the **$psr4** array.
2. Edit **application/Config/Routes.php** and set **discoverLocal** to **true**.
3. Edit **application/Config/Email.php** and verify that a **fromName** and **fromEmail** are set 
    as that is used when sending emails for password reset, etc. 
4. Edit **application/Config/Validation.php** and add the following value to the **ruleSets** array: 
    `\Myth\Auth\Authentication\Passwords\ValidationRules::class`
4. Ensure your database is setup correctly, then run the Auth migrations: 

    > php spark migrate:latest -all  

## Overview

When first installed, Myth:Auth is setup to provide all of the basic authentication services 
for you, including new user registration, login/logout, and forgotten password flows.

Remember me functionality is turned off by default, though that can be turned on in Config/Auth.php
by setting the `$allowRemembering` variable to be `true`.

### Routes

Routes are defined in Auth's **Config/Routes.php** file. This file is automatically located by CodeIgniter
when it is processing the routes. If you would like to customize the routes, you should copy the file
to the **application/Config** directory and make your changes there.

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
[the user guide](https://bcit-ci.github.io/CodeIgniter4/general/modules.html) for CI4's 
Code Module support. 

## Services

The following Services are provided by the package: 

**authentication** 

Provides access to any of the authenticacation packages that Myth:Auth knows about. By default
it will return the "Local Authentication" library, which is the basic password-based system.

    $authenticate = Myth\Auth\Services::authentication();
    
You can specify the library to use as the first argument:

    $authenticate = Myth\Auth\Services::authentication('jwt');
    
**authorization**

Provides access to any of the authorization libraries that Myth:Auth knows about. By default
it will return the "Flat" authorization library, which is a Flat RBAC (role-based access control)
as defined by NIST. It provides user-specific permissions as well as group (role) based permissions.

    $authorize = $auth = Myth\Auth\Services::authorization();

**passwords**

Provides direct access to the Password validation system. This is an expandable system that currently
supports many of [NIST's latest Digital Identity guidelines](https://pages.nist.gov/800-63-3/). The 
validator comes with a dictionary of over 620,000 common/leaked passwords that can be checked against.
A handful of variations on the user's email/username are automatically checked against. 

    $authenticate = Myth\Auth\Services::passwords();
   
Most of the time you should not need to access this library directly, though, as a new Validation rule
is provided that can be used with the Validation library, `strong_password`. In order to enable this, 
you must first edit **application/Config/Validation.php** and add the new ruleset to the available rule sets:

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

## Users

Myth:Auth uses [CodeIgniter Entities](https://bcit-ci.github.io/CodeIgniter4/database/entities.html) 
for it's User object, and your application must also use that class. This class
provides automatic password hashing as well as utility methods for banning/un-banning, password
reset hash generation, and more. 

It also provides a UserModel that should be used as it provides methods needed during the 
password-reset flow, as well as basic validation rules. You are free to extend this class
or modify it as needed.

## Restricting by Route

If you specify each of your routes within the `application/Config/Routes.php` file, you can restrict access
to users by group/role or permission with []Controller Filters](https://bcit-ci.github.io/CodeIgniter4/general/filters.html).

First, edit `application/Config/Filters.php` and add the following `role` and `permission` entries to the 
`aliases` property::

```
    'role' => \Myth\Auth\Filters\RoleFilter::class,
    'permission' => \Myth\Auth\Filters\PermissionFilter::class,
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
