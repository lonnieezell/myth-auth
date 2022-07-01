<?php

namespace Myth\Auth\Authentication\Resetters;

use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;

abstract class BaseResetter
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
     * Sends a reset message to user
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
     * Allows for changing the config file on the Resetter.
     *
     * @return $this
     */
    public function setConfig(AuthConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Gets a config settings for current Resetter.
     *
     * @return object
     */
    public function getResetterSettings()
    {
        return (object) $this->config->userResetters[static::class];
    }

    /**
     * Returns the current error.
     */
    public function error(): string
    {
        return $this->error;
    }
}
