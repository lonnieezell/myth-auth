<?php namespace Myth\Auth\Authentication;

use CodeIgniter\Router\Exceptions\RedirectException;
use Myth\Auth\Entities\User;
use Myth\Auth\Exceptions\AuthException;
use Myth\Auth\Password;

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

        if (empty($this->user))
        {
            // Always record a login attempt, whether success or not.
            $ipAddress = service('request')->getIPAddress();
            $this->recordLoginAttempt($credentials['email'] ?? $credentials['username'], $ipAddress, $this->user->id ?? null, false);

            $this->user = null;
            return false;
        }

        if ($this->user->isBanned())
        {
            // Always record a login attempt, whether success or not.
            $ipAddress = service('request')->getIPAddress();
            $this->recordLoginAttempt($credentials['email'] ?? $credentials['username'], $ipAddress, $this->user->id ?? null, false);

            $this->error = lang('Auth.userIsBanned');

            $this->user = null;
            return false;
        }

        if (! $this->user->isActivated())
        {
            // Always record a login attempt, whether success or not.
            $ipAddress = service('request')->getIPAddress();
            $this->recordLoginAttempt($credentials['email'] ?? $credentials['username'], $ipAddress, $this->user->id ?? null, false);

            $param = http_build_query([
                'login' => urlencode($credentials['email'] ?? $credentials['username'])
            ]);

            $this->error = lang('Auth.notActivated') .' '. anchor(route_to('resend-activate-account').'?'.$param, lang('Auth.activationResend'));

            $this->user = null;
            return false;
        }

        return $this->login($this->user, $remember);
    }

    /**
     * Checks to see if the user is logged in or not.
     *
     * @return bool
     */
    public function check(): bool
    {
        if ($this->isLoggedIn())
        {
            // Do we need to force the user to reset their password?
            if ($this->user && $this->user->force_pass_reset)
            {
                throw new RedirectException(route_to('reset-password') .'?token='.$this->user->reset_hash);
            }

            return true;
        }

        // Check the remember me functionality.
        helper('cookie');
        $remember = get_cookie('remember');

        if (empty($remember))
        {
            return false;
        }

        [$selector, $validator] = explode(':', $remember);
        $validator = hash('sha256', $validator);

        $token = $this->loginModel->getRememberToken($selector);

        if (empty($token))
        {
            return false;
        }

        if (! hash_equals($token->hashedValidator, $validator))
        {
            return false;
        }

        // Yay! We were remembered!
        $user = $this->userModel->find($token->user_id);

        if (empty($user))
        {
            return false;
        }

        $this->login($user);

        // We only want our remember me tokens to be valid
        // for a single use.
        $this->refreshRemember($user->id, $selector);

        return true;
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
            $this->error = lang('Auth.badAttempt');
            return false;
        }

        // Now, try matching the passwords.
        if (! Password::verify($password, $user->password_hash))
        {
            $this->error = lang('Auth.invalidPassword');
            return false;
        }

        // Check to see if the password needs to be rehashed.
        // This would be due to the hash algorithm or hash
        // cost changing since the last time that a user
        // logged in.
        if (Password::needsRehash($user->password_hash, $this->config->hashAlgorithm))
        {
            $user->password = $password;
            $this->userModel->save($user);
        }

        return $returnUser
            ? $user
            : true;
    }

}
