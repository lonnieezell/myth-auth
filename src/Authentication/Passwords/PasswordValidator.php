<?php namespace Myth\Auth\Authentication\Passwords;

use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;
use Myth\Auth\Exceptions\AuthException;

class PasswordValidator
{
	/**
	 * @var AuthConfig
	 */
	protected $config;

	protected $error;

	protected $suggestion;

	public function __construct(AuthConfig $config)
	{
		$this->config = $config;
	}

	/**
	 * Checks a password against all of the Validators specified
	 * in `$passwordValidators` setting in Config\Auth.php.
	 *
	 * @param string $password
	 * @param User   $user
	 *
	 * @return bool
	 */
	public function check(string $password, User $user = null): bool
	{
		if (is_null($user))
		{
			throw AuthException::forNoEntityProvided();
		}

		$password = trim($password);

		if (empty($password))
		{
			$this->error = lang('Auth.errorPasswordEmpty');

			return false;
		}

		$valid = false;

		foreach ($this->config->passwordValidators as $className)
		{
			$class = new $className();
			$class->setConfig($this->config);

			if ($class->check($password, $user) === false)
			{
				$this->error = $class->error();
				$this->suggestion = $class->suggestion();

				$valid = false;
				break;
			}

			$valid = true;
		}

		return $valid;
	}

	/**
	 * Returns the current error, as defined by validator
	 * it failed to pass.
	 *
	 * @return mixed
	 */
	public function error()
	{
		return $this->error;
	}

	/**
	 * Returns a string with any suggested fix
	 * based on the validator it failed to pass.
	 *
	 * @return mixed
	 */
	public function suggestion()
	{
		return $this->suggestion;
	}
}
