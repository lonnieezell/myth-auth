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
}
