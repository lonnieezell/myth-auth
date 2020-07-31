<?php

namespace Myth\Auth\Authentication;

interface AuthenticatorInterface
{
    /**
     * Attempts to validate the credentials and log a user in.
     *
     * @param array $credential
     * @param bool  $remember Should we remember the user (if enabled)
     *
     * @return bool
     */
    public function attempt(array $credential, bool $remember = false): bool;

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
     *
     * @return boolean
     */
    public function validate(array $credential);

    /**
     * Returns the User instance for the current logged in user.
     *
     * @return \Myth\Auth\Entities\User|null
     */
    public function user();
}
