<?php

/**
 * Myth:Auth routes file.
 */

$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], function($routes) {
    // Login/out
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');

    // Registration
    $routes->get('register', 'AuthController::register', ['as' => 'register']);
    $routes->post('register', 'AuthController::attemptRegister');

    // Forgot/Resets
});

