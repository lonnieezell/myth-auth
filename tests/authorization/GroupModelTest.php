<?php

use Myth\Auth\Authorization\GroupModel;
use ModuleTests\Support\AuthTestCase;

class GroupModelTest extends AuthTestCase
{
    /**
     * @var GroupModel
     */
    protected $model;

    protected $refresh = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new GroupModel();
    }

    public function testAddUserToGroup()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $result = $this->model->addUserToGroup($user->id, $group->id);

        $this->assertInstanceOf(\CodeIgniter\Database\ResultInterface::class, $result);
        $this->seeInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
    }

    public function testRemoveUserFromGroup()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group2->id
        ]);

        $result = $this->model->removeUserFromGroup($user->id, $group->id);

        $this->assertInstanceOf(\CodeIgniter\Database\ResultInterface::class, $result);
        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);
        $this->seeInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group2->id
        ]);
    }

    public function testRemoveUserFromAllGroups()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group2->id
        ]);

        $result = $this->model->removeUserFromAllGroups($user->id);

        $this->assertInstanceOf(\CodeIgniter\Database\ResultInterface::class, $result);
        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);
        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group2->id
        ]);
    }

    public function testGetGroupsForUser()
    {
        $user = $this->createUser();
        $group = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id' => $user->id,
            'group_id' => $group2->id
        ]);

        $result = $this->model->getGroupsForUser($user->id);

        $this->assertEquals($result[0], [
            'group_id' => $group->id,
            'user_id' => $user->id,
            'name' => $group->name,
            'description' => $group->description
        ]);
        $this->assertEquals($result[1], [
            'group_id' => $group2->id,
            'user_id' => $user->id,
            'name' => $group2->name,
            'description' => $group2->description
        ]);
    }

    public function testAddPermissionToGroup()
    {
        $group = $this->createGroup();
        $permission = $this->createPermission();

        $this->model->addPermissionToGroup($permission->id, $group->id);

        $this->seeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testRemovePermissionFromGroup()
    {
        $group = $this->createGroup();
        $group2 = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group2->id,
            'permission_id' => $permission->id
        ]);

        $this->model->removePermissionFromGroup($permission->id, $group->id);

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
        $this->seeInDatabase('auth_groups_permissions', [
            'group_id' => $group2->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testRemovePermissionFromAllGroups()
    {
        $group = $this->createGroup();
        $group2 = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id' => $group2->id,
            'permission_id' => $permission->id
        ]);

        $this->model->removePermissionFromAllGroups($permission->id);

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group->id,
            'permission_id' => $permission->id
        ]);
        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id' => $group2->id,
            'permission_id' => $permission->id
        ]);
    }
}
