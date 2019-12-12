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
        parent::setUp();

        $this->user = $this->createUser();
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
	    $perm = $this->createPermission();
	    $model = new PermissionModel();

		$this->assertEmpty($this->user->getPermissions());

		$model->addPermissionToUser($perm->id, $this->user->id);

		$this->assertTrue(in_array($perm->name, $this->user->getPermissions()));
	}
}
