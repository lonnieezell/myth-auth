<?php namespace Myth\Auth\Config;

use CodeIgniter\Model;
use Myth\Auth\Authentication\Passwords\PasswordValidator;
use Myth\Auth\Models\LoginModel;
use Myth\Auth\Models\UserModel;

class Services extends \Config\Services
{
    public static function authentication(string $lib = 'local', Model $userModel=null, Model $loginModel=null, bool $getShared = true)
    {
        if ($getShared)
        {
            return self::getSharedInstance('authentication', $lib, $userModel, $loginModel);
        }

        $config = new Auth();

        $class = $config->authenticationLibs[$lib];

        $instance = new $class($config);

        if (empty($userModel))
        {
            $userModel = new UserModel();
        }

        if (empty($loginModel))
        {
            $loginModel = new LoginModel();
        }

        return $instance
            ->setUserModel($userModel)
            ->setLoginModel($loginModel);
    }

    public static function authorization()
    {
        
    }

    /**
     * Returns an instance of the password validator.
     *
     * @param null $config
     * @param bool $getShared
     *
     * @return mixed|PasswordValidator
     */
    public static function passwords($config = null, bool $getShared = true)
    {
        if ($getShared)
        {
            return self::getSharedInstance('passwords', $config);
        }

        if (empty($config))
        {
            $config = new Auth();
        }

        return new PasswordValidator($config);
    }
}
