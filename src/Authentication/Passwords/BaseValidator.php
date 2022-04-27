<?php

namespace Myth\Auth\Authentication\Passwords;

use Myth\Auth\Config\Auth as AuthConfig;

abstract class BaseValidator
{
    /**
     * @var AuthConfig|null
     */
    protected $config;

    /**
     * Error message.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Suggestion message.
     *
     * @var string
     */
    protected $suggestion = '';

    /**
     * Allows for setting a config file on the Validator.
     *
     * @param AuthConfig $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Returns the error string that should be displayed to the user.
     */
    public function error(): string
    {
        return $this->error;
    }

    /**
     * Returns a suggestion that may be displayed to the user
     * to help them choose a better password. The method is
     * required, but a suggestion is optional. May return
     * an empty string instead.
     */
    public function suggestion(): string
    {
        return $this->suggestion;
    }
}
