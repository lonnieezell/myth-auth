<?php

use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Test\Fakers\UserFaker;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class GroupModelTest extends AuthTestCase
{
    protected GroupModel $model;
    protected $refresh = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new GroupModel();
    }

    public function testAddUserToGroup()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $result = $this->model->addUserToGroup($user->id, $group->id);

        $this->assertTrue($result);
        $this->seeInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
    }

    public function testRemoveUserFromGroup()
    {
        $user   = $this->createUser();
        $group  = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group2->id,
        ]);

        $result = $this->model->removeUserFromGroup($user->id, $group->id);

        $this->assertTrue($result);
        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
        $this->seeInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group2->id,
        ]);
    }

    public function testRemoveUserFromAllGroups()
    {
        $user   = $this->createUser();
        $group  = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group2->id,
        ]);

        $result = $this->model->removeUserFromAllGroups($user->id);

        $this->assertTrue($result);
        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group2->id,
        ]);
    }

    public function testGetGroupsForUser()
    {
        $user   = $this->createUser();
        $group  = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group2->id,
        ]);

        $result = $this->model->getGroupsForUser($user->id);

        $this->assertSame($result[0], [
            'group_id'    => $group->id,
            'user_id'     => $user->id,
            'name'        => $group->name,
            'description' => $group->description,
        ]);
        $this->assertSame($result[1], [
            'group_id'    => $group2->id,
            'user_id'     => $user->id,
            'name'        => $group2->name,
            'description' => $group2->description,
        ]);
    }

    public function testGetGroupsForUserFromCache()
    {
        $user   = $this->createUser();
        $group1 = $this->createGroup();
        $group2 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group1->id,
        ]);

        $cacheGroups = [
            [
                'group_id'    => $group2->id,
                'user_id'     => $user->id,
                'name'        => 'notemptygroup',
                'description' => 'This group can only be loaded from cache.',
            ],
        ];
        cache()->save("{$user->id}_groups", $cacheGroups, 300);

        $result = $this->model->getGroupsForUser($user->id);

        $this->assertSame($result, $cacheGroups);
    }

    public function testGetEmptyGroupsForUserFromCache()
    {
        $user   = $this->createUser();
        $group1 = $this->createGroup();

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group1->id,
        ]);

        $cacheGroups = [];
        cache()->save("{$user->id}_groups", $cacheGroups, 300);

        $result = $this->model->getGroupsForUser($user->id);

        $this->assertSame($result, $cacheGroups);
    }

    public function testGetUsersForGroup()
    {
        $group = $this->createGroup();
        $user1 = fake(UserFaker::class);
        $user2 = fake(UserFaker::class);

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user1->id,
            'group_id' => $group->id,
        ]);
        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user2->id,
            'group_id' => $group->id,
        ]);

        $result = $this->model->getUsersForGroup($group->id);

        $this->assertSame($user1->id, $result[0]['id']);
        $this->assertSame($user2->id, $result[1]['id']);
    }

    public function testGetUsersForGroupFromCache()
    {
        $group = $this->createGroup();
        $user1 = fake(UserFaker::class);
        $user2 = fake(UserFaker::class);

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user1->id,
            'group_id' => $group->id,
        ]);

        $cacheUsers = [
            'group_id' => $group->id,
            'user_id'  => $user2->id,
            'email'    => 'gonnaSkip@theOtherProperties.lazy',
        ];
        cache()->save("{$group->id}_users", $cacheUsers, 300);

        $result = $this->model->getUsersForGroup($group->id);

        $this->assertSame($result, $cacheUsers);
    }

    public function testGetEmptyUsersForGroupFromCache()
    {
        $group = $this->createGroup();
        $user  = fake(UserFaker::class);

        $this->hasInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);

        $cacheUsers = [];
        cache()->save("{$group->id}_users", $cacheUsers, 300);

        $result = $this->model->getUsersForGroup($group->id);

        $this->assertSame($result, $cacheUsers);
    }

    public function testAddPermissionToGroup()
    {
        $group      = $this->createGroup();
        $permission = $this->createPermission();

        $this->model->addPermissionToGroup($permission->id, $group->id);

        $this->seeInDatabase('auth_groups_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function testRemovePermissionFromGroup()
    {
        $group      = $this->createGroup();
        $group2     = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission->id,
        ]);
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id'      => $group2->id,
            'permission_id' => $permission->id,
        ]);

        $this->model->removePermissionFromGroup($permission->id, $group->id);

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission->id,
        ]);
        $this->seeInDatabase('auth_groups_permissions', [
            'group_id'      => $group2->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function testRemovePermissionFromAllGroups()
    {
        $group      = $this->createGroup();
        $group2     = $this->createGroup();
        $permission = $this->createPermission();

        $this->hasInDatabase('auth_groups_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission->id,
        ]);
        $this->hasInDatabase('auth_groups_permissions', [
            'group_id'      => $group2->id,
            'permission_id' => $permission->id,
        ]);

        $this->model->removePermissionFromAllGroups($permission->id);

        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission->id,
        ]);
        $this->dontSeeInDatabase('auth_groups_permissions', [
            'group_id'      => $group2->id,
            'permission_id' => $permission->id,
        ]);
    }
}
