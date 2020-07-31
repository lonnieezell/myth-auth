<?php

namespace Myth\Auth\Authentication;

use CodeIgniter\Router\Exceptions\RedirectException;
use Config\Services;
use Myth\Auth\Exceptions\AuthException;

class LocalAuthenticator extends BaseAuthentication implements AuthenticatorInterface
{
    /**
     * Attempts to validate the credential and log a user in.
     *
     * @param array   $credential
     * @param boolean $remember   Should we remember the user (if enabled)
     *
     * @return boolean
     */
    public function attempt(array $credential, bool $remember = false): bool
    {
        if (! $this->validate($credential))
        {
            return false;
        }
        
        if (empty($this->user))
        {
            return false;
        }

        if ($this->user->isBanned())
        {
            // Always record a login attempt, whether success or not.
            $this->recordLoginAttempt($credential['email'] ?? $credential['username'], false);
            
            // Set error
            $this->error = lang('Auth.userIsBanned');
            
            // Clear user
            $this->user = null;
            
            return false;
        }

        if (! $this->user->isActivated())
        {
            // Always record a login attempt, whether success or not.
            $this->recordLoginAttempt(false);

            // Set error
            $this->error = lang('Auth.notActivated') . ' ' . anchor(route_to('resend-activate-account') . '?' . http_build_query([
                'login' => urlencode($credentials['email'] ?? $credentials['username'])
            ]), lang('Auth.activationResend'));
            
            // Clear user
            $this->user = null;
            
            return false;
        }

        return $this->login($remember);
    }

    //--------------------------------------------------------------------

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
                throw new RedirectException(route_to('reset-password') . '?token=' . $this->user->reset_hash);
            }

            return true;
        }

        // Check the remember me functionality.
        $remember = $this->request->getCookie('remember');

        if (empty($remember))
        {
            return false;
        }

        [$selector, $validator] = explode(':', $remember);

        $token = $this->loginModel->getRememberToken($selector);

        if (empty($token))
        {
            return false;
        }

        if (! hash_equals($token->hashedValidator, hash('sha256', $validator)))
        {
            return false;
        }

        // Get remembered user.
        $this->user = $this->userModel->find($token->user_id);

        if (empty($this->user))
        {
            return false;
        }
        
        // User were remembered, time for login.
        $this->login();

        // Set remember me tokens to be valid for a single use.
        $this->refreshRemember($this->user->id, $selector);

        return true;
    }

    //--------------------------------------------------------------------

    /**
     * Checks the user's credentials to see if they could authenticate.
     * Unlike `attempt()`, will not log the user into the system.
     *
     * @param array $credential
     *
     * @return boolean
     */
    public function validate(array $credential): bool
    {
        // Can't validate without password.
        if (empty($credential['password']) || count($credential) !== 2)
        {
            return false;
        }

        // Only allowed 1 additional credential other than password
        $password = $credential['password'];
        unset($credential['password']);

        if (count($credential) > 1)
        {
            throw AuthException::forTooManyCredentials();
        }

        // Ensure that the fields are allowed validation fields
        if (! in_array(key($credential), $this->config->validFields))
        {
            throw AuthException::forInvalidFields(key($credential));
        }

        // Can we find a user with those credentials?
        $user = $this->userModel->where($credential)->first();

        if (! $user)
        {
            $this->error = lang('Auth.badAttempt');
            return false;
        }

        // Now, try matching the passwords.
        $result = password_verify(base64_encode(
            hash('sha384', $password, true)
        ), $user->password_hash);

        if (! $result)
        {
            $this->error = lang('Auth.invalidPassword');
            return false;
        }

        // Check to see if the password needs to be rehashed.
        // This would be due to the hash algorithm or hash
        // cost changing since the last time that a user
        // logged in.
        if (password_needs_rehash($user->password_hash, $this->config->hashAlgorithm))
        {
            $user->password = $password;
            $this->userModel->save($user);
        }

        $this->user = $user;

        return true;
    }
}
