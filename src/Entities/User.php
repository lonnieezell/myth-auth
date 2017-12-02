<?php namespace Myth\Auth\Entities;

use CodeIgniter\Entity;
use Myth\Auth\Config\Auth;

class User extends Entity
{
	// Database Columns
	protected $id;
	protected $email;
	protected $username;
	protected $name;
	protected $password_hash;
	protected $reset_hash;
	protected $activate_hash;
	protected $status;
	protected $status_message;
	protected $active           = false;
	protected $force_pass_reset = false;
	protected $deleted          = false;
	protected $created_at;
	protected $updated_at;

	protected $_options = [
		/*
		 * Maps names used in sets and gets against unique
		 * names within the class, allowing independence from
		 * database column names.
		 *
		 * Example:
		 *  $datamap = [
		 *      'db_name' => 'class_name'
		 *  ];
		 */
		'datamap' => [],

		/*
		 * Define properties that are automatically converted to Time instances.
		 */
		'dates'   => ['created_at', 'updated_at'],

		/*
		 * Array of field names and the type of value to cast them as
		 * when they are accessed.
		 */
		'casts'   => [
			'active'           => 'boolean',
			'force_pass_reset' => 'boolean',
			'deleted'          => 'boolean',
		],
	];

	/**
	 * Automatically hashes the password when set.
	 *
	 * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
	 *
	 * @param string $password
	 */
	public function setPassword(string $password)
	{
		$config = new Auth();

		$this->password_hash = password_hash(
			base64_encode(
				hash('sha384', $password, true)
			),
			PASSWORD_DEFAULT,
			['cost' => $config->hashCost]
		);
	}

    /**
     * Generates a secure hash to use for password reset purposes,
     * saves it to the instance.
     *
     * @return $this
     * @throws \Exception
     */
	public function generateResetHash()
	{
		$this->reset_hash = bin2hex(random_bytes(16));

		return $this;
	}

    /**
     * Generates a secure random hash to use for account activation.
     *
     * @return $this
     * @throws \Exception
     */
	public function generateActivateHash()
	{
		$this->activate_hash = bin2hex(random_bytes(16));

		return $this;
	}

	/**
	 * Bans a user.
	 *
	 * @param string $reason
	 *
	 * @return $this
	 */
	public function ban(string $reason)
	{
		$this->status = 'banned';
		$this->status_message = $reason;

		return $this;
	}

	/**
	 * Removes a ban from a user.
	 *
	 * @return $this
	 */
	public function unBan()
	{
		$this->status = $this->status_message = '';

		return $this;
	}

	/**
	 * Checks to see if a user has been banned.
	 *
	 * @return bool
	 */
	public function isBanned(): bool
	{
		return $this->status === 'banned';
	}

}
