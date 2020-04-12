<?php

use Myth\Auth\Models\UserModel;
use ModuleTests\Support\AuthTestCase;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Authorization\FlatAuthorization;

class FlatAuthorizationTest extends AuthTestCase
{
    protected $refresh = true;

    /**
     * @var UserModel
     */
    protected $users;
    /**
     * @var GroupModel
     */
    protected $groups;
    /**
     * @var PermissionModel
     */
    protected $permissions;
    /**
     * @var FlatAuthorization
     */
    protected $auth;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = new UserModel();
        $this->groups = new GroupModel();
        $this->permissions = new PermissionModel();

        $this->auth = new FlatAuthorization($this->groups, $this->permissions);
        $this->auth->setUserModel($this->users);

        db_connect()->table('auth_groups_users')->truncate();
        cache()->clean();
    }

    public function testInGroupSingleId()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $this->assertFalse($this->auth->inGroup($group->id, $user->id));

        $this->groups->addUserToGroup($user->id, $group->id);

        $this->assertTrue($this->auth->inGroup($group->id, $user->id));
    }

    public function testInGroupSingleName()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $this->assertFalse($this->auth->inGroup($group->name, $user->id));

        $this->groups->addUserToGroup($user->id, $group->id);

        $this->assertTrue($this->auth->inGroup($group->name, $user->id));
    }

    public function testInGroupMultiId()
    {
        $user = $this->createUser();
        $group1 = $this->createGroup();
        $group2 = $this->createGroup();

        $groups = [$group1->id, $group2->id];

        $this->assertFalse($this->auth->inGroup($groups, $user->id));

        $this->groups->addUserToGroup($user->id, $group2->id);

        $this->assertTrue($this->auth->inGroup($groups, $user->id));
    }

    public function testInGroupMultiName()
    {
        $user = $this->createUser();
        $group1 = $this->createGroup();
        $group2 = $this->createGroup();

        $groups = [$group1->name, $group2->name];

        $this->assertFalse($this->auth->inGroup($groups, $user->id));

        $this->groups->addUserToGroup($user->id, $group2->id);

        $this->assertTrue($this->auth->inGroup($groups, $user->id));
    }

    public function testHasPermissionIdGroup()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $permission = $this->createPermission();
        $this->groups->addUserToGroup($user->id, $group->id);

        $this->assertFalse($this->auth->hasPermission($permission->id, $user->id));

        $this->groups->addPermissionToGroup($permission->id, $group->id);

        $this->assertTrue($this->auth->hasPermission($permission->id, $user->id));
    }

    public function testHasPermissionNameGroup()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $permission = $this->createPermission();
        $this->groups->addUserToGroup($user->id, $group->id);

        $this->assertFalse($this->auth->hasPermission($permission->name, $user->id));

        $this->groups->addPermissionToGroup($permission->id, $group->id);

        $this->assertTrue($this->auth->hasPermission($permission->name, $user->id));
    }

    public function testHasPermissionIdUser()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $permission = $this->createPermission();
        $this->groups->addUserToGroup($user->id, $group->id);

        $this->assertFalse($this->auth->hasPermission($permission->id, $user->id));

        $this->auth->addPermissionToUser($permission->id, $user->id);

        $this->assertTrue($this->auth->hasPermission($permission->id, $user->id));
    }

    public function testHasPermissionIdUserNoGroups()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();

        $this->assertFalse($this->auth->hasPermission($permission->id, $user->id));

        $this->auth->addPermissionToUser($permission->id, $user->id);

        $this->assertTrue($this->auth->hasPermission($permission->id, $user->id));
    }

    public function testAddUserToGroupId()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $this->dontSeeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $this->auth->addUserToGroup($user->id, $group->id);

        $this->seeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }

    public function testAddUserToGroupName()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $this->dontSeeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $this->auth->addUserToGroup($user->id, $group->name);

        $this->seeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }

    public function testRemoveUserFromGroupId()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $this->auth->removeUserFromGroup($user->id, $group->id);

        $this->dontSeeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }

    public function testRemoveUserFromGroupName()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $this->auth->removeUserFromGroup($user->id, $group->name);

        $this->dontSeeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }

    public function testAddPermissionToGroupId()
    {
        $group = $this->createGroup();
        $permission = $this->createPermission();

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->auth->addPermissionToGroup($permission->id, $group->id));

        $this->seeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testAddPermissionToGroupName()
    {
        $group = $this->createGroup();
        $permission = $this->createPermission();

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->auth->addPermissionToGroup($permission->name, $group->name));

        $this->seeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testRemovePermissionFromGroupId()
    {
        $group = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->auth->removePermissionFromGroup($permission->id, $group->id));

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testRemovePermissionFromGroupName()
    {
        $group = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->auth->removePermissionFromGroup($permission->name, $group->name));

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testAddPermissionToUser()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();

        $this->dontSeeInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->auth->addPermissionToUser($permission->id, $user->id));

        $this->seeInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testRemovePermissionFromUser()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);

        $this->auth->removePermissionFromUser($permission->id, $user->id);

        $this->dontSeeInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testDoesUserHavePermission()
    {
        $user = $this->createUser();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_users_permissions', [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ]);

        $this->assertTrue($this->auth->doesUserHavePermission($user->id, $permission->id));
    }

    public function testDoesUSerHavePermissionByGroupAssign()
    {
        $user = $this->createUser();
        $group1 = $this->createGroup();
        $group2 = $this->createGroup();
        $permission1 = $this->createPermission();
        $permission2 = $this->createPermission();

        // group1 has both permissions
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group1->id,
            'permission_id' => $permission1->id
        ]);
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group1->id,
            'permission_id' => $permission2->id
        ]);

        // group2 has only one permission
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group2->id,
            'permission_id' => $permission2->id
        ]);

        // user is assigned to proup2
        $this->hasInDatabase('auth_groups_users', [
            'group_id' => $group2->id,
            'user_id' => $user->id
        ]);

        // no permission for permission1
        $this->assertFalse($this->auth->doesUserHavePermission($user->id, $permission1->id));
        // but he has permission for permission2
        $this->assertTrue($this->auth->doesUserHavePermission($user->id, $permission2->id));
    }

    public function testGroupNotFound()
    {
        $this->assertNull($this->auth->group('some_group'));
    }

    public function testGroupWithId()
    {
        $group = $this->createGroup();

        $found = $this->auth->group($group->id);

        $this->assertEquals($group->id, $found->id);
        $this->assertEquals($group->name, $found->name);
        $this->assertEquals($group->description, $found->description);
    }

    public function testGroupWithName()
    {
        $group = $this->createGroup();

        $found = $this->auth->group($group->name);

        $this->assertEquals($group->id, $found->id);
        $this->assertEquals($group->name, $found->name);
        $this->assertEquals($group->description, $found->description);
    }

    public function testCreateGroupSuccess()
    {
        $result = $this->auth->createGroup('Group A', 'Description');

        $this->assertIsInt($result);

        $this->seeInDatabase('auth_groups', [
            'id' => $result,
            'name' => 'Group A',
            'description' => 'Description'
        ]);
    }

    public function testDeleteGroup()
    {
        $group = $this->createGroup();

        $this->seeInDatabase('auth_groups', [
            'id' => $group->id
        ]);

        $this->assertTrue($this->auth->deleteGroup($group->id));

        $this->dontSeeInDatabase('auth_groups', [
            'id' => $group->id
        ]);
    }

    public function testUpdateGroup()
    {
        $group = $this->createGroup();

        $this->assertTrue($this->auth->updateGroup($group->id, 'Group B', 'More Words'));

        $this->seeInDatabase('auth_groups', [
            'id' => $group->id,
            'name' => 'Group B',
            'description' => 'More Words'
        ]);
    }

    public function testPermissionNotFound()
    {
        $this->assertNull($this->auth->permission(1234));
    }

    public function testPermissionWithId()
    {
        $perm = $this->createPermission();

        $found = $this->auth->permission($perm->id);

        $this->assertEquals($perm->id, $found['id']);
        $this->assertEquals($perm->name, $found['name']);
        $this->assertEquals($perm->description, $found['description']);
    }

    public function testPermissionWithName()
    {
        $perm = $this->createPermission();

        $found = $this->auth->permission($perm->name);

        $this->assertEquals($perm->id, $found['id']);
        $this->assertEquals($perm->name, $found['name']);
        $this->assertEquals($perm->description, $found['description']);
    }

    public function testCreatePermissionSuccess()
    {
        $perm = $this->auth->createPermission('Perm A', 'Description');

        $this->assertIsInt($perm);

        $this->seeInDatabase('auth_permissions', [
            'id' => $perm,
            'name' => 'Perm A',
            'description' => 'Description'
        ]);
    }

    public function testDeletePermission()
    {
        $perm = $this->createPermission();

        $this->seeInDatabase('auth_permissions', [
            'id' => $perm->id
        ]);

        $this->auth->deletePermission($perm->id);

        $this->dontSeeInDatabase('auth_permissions', [
            'id' => $perm->id
        ]);
    }

    public function testUpdatePermission()
    {
        $perm = $this->createPermission();

        $this->assertTrue($this->auth->updatePermission($perm->id, 'Perm B', 'More Words'));

        $this->seeInDatabase('auth_permissions', [
            'id' => $perm->id,
            'name' => 'Perm B',
            'description' => 'More Words'
        ]);
    }

    public function testGroupPermissionsEmpty()
    {
        $group = $this->createGroup();

        $this->assertEquals([], $this->auth->groupPermissions($group->id));
    }

    public function testGroupPermissions()
    {
        $group = $this->createGroup();
        $perm = $this->createPermission();

        $this->auth->addPermissionToGroup($perm->id, $group->id);

        $found = $this->auth->groupPermissions($group->id);

        $this->assertTrue(isset($found[$perm->id]));
        $this->assertEquals((array)$perm, $found[$perm->id]);
    }
}
