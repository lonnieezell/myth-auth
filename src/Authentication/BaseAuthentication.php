<?php

namespace Myth\Auth\Authentication;

use CodeIgniter\Events\Events;
use CodeIgniter\Model;
use Config\App;
use Config\Services
use Myth\Auth\Entities\User;
use Myth\Auth\Exceptions\AuthException;
use Myth\Auth\Exceptions\UserNotFoundException;

class BaseAuthentication
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Config\Auth
     */
    protected $config;

    /**
     * @var CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;

    /**
     * @var CodeIgniter\HTTP\Response
     */
    protected $response;

    /**
     * @var CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var Model
     */
    protected $userModel;

    /**
     * @var Model
     */
    protected $loginModel;

    //--------------------------------------------------------------------

    public function __construct($config)
    {
        $this->config = $config;

        $this->request = Services::request();
        $this->response = Services::response();
        $this->session = Services::session();
    }

    //--------------------------------------------------------------------

    /**
     * Logs a user into the system.
     * NOTE: does not perform validation. All validation should
     * be done prior to using the login method.
     *
     * @param boolean $remember
     *
     * @return boolean
     * @throws \Exception
     */
    public function login(bool $remember = false): bool
    {
        // Always record a login attempt
        $this->recordLoginAttempt();

        // Regenerate the session ID to help protect against session fixation
        if (ENVIRONMENT !== 'testing')
        {
            $this->session->regenerate();
        }

        // Let the session know we're logged in
        $this->session->set('logged_in', $this->user->id);

        // When logged in, ensure cache control headers are in place
        $this->response->noCache();

        if ($remember && $this->config->allowRemembering)
        {
            $this->rememberUser($this->user->id);
        }

        // We'll give a 20% chance to need to do a purge since we
        // don't need to purge THAT often, it's just a maintenance issue.
        // to keep the table from getting out of control.
        if (mt_rand(1, 100) < 20)
        {
            $this->loginModel->purgeOldRememberTokens();
        }

		// trigger login event, in case anyone cares
		Events::trigger('login', $this->user);

        return true;
    }

    //--------------------------------------------------------------------

    /**
     * Checks to see if the user is logged in.
     *
     * @return boolean
     */
    public function isLoggedIn(): bool
    {
        // On the off chance
        if ($this->user instanceof User)
        {
            return true;
        }

        if ($this->session->has('logged_in'))
        {
            // Store our current user object
            $this->user = $this->userModel->find($this->session->get('logged_in'));

            return $this->user instanceof User;
        }

        return false;
    }

    //--------------------------------------------------------------------

    /**
     * Logs a user into the system by their ID.
     *
     * @param int     $id
     * @param boolean $remember
     */
    public function loginByID(int $id, bool $remember = false)
    {
        $this->user = $this->retrieveUser(['id' => $id]);

        if (empty($this->user))
        {
            throw UserNotFoundException::forUserID($id);
        }

        return $this->login($remember);
    }

    //--------------------------------------------------------------------

    /**
     * Logs a user out of the system.
     *
     * @return void
     */
    public function logout(): void
    {
        // Destroy the session data - but ensure a session is still
        // available for flash messages, etc.
        if ($this->session->get())
        {
            foreach ($this->session->get() as $key => $value)
            {
                $this->session->remove($key);
            }
        }

        // Regenerate the session ID for a touch of added safety.
        $this->session->regenerate(true);

        // Remove the cookie
        $this->response->deleteCookie("remember");

        // Take care of any remember me functionality
        $this->loginModel->purgeRememberTokens($user->id);

        // Trigger logout event
        Events::trigger('logout', $this->user);

        $this->user = null;
    }

    //--------------------------------------------------------------------

    /**
     * Record a login attempt
     *
     * @param string  $email
     * @param boolean $success
     *
     * @return mixed
     */
    public function recordLoginAttempt(string $email = null, bool $success = true)
    {
        return $this->loginModel->insert([
            'email' => $email ?? $this->user->email,
            'user_id' => $this->user->id,
            'ip_address' => $this->request->getIPAddress(),
            'date' => date('Y-m-d H:i:s'),
            'success' => (int) $success
        ]);
    }

    //--------------------------------------------------------------------

    /**
     * Generates a timing-attack safe remember me token
     * and stores the necessary info in the db and a cookie.
     *
     * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
     *
     * @param int $id
     *
     * @return void
     * @throws \Exception
     */
    public function rememberUser(int $id): void
    {
        $selector  = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(20));
        $expires   = date('Y-m-d H:i:s', time() + $this->config->rememberLength);

        // Store it in the database
        $this->loginModel->rememberUser($id, $selector, hash('sha256', $validator), $expires);

        // Save it to the user's browser in a cookie.
        $appConfig = new App();

        // Create the cookie
        $this->response->setCookie(
            'remember',                    // Cookie Name
            $selector.':'.$validator,      // Value
            $this->config->rememberLength, // # Seconds until it expires
            $appConfig->cookieDomain,
            $appConfig->cookiePath,
            $appConfig->cookiePrefix,
            $appConfig->cookieHTTPOnly,    // Only send over HTTPS?
            true                           // Hide from Javascript?
        );
    }

    //--------------------------------------------------------------------

    /**
     * Sets a new validator for this user/selector. This allows
     * a one-time use of remember-me tokens, but still allows
     * a user to be remembered on multiple browsers/devices.
     *
     * @param int    $id
     * @param string $selector
     *
     * @return void
     */
    public function refreshRemember(int $id, string $selector): void
    {
        $existing = $this->loginModel->getRememberToken($selector);

        // No matching record? Shouldn't happen, but remember the user now.
        if (empty($existing))
        {
            return $this->rememberUser($id);
        }
        
        $validator = bin2hex(random_bytes(20));

        // Update the validator in the database and the session
        $this->loginModel->updateRememberValidator($selector, $validator);

        // Save it to the user's browser in a cookie.
        $appConfig = new App();

        // Create the cookie
        $this->response->setCookie(
            'remember',                    // Cookie Name
            $selector.':'.$validator,      // Value
            $this->config->rememberLength, // # Seconds until it expires
            $appConfig->cookieDomain,
            $appConfig->cookiePath,
            $appConfig->cookiePrefix,
            $appConfig->cookieHTTPOnly,    // Only send over HTTPS?
            true                           // Hide from Javascript?
        );
    }

    //--------------------------------------------------------------------

    /**
     * Returns the User instance for the current logged in user.
     *
     * @return \Myth\Auth\Entities\User|null
     */
    public function user()
    {
        return $this->user;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the User ID for the current logged in user.
     *
     * @return int|null
     */
    public function id(): ?int
    {
        return $this->user->id ?? null;
    }

    //--------------------------------------------------------------------

    /**
     * Grabs the current user from the database.
     *
     * @param array $condition
     *
     * @return mixed
     */
    public function retrieveUser(array $condition)
    {
        if (! $this->userModel instanceof Model)
        {
            throw AuthException::forInvalidModel('User');
        }

        return $this->userModel->where($condition)->first();
    }

    //--------------------------------------------------------------------

    /**
     * Returns the current error, if any.
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }

    //--------------------------------------------------------------------

    /**
     * Whether to continue instead of throwing exceptions,
     * as defined in config.
     *
     * @return boolean
     */
    public function silent(): bool
    {
        return $this->config->silent;
    }

    //--------------------------------------------------------------------
    // Model Setters
    //--------------------------------------------------------------------

    /**
     * Sets the model that should be used to work with
     * user accounts.
     *
     * @param \CodeIgniter\Model $model
     *
     * @return $this
     */
    public function setUserModel(Model $model)
    {
        $this->userModel = $model;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Sets the model that should be used to record
     * login attempts (but failed and successful).
     *
     * @param Model $model
     *
     * @return $this
     */
    public function setLoginModel(Model $model)
    {
        $this->loginModel = $model;

        return $this;
    }
}

