<?php namespace Myth\Auth\Entities;

use CodeIgniter\Entity;
use Myth\Auth\Config\Auth;

class User extends Entity
{
    /**
     * Maps names used in sets and gets against unique
     * names within the class, allowing independence from
     * database column names.
     *
     * Example:
     *  $datamap = [
     *      'db_name' => 'class_name'
     *  ];
     */
    protected $datamap = [];

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active'           => 'boolean',
        'force_pass_reset' => 'boolean',
        'deleted'          => 'boolean',
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
        $config = config(Auth::class);

		$this->attributes['password_hash'] = password_hash(
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
		$this->attributes['reset_hash'] = bin2hex(random_bytes(16));
		$this->attributes['reset_start_time'] = date('Y-m-d H:i:s');

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
		$this->attributes['activate_hash'] = bin2hex(random_bytes(16));

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
		$this->attributes['status'] = 'banned';
		$this->attributes['status_message'] = $reason;

		return $this;
	}

	/**
	 * Removes a ban from a user.
	 *
	 * @return $this
	 */
	public function unBan()
	{
		$this->attributes['status'] = $this->status_message = '';

		return $this;
	}

	/**
	 * Checks to see if a user has been banned.
	 *
	 * @return bool
	 */
	public function isBanned(): bool
	{
		return $this->attributes['status'] === 'banned';
	}

    /**
     * Returns the user's permissions, automatically
     * json_decoding them into an associative array.
     *
     * @return array|mixed
     */
    public function getPermissions()
    {
        return ! empty($this->permissions)
            ? json_decode($this->permissions, true)
            : [];
	}

    /**
     * Stores the permissions, automatically json_encoding
     * them for saving.
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions = null)
    {
        if (is_array($permissions))
        {
            $this->permissions = json_encode($permissions);
        }

        return $this;
	}
}
