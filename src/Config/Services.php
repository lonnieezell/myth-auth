<?php namespace Myth\Auth\Config;

use Myth\Auth\Models\UserModel;

class Services extends \Config\Services
{
    public static function authentication(string $lib = 'local', bool $getShared = true)
    {
        if ($getShared)
        {
            return self::getSharedInstance('authentication', $lib);
        }

        $config = new Auth();

        $class = $config->authenticationLibs[$lib];

        $instance = new $class();

        return $instance
            ->setUserModel(new UserModel());
    }

    public static function authorization()
    {
        
    }

}
