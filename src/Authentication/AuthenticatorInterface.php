<?php namespace Myth\Auth\Authentication;

use Myth\Auth\Entities\User;

interface AuthenticatorInterface
{
    /**
     * Attempts to validate the credentials and log a user in.
     *
     * @param array $credentials
     * @param bool  $remember Should we remember the user (if enabled)
     *
     * @return bool
     */
    public function attempt(array $credentials, bool $remember = null): bool;

    /**
     * Checks to see if the user is logged in or not.
     *
     * @return bool
     */
    public function check(): bool;

    /**
     * Checks the user's credentials to see if they could authenticate.
     * Unlike `attempt()`, will not log the user into the system.
     *
     * @param array $credentials
     * @param bool  $returnUser
     *
     * @return bool|User
     */
    public function validate(array $credentials, bool $returnUser=false);

    /**
     * Validates the user password
     *
     * @param User $user
     * @param string $password
     *
     * @return bool
     */
    public function validate_password(User $user, string $password) : bool;

    /**
     * Returns the User instance for the current logged in user.
     *
     * @return \Myth\Auth\Entities\User|null
     */
    public function user();
}
