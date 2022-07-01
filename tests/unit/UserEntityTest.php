<?php

use CodeIgniter\Config\Factories;
use Config\Services;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class UserEntityTest extends AuthTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        Services::reset();

        parent::setUp();

        // Don't worry about default groups for this...
        $config                   = new AuthConfig();
        $config->defaultUserGroup = 'Administrators';
        Factories::injectMock('config', 'Auth', $config);
    }

    public function testGetPermissionsNotSaved()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Users must be created before getting permissions.');

        (new User())->getPermissions();
    }

    public function testGetPermissionSuccess()
    {
        $user  = $this->createUser();
        $perm  = $this->createPermission();
        $model = new PermissionModel();

        $this->assertEmpty($user->getPermissions());

        $model->addPermissionToUser($perm->id, $user->id);

        $this->assertContains($perm->name, $user->getPermissions());
    }
}
