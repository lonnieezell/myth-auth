<?php

namespace Myth\Auth\Config;

use CodeIgniter\Router\RouteCollection;
use Myth\Auth\Config\Auth as AuthConfig;

/** @var RouteCollection $routes */

// Myth:Auth routes file.
$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], static function ($routes) {
    // Load the reserved routes from Auth.php
    $config         = config(AuthConfig::class);
    $reservedRoutes = $config->reservedRoutes;

    // Login/out
    $routes->get($reservedRoutes['login'], 'AuthController::login', ['as' => 'login']);
    $routes->post($reservedRoutes['login'], 'AuthController::attemptLogin');
    $routes->get($reservedRoutes['logout'], 'AuthController::logout', ['as' => 'logout']);

    // Registration
    $routes->get($reservedRoutes['register'], 'AuthController::register', ['as' => 'register']);
    $routes->post($reservedRoutes['register'], 'AuthController::attemptRegister');

    // Activation
    $routes->get($reservedRoutes['activate-account'], 'AuthController::activateAccount', ['as' => 'activate-account']);
    $routes->get($reservedRoutes['resend-activate-account'], 'AuthController::resendActivateAccount', ['as' => 'resend-activate-account']);

    // Forgot/Resets
    $routes->get($reservedRoutes['forgot'], 'AuthController::forgotPassword', ['as' => 'forgot']);
    $routes->post($reservedRoutes['forgot'], 'AuthController::attemptForgot');
    $routes->get($reservedRoutes['reset-password'], 'AuthController::resetPassword', ['as' => 'reset-password']);
    $routes->post($reservedRoutes['reset-password'], 'AuthController::attemptReset');
});
