<?php namespace Myth\Auth\Authentication\Resetters;

use Myth\Auth\Config\Auth;
use Myth\Auth\Entities\User;

abstract class BaseResetter
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
	 * Sends a reset message to user
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
	 * Allows for changing the config file on the Resetter.
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
	 *
	 * @return string
	 */
	public function error(): string
	{
		return $this->error;
	}
}
