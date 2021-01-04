<?php namespace Myth\Auth\Authentication\Activators;

use Myth\Auth\Config\Auth;
use Myth\Auth\Entities\User;

abstract class BaseActivator
{
	/**
	 * @var Auth
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
	 *
	 * @return bool
	 */
	abstract public function send(User $user = null): bool;

	/**
	 * Sets the initial config file.
	 *
	 * @param Auth|null $config
	 */
	public function __construct(Auth $config = null)
	{
		$this->config = $config ?? config('Auth');
	}

	/**
	 * Allows for changing the config file on the Activator.
	 *
	 * @param Auth $config
	 *
	 * @return $this
	 */
	public function setConfig(Auth $config)
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
	 *
	 * @return string
	 */
	public function error(): string
	{
		return $this->error;
	}
}
