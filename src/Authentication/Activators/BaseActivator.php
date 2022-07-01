<?php

namespace Myth\Auth\Authentication\Activators;

use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;

abstract class BaseActivator
{
    /**
     * @var AuthConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $error = '';

    /**
     * Sends an activation message to user
     *
     * @param User $user
     */
    abstract public function send(?User $user = null): bool;

    /**
     * Sets the initial config file.
     */
    public function __construct(?AuthConfig $config = null)
    {
        $this->config = $config ?? config('Auth');
    }

    /**
     * Allows for changing the config file on the Activator.
     *
     * @return $this
     */
    public function setConfig(AuthConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Gets a config settings for current Activator.
     *
     * @return object
     */
    public function getActivatorSettings()
    {
        return (object) $this->config->userActivators[static::class];
    }

    /**
     * Returns the current error.
     */
    public function error(): string
    {
        return $this->error;
    }
}
