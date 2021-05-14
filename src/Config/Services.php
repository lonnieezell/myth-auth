<?php namespace Myth\Auth\Config;

use CodeIgniter\Model;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Authorization\FlatAuthorization;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\LoginModel;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Authentication\Activators\ActivatorInterface;
use Myth\Auth\Authentication\Activators\UserActivator;
use Myth\Auth\Authentication\Passwords\PasswordValidator;
use Myth\Auth\Authentication\Resetters\EmailResetter;
use Myth\Auth\Authentication\Resetters\ResetterInterface;
use Config\Services as BaseService;

class Services extends BaseService
{
	public static function authentication(string $lib = 'local', Model $userModel = null, Model $loginModel = null, bool $getShared = true)
	{
		if ($getShared)
		{
			return self::getSharedInstance('authentication', $lib, $userModel, $loginModel);
		}

		$userModel  = $userModel ?? model(UserModel::class);
		$loginModel = $loginModel ?? model(LoginModel::class);

		/** @var AuthConfig $config */
		$config   = config('Auth');
		$class	  = $config->authenticationLibs[$lib];
		$instance = new $class($config);

		return $instance
			->setUserModel($userModel)
			->setLoginModel($loginModel);
	}

	public static function authorization(Model $groupModel = null, Model $permissionModel = null, Model $userModel = null, bool $getShared = true)
	{
		if ($getShared)
		{
			return self::getSharedInstance('authorization', $groupModel, $permissionModel, $userModel);
		}

		$groupModel	     = $groupModel ?? model(GroupModel::class);
		$permissionModel = $permissionModel ?? model(PermissionModel::class);
		$userModel	     = $userModel ?? model(UserModel::class);

		$instance = new FlatAuthorization($groupModel, $permissionModel);

		return $instance->setUserModel($userModel);
	}

	/**
	 * Returns an instance of the PasswordValidator.
	 *
	 * @param AuthConfig|null $config
	 * @param bool      $getShared
	 *
	 * @return ValidatorInterface
	 */
	public static function passwords(AuthConfig $config = null, bool $getShared = true): PasswordValidator
	{
		if ($getShared)
		{
			return self::getSharedInstance('passwords', $config);
		}

		return new PasswordValidator($config ?? config(AuthConfig::class));
	}

	/**
	 * Returns an instance of the Activator.
	 *
	 * @param AuthConfig|null $config
	 * @param bool      $getShared
	 *
	 * @return ActivatorInterface
	 */
	public static function activator(AuthConfig $config = null, bool $getShared = true): ActivatorInterface
	{
		if ($getShared)
		{
			return self::getSharedInstance('activator', $config);
		}

		$config = $config ?? config(AuthConfig::class);
		$class	= $config->requireActivation ?? UserActivator::class;

		return new $class($config);
	}

	/**
	 * Returns an instance of the Resetter.
	 *
	 * @param AuthConfig|null $config
	 * @param bool      $getShared
	 *
	 * @return ResetterInterface
	 */
	public static function resetter(AuthConfig $config = null, bool $getShared = true): ResetterInterface
	{
		if ($getShared)
		{
			return self::getSharedInstance('resetter', $config);
		}

		$config = $config ?? config(AuthConfig::class);
		$class	= $config->activeResetter ?? EmailResetter::class;

		return new $class($config);
	}
}
