<?php namespace Myth\Auth\Authentication;

class LocalAuthenticator extends AuthenticationBase implements AuthenticatorInterface
{
    /**
     * Attempts to validate the credentials and log a user in.
     *
     * @param array $credentials
     * @param bool  $remember Should we remember the user (if enabled)
     *
     * @return bool
     */
    public function attempt(array $credentials, bool $remember = null): bool
    {
    }

    /**
     * Checks to see if the user is logged in or not.
     *
     * @return bool
     */
    public function check(): bool
    {
    }

    /**
     * Checks the user's credentials to see if they could authenticate.
     * Unlike `attempt()`, will not log the user into the system.
     *
     * @return bool
     */
    public function validate(): bool
    {
    }
}
