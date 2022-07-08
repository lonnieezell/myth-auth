<?php

namespace Myth\Auth\Config;

use Myth\Auth\Config\Auth as AuthConfig;

// Myth:Auth routes file.
$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], static function ($routes) {
    // Load the reserved routes from Auth.php
    $config         = config(AuthConfig::class);
    $reservedRoutes = $config->reservedRoutes;

    // Login/out
    $routes->get($reservedRoutes['login'], 'Auth::login', ['as' => $reservedRoutes['login']]);
    $routes->post($reservedRoutes['login'], 'Auth::attemptLogin');
    $routes->get($reservedRoutes['logout'], 'Auth::logout');

    // Registration
    $routes->get($reservedRoutes['register'], 'Auth::register', ['as' => $reservedRoutes['register']]);
    $routes->post($reservedRoutes['register'], 'Auth::attemptRegister');

    // Activation
    $routes->get($reservedRoutes['activate-account'], 'Auth::activateAccount', ['as' => $reservedRoutes['activate-account']]);
    $routes->get($reservedRoutes['resend-activate-account'], 'Auth::resendActivateAccount', ['as' => $reservedRoutes['resend-activate-account']]);

    // Forgot/Resets
    $routes->get($reservedRoutes['forgot'], 'Auth::forgotPassword', ['as' => $reservedRoutes['forgot']]);
    $routes->post($reservedRoutes['forgot'], 'Auth::attemptForgot');
    $routes->get($reservedRoutes['reset-password'], 'Auth::resetPassword', ['as' => $reservedRoutes['reset-password']]);
    $routes->post($reservedRoutes['reset-password'], 'Auth::attemptReset');
});
