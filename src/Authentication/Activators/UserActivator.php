<?php namespace Myth\Auth\Authentication\Activators;

use Myth\Auth\Config\Auth;
use Myth\Auth\Entities\User;

class UserActivator
{
    /**
     * @var Auth
     */
    protected $config;

    protected $error;

    public function __construct(Auth $config)
    {
        $this->config = $config;
    }

    /**
     * Sends activation message to the user via specified class
     * in `$requireActivation` setting in Config\Auth.php.
     *
     * @param User $user
     *
     * @return bool
     */
    public function send(User $user = null): bool
    {
        if ($this->config->requireActivation === false)
        {
            return true;
        }

        $className = $this->config->requireActivation;

        $class = new $className();
        $class->setConfig($this->config);

        if ($class->send($user) === false)
        {
            log_message('error', "Failed to send activation messaage to: {$user->email}");
            $this->error = $class->error();

            return false;
        }

        return true;
    }

    /**
     * Returns the current error.
     *
     * @return mixed
     */
    public function error()
    {
        return $this->error;
    }

}
