<?php namespace Myth\Auth\Entities;

use CodeIgniter\Entity;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;

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
    protected $dates = ['reset_at', 'reset_expires', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active'           => 'boolean',
        'force_pass_reset' => 'boolean',
    ];

    /**
     * Per-user permissions cache
     * @var array
     */
    protected $permissions = [];

    /**
     * Per-user roles cache
     * @var array
     */
    protected $roles = [];

	/**
	 * Automatically hashes the password when set.
	 *
	 * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
	 *
	 * @param string $password
	 */
	public function setPassword(string $password)
	{
        $config = config('Auth');

        if (
            (defined('PASSWORD_ARGON2I') && $config->hashAlgorithm == PASSWORD_ARGON2I)
                ||
            (defined('PASSWORD_ARGON2ID') && $config->hashAlgorithm == PASSWORD_ARGON2ID)
            )
        {
            $hashOptions = [
                'memory_cost' => $config->hashMemoryCost,
                'time_cost'   => $config->hashTimeCost,
                'threads'     => $config->hashThreads
                ];
        }
        else
        {
            $hashOptions = [
                'cost' => $config->hashCost
                ];
        }

        $this->attributes['password_hash'] = password_hash(
            base64_encode(
                hash('sha384', $password, true)
            ),
            $config->hashAlgorithm,
            $hashOptions
        );

        /*
            Set these vars to null in case a reset password was asked.
            Scenario:
                user (a *dumb* one with short memory) requests a
                reset-token and then does nothing => asks the
                administrator to reset his password.
            User would have a new password but still anyone with the
            reset-token would be able to change the password.
        */
        $this->attributes['reset_hash'] = null;
        $this->attributes['reset_at'] = null;
        $this->attributes['reset_expires'] = null;
	}

    /**
     * Force a user to reset their password on next page refresh
     * or login. Checked in the LocalAuthenticator's check() method.
     *
     * @param User $user
     *
     * @return User
     * @throws \Exception
     */
    public function forcePasswordReset()
    {
        $this->generateResetHash();
        $this->attributes['force_pass_reset'] = 1;

        return $this;
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
		$this->attributes['reset_expires'] = date('Y-m-d H:i:s', time() + config('Auth')->resetTime);

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
     * Activate user.
     *
     * @return $this
     */
    public function activate()
    {
        $this->attributes['active'] = 1;
        $this->attributes['activate_hash'] = null;

        return $this;
    }

    /**
     * Unactivate user.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->attributes['active'] = 0;

        return $this;
    }

    /**
     * Checks to see if a user is active.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return isset($this->attributes['active']) && $this->attributes['active'] == true;
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
		return isset($this->attributes['status']) && $this->attributes['status'] === 'banned';
	}

    /**
     * Determines whether the user has the appropriate permission,
     * either directly, or through one of it's groups.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can(string $permission)
    {
        return in_array(strtolower($permission), $this->getPermissions());
	}

    /**
     * Returns the user's permissions, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id=> name,
     * ]
     *
     * @return array|mixed
     */
    public function getPermissions()
    {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting permissions.');
        }

        if (empty($this->permissions))
        {
            $this->permissions = (new PermissionModel())->getPermissionsForUser($this->id);
        }

        return $this->permissions;
    }

    /**
     * Returns the user's roles, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id => name,
     * ]
     *
     * @return array|mixed
     */
    public function getRoles()
    {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting roles.');
        }

        if (empty($this->roles))
        {
            $groups = (new GroupModel())->getGroupsForUser($this->id);

            foreach ($groups as $group)
            {
                $this->roles[$group['group_id']] = strtolower($group['name']);
            }
        }

        return $this->roles;
	}

    /**
     * Warns the developer it won't work, so they don't spend
     * hours tracking stuff down.
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions = null)
    {
        throw new \RuntimeException('User entity does not support saving permissions directly.');
	}
}
