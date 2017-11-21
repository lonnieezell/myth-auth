# Myth:Auth

Flexible, Powerful, Secure auth package for CodeIgniter 4.

**NOTE: This package is under early development and is not ready for prime-time.**

## Intended Features

This is meant to be a one-stop shop for 99% of your authentication needs with CI4. The plan is 
to include the following primary features: 

- Password-based authentication with remember-me functionality for web apps
- JWT authentication for APIs that should work with password-based accounts
- Social login integration by integrating [HybridAuth](https://hybridauth.github.io/). Works well with other accounts.
- Flat RBAC per NIST standards. (Will link it when I find it again)
- all views/javascript necessary in cross-browser manner
- easy to "publish" files to the main application for easy customization. Done via a CLI command.
- Debug Toolbar integration

## Installation

1. Edit **application/Config/Autoload.php** and add the **Myth\Auth** namespace to the **$psr4** array.
2. Edit **application/Config/Routes.php** and set **discoverLocal** to **true**.
