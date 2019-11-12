<?php

use Myth\Auth\Entities\User;
use ModuleTests\Support\AuthTestCase;

class UserTest extends AuthTestCase
{
    public function testGetPermissionsThroughUser()
    {
        $user = $this->createUser();
        $permission1 = $this->createPermission(['name' => 'first']);
        $permission2 = $this->createPermission(['name' => 'second']);

        $this->hasInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission1->id
        ]);
        $this->hasInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission2->id
        ]);

        $expected = [
            $permission1->id => $permission1->name,
            $permission2->id => $permission2->name,
        ];

        $this->assertEquals($expected, $user->permissions);
    }

    public function testGetPermissionsThroughGroup()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $permission = $this->createPermission(['name' => 'first']);

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);

        $expected = [
            $permission->id => $permission->name,
        ];

        $this->assertEquals($expected, $user->permissions);
    }

    public function testCan()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();
        $this->hasInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($user->can($permission->name));
        $this->assertFalse($user->can('jump for joy'));
    }
}
