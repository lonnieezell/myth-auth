<?php namespace Myth\Auth\Config;

use CodeIgniter\Model;
use Myth\Auth\Authorization\FlatAuthorization;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\LoginModel;
use Myth\Authorization\GroupModel;
use Myth\Authorization\PermissionModel;
use Myth\Auth\Authentication\Passwords\PasswordValidator;
use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function authentication(string $lib = 'local', Model $userModel=null, Model $loginModel=null, bool $getShared = true)
    {
        if ($getShared)
        {
            return self::getSharedInstance('authentication', $lib, $userModel, $loginModel);
        }

        $config = config(Auth::class);

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

    public static function authorization(Model $groupModel=null, Model $permissionModel=null, Model $userModel=null, bool $getShared = true)
    {
        if ($getShared)
        {
            return self::getSharedInstance('authorization', $groupModel, $permissionModel);
        }

        if (is_null($groupModel))
        {
            $groupModel = new GroupModel();
        }

        if (is_null($permissionModel))
        {
            $permissionModel = new PermissionModel();
        }

        $instance = new FlatAuthorization($groupModel, $permissionModel);

        if (is_null($userModel))
        {
            $userModel = new UserModel();
        }

        return $instance->setUserModel($userModel);
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
            $config = config(Auth::class);
        }

        return new PasswordValidator($config);
    }
}
