<?php

namespace Myth\Auth\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use Exception;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Password;
use RuntimeException;

/**
 * @property array<int, string> $permissions
 * @property Time|null          $reset_expires
 */
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
        'username'         => 'string',
        'email'            => 'string',
        'active'           => 'boolean',
        'force_pass_reset' => 'boolean',
    ];

    /**
     * Per-user permissions cache
     *
     * @var array<int, string>
     */
    protected $permissions = [];

    /**
     * Per-user roles cache
     *
     * @var array
     */
    protected $roles = [];

    /**
     * Automatically hashes the password when set.
     *
     * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
     */
    public function setPassword(string $password)
    {
        $this->attributes['password_hash'] = Password::hash($password);

        /*
            Set these vars to null in case a reset password was asked.
            Scenario:
                user (a *dumb* one with short memory) requests a
                reset-token and then does nothing => asks the
                administrator to reset his password.
            User would have a new password but still anyone with the
            reset-token would be able to change the password.
        */
        $this->attributes['reset_hash']    = null;
        $this->attributes['reset_at']      = null;
        $this->attributes['reset_expires'] = null;
    }

    /**
     * Explicitly convert false and true to 0 and 1
     *
     * Some BDD (PostgreSQL for example) can be picky about data types and
     * if 'active' or 'force_pass_reset' are set to (bool)true/false, the method
     * CodeIgniter\Database\Postgre\Connection::escape() will translate it
     * to a literal TRUE/FALSE. Since the database fields are defined as integer,
     * the BDD will throw an error about mismatched type.
     *
     * @param bool|int $active
     */
    public function setActive($active)
    {
        $this->attributes['active'] = $active ? 1 : 0;
    }

    /**
     * Explicitly convert false and true to 0 and 1
     *
     * @see setActive()  Explanation about strict typing at database level
     *
     * @param bool|int $force_pass_reset
     */
    public function setForcePassReset($force_pass_reset)
    {
        $this->attributes['force_pass_reset'] = $force_pass_reset ? 1 : 0;
    }

    /**
     * Force a user to reset their password on next page refresh
     * or login. Checked in the LocalAuthenticator's check() method.
     *
     * @return $this
     *
     * @throws Exception
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
     *
     * @throws Exception
     */
    public function generateResetHash()
    {
        $this->attributes['reset_hash']    = bin2hex(random_bytes(16));
        $this->attributes['reset_expires'] = date('Y-m-d H:i:s', time() + config('Auth')->resetTime);

        return $this;
    }

    /**
     * Generates a secure random hash to use for account activation.
     *
     * @return $this
     *
     * @throws Exception
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
        $this->attributes['active']        = 1;
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
     */
    public function isActivated(): bool
    {
        return $this->active;
    }

    /**
     * Bans a user.
     *
     * @return $this
     */
    public function ban(string $reason)
    {
        $this->attributes['status']         = 'banned';
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
     */
    public function isBanned(): bool
    {
        return isset($this->attributes['status']) && $this->attributes['status'] === 'banned';
    }

    /**
     * Determines whether the user has the appropriate permission,
     * either directly, or through one of it's groups.
     *
     * @return bool
     */
    public function can(string $permission)
    {
        return in_array(strtolower($permission), $this->getPermissions(), true);
    }

    /**
     * Returns the user's permissions, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id=> name,
     * ]
     *
     * @return array<int, string>
     */
    public function getPermissions()
    {
        if (empty($this->id)) {
            throw new RuntimeException('Users must be created before getting permissions.');
        }

        if (empty($this->permissions)) {
            $this->permissions = model(PermissionModel::class)->getPermissionsForUser($this->id);
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
        if (empty($this->id)) {
            throw new RuntimeException('Users must be created before getting roles.');
        }

        if (empty($this->roles)) {
            $groups = model(GroupModel::class)->getGroupsForUser($this->id);

            foreach ($groups as $group) {
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
    public function setPermissions(?array $permissions = null)
    {
        throw new RuntimeException('User entity does not support saving permissions directly.');
    }
}
