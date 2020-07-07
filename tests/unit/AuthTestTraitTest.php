<?php

use Myth\Auth\Test\Fakers\PermissionFaker;
use Myth\Auth\Test\Fakers\UserFaker;
use Tests\Support\AuthTestCase;

class AuthTestTraitTest extends AuthTestCase
{
	use \Myth\Auth\Test\AuthTestTrait;

	public function testResetServicesResetsAuthentication()
	{
		$authentication = service('authentication');

		$user = fake(UserFaker::class);

		$authentication->login($user);
		$this->assertTrue($authentication->isLoggedIn());

		$this->resetAuthServices();

		$this->assertFalse(service('authentication')->check());
	}

	public function testResetServicesResetsAuthorization()
	{
		$authorization = service('authorization');
		$authorization->setUserModel(model(UserFaker::class));

		$this->resetAuthServices();

		$authorization = service('authorization');
		$model         = $this->getPrivateProperty($authorization, 'userModel');

		$this->assertNotInstanceOf(UserFaker::class, $model);
	}
}
