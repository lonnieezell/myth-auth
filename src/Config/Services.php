<?php namespace Myth\Auth\Config;

use CodeIgniter\Model;
use Myth\Auth\Models\UserModel;

class Services extends \Config\Services
{
    public static function authentication(string $lib = 'local', Model $userModel=null, bool $getShared = true)
    {
        if ($getShared)
        {
            return self::getSharedInstance('authentication', $lib, $userModel);
        }

        $config = new Auth();

        $class = $config->authenticationLibs[$lib];

        $instance = new $class($config);

        if (empty($userModel))
        {
            $userModel = new UserModel();
        }

        return $instance
            ->setUserModel($userModel);
    }

    public static function authorization()
    {
        
    }

}
