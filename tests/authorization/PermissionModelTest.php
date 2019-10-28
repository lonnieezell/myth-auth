<?php

use ModuleTests\Support\AuthTestCase;
use Myth\Auth\Authorization\PermissionModel;

class PermissionModelTest extends AuthTestCase
{
    /**
     * @var PermissionModel
     */
    protected $model;

    protected $refresh = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new PermissionModel();
    }

    public function testDoesUserHavePermissionsNope()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();

        $this->assertFalse($this->model->doesUserHavePermission($user->id, $permission->id));
    }

    public function testDoesUserHavePermissionUserLevel()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->model->doesUserHavePermission($user->id, $permission->id));
    }

    public function testDoesUserHavePermissionGroupLevel()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->model->doesUserHavePermission($user->id, $permission->id));
    }
}
