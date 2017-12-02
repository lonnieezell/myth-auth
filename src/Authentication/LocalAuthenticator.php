<?php namespace Myth\Auth\Authentication;

use Myth\Auth\Config\Services;
use Myth\Auth\Exceptions\AuthException;

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
        $this->user = $this->validate($credentials, true);

        // Always record a login attempt, whether success or not.
        $ipAddress = Services::request()->getIPAddress();
        $this->recordLoginAttempt($credentials, $ipAddress, ! is_bool($this->user));

        if (! $this->user)
        {
            $this->user = null;
            return false;
        }

        if ($remember)
        {
            $this->rememberUser($this->user);
        }

        return true;
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
     * @param array $credentials
     * @param bool  $returnUser
     *
     * @return bool|User
     */
    public function validate(array $credentials, bool $returnUser=false)
    {
        // Can't validate without a password.
        if (empty($credentials['password']) || count($credentials) < 2)
        {
            return false;
        }

        // Only allowed 1 additional credential other than password
        $password = $credentials['password'];
        unset($credentials['password']);

        if (count($credentials) > 1)
        {
            throw AuthException::forTooManyCredentials();
        }

        // Ensure that the fields are allowed validation fields
        if (! in_array(key($credentials), $this->config->validFields))
        {
            throw AuthException::forInvalidFields(key($credentials));
        }

        // Can we find a user with those credentials?
        $user = $this->userModel->where($credentials)
                                ->first();

        if (! $user)
        {
            $this->error = lang('Auth.invalidUser');
            return false;
        }

        // Now, try matching the passwords.
        $result = password_verify($password, $user->password_hash);

        if (! $result)
        {
            $this->error = lang('Auth.invalidPassword');
            return false;
        }

        // Check to see if the password needs to be rehashed.
        // This would be due to the hash algorithm or hash
        // cost changing since the last time that a user
        // logged in.
        if (password_needs_rehash($user->password_hash, PASSWORD_DEFAULT))
        {
            $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->save($user);
        }

        return $returnUser
            ? $user
            : true;
    }
}
