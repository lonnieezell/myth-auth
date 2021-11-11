<?php

namespace Myth\Auth\Filters;

use Myth\Auth\Config\Auth as AuthConfig;

abstract class BaseFilter
{
    /**
     * Landing Route
     */
    protected $landingRoute;

    /**
     * Reserved Routes
     */
    protected $reservedRoutes;

    /**
     * Authenticate
     */
    protected $authenticate;

    /**
     * Authorize
     */
    protected $authorize;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Load the routes
        $config = config(AuthConfig::class);
        $this->landingRoute = $config->landingRoute;
        $this->reservedRoutes = $config->reservedRoutes;

        // Load the authenticate
        $this->authenticate = service('authentication');

        // Load the authorize
        $this->authorize = service('authorization');

        // Load the helper
        if (! function_exists('logged_in')) {
            helper('auth');
        }
    }
}
