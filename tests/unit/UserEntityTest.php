<?php

use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Entities\User;
use ModuleTests\Support\AuthTestCase;

class UserEntityTest extends AuthTestCase
{
    /**
     * @var User
     */
    protected $user;

    public function setUp(): void
    {
        \Config\Services::reset();

        parent::setUp();

        // Don't worry about default groups for this...
        $config = new \Myth\Auth\Config\Auth();
        $config->defaultGroup = 'Administrators';
        \CodeIgniter\Config\Config::injectMock('Auth', $config);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Users must be created before getting permissions.
     */
    public function testGetPermissionsNotSaved()
    {
        $user = new User();

        $this->assertEmpty($user->getPermissions());
    }

	public function testGetPermissionSuccess()
	{
        $user = $this->createUser();
	    $perm = $this->createPermission();
	    $model = new PermissionModel();

		$this->assertEmpty($user->getPermissions());

		$model->addPermissionToUser($perm->id, $user->id);

		$this->assertTrue(in_array($perm->name, $user->getPermissions()));
	}
}
