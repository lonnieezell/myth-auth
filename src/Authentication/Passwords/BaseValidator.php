<?php namespace Myth\Auth\Authentication\Passwords;

use Myth\Auth\Config\Auth as AuthConfig;

class BaseValidator
{
	/**
	 * @var AuthConfig
	 */
    protected $config;

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
}
