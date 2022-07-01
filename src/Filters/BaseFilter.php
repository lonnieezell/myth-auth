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
        // Load the Auth config, for constructor only!!!
        $config = config(AuthConfig::class);

        // Load the routes
        $this->landingRoute   = $config->landingRoute;
        $this->reservedRoutes = $config->reservedRoutes;

        // Load the authenticate service
        $this->authenticate = service('authentication');

        // Load the authorize service
        $this->authorize = service('authorization');

        // Load the helper
        if (! function_exists('logged_in')) {
            helper('auth');
        }
    }
}
