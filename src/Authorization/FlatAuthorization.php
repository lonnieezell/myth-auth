<?php

namespace Myth\Auth\Authorization;

use CodeIgniter\Events\Events;
use CodeIgniter\Model;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class FlatAuthorization implements AuthorizeInterface
{
    /**
     * @var array|string|null
     */
    protected $error;

    /**
     * The group model to use. Usually the class noted
     * below (or an extension thereof) but can be any
     * compatible CodeIgniter Model.
     *
     * @var GroupModel
     */
    protected $groupModel;

    /**
     * The group model to use. Usually the class noted
     * below (or an extension thereof) but can be any
     * compatible CodeIgniter Model.
     *
     * @var PermissionModel
     */
    protected $permissionModel;

    /**
     * The group model to use. Usually the class noted
     * below (or an extension thereof) but can be any
     * compatible CodeIgniter Model.
     *
     * @var UserModel
     */
    protected $userModel;

    /**
     * Stores the models.
     *
     * @param GroupModel      $groupModel
     * @param PermissionModel $permissionModel
     *
     * @return array|string|null
     */
    public function __construct(Model $groupModel, Model $permissionModel)
    {
        $this->groupModel      = $groupModel;
        $this->permissionModel = $permissionModel;
    }

    /**
     * Returns any error(s) from the last call.
     *
     * @return array|string|null
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Allows the consuming application to pass in a reference to the
     * model that should be used.
     *
     * @param UserModel $model
     *
     * @return mixed
     */
    public function setUserModel(Model $model)
    {
        $this->userModel = $model;

        return $this;
    }

    // --------------------------------------------------------------------
    // Actions
    // --------------------------------------------------------------------

    /**
     * Checks to see if a user is in a group.
     *
     * Groups can be either a string, with the name of the group, an INT
     * with the ID of the group, or an array of strings/ids that the
     * user must belong to ONE of. (It's an OR check not an AND check)
     *
     * @param mixed $groups
     *
     * @return bool
     */
    public function inGroup($groups, int $userId)
    {
        if ($userId === 0) {
            return false;
        }

        if (! is_array($groups)) {
            $groups = [$groups];
        }

        $userGroups = $this->groupModel->getGroupsForUser((int) $userId);

        if (empty($userGroups)) {
            return false;
        }

        foreach ($groups as $group) {
            if (is_numeric($group)) {
                $ids = array_column($userGroups, 'group_id');
                if (in_array($group, $ids, true)) {
                    return true;
                }
            } elseif (is_string($group)) {
                $names = array_column($userGroups, 'name');

                if (in_array($group, $names, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Checks a user's groups to see if they have the specified permission.
     *
     * @param int|string $permission Permission ID or name
     *
     * @return mixed
     */
    public function hasPermission($permission, int $userId)
    {
        // @phpstan-ignore-next-line
        if (empty($permission) || (! is_string($permission) && ! is_numeric($permission))) {
            return null;
        }

        if (empty($userId) || ! is_numeric($userId)) {
            return null;
        }

        // Get the Permission ID
        $permissionId = $this->getPermissionID($permission);

        if (! is_numeric($permissionId)) {
            return false;
        }

        // First check the permission model. If that exists, then we're golden.
        if ($this->permissionModel->doesUserHavePermission($userId, $permissionId)) {
            return true;
        }

        // Still here? Then we have one last check to make - any user private permissions.
        return $this->doesUserHavePermission($userId, $permissionId);
    }

    /**
     * Makes a member a part of a group.
     *
     * @param mixed $group Either ID or name, fails on anything else
     *
     * @return bool|null
     */
    public function addUserToGroup(int $userid, $group)
    {
        if (empty($userid) || ! is_numeric($userid)) {
            return null;
        }

        if (empty($group) || (! is_numeric($group) && ! is_string($group))) {
            return null;
        }

        $groupId = $this->getGroupID($group);

        if (! Events::trigger('beforeAddUserToGroup', $userid, $groupId)) {
            return false;
        }

        // Group ID
        if (! is_numeric($groupId)) {
            return null;
        }

        if (! $this->groupModel->addUserToGroup($userid, $groupId)) {
            $this->error = $this->groupModel->errors();

            return false;
        }

        Events::trigger('didAddUserToGroup', $userid, $groupId);

        return true;
    }

    /**
     * Removes a single user from a group.
     *
     * @param mixed $group
     *
     * @return mixed
     */
    public function removeUserFromGroup(int $userId, $group)
    {
        if (empty($userId) || ! is_numeric($userId)) {
            return null;
        }

        if (empty($group) || (! is_numeric($group) && ! is_string($group))) {
            return null;
        }

        $groupId = $this->getGroupID($group);

        if (! Events::trigger('beforeRemoveUserFromGroup', $userId, $groupId)) {
            return false;
        }

        // Group ID
        if (! is_numeric($groupId)) {
            return false;
        }

        if (! $this->groupModel->removeUserFromGroup($userId, $groupId)) {
            $this->error = $this->groupModel->errors();

            return false;
        }

        Events::trigger('didRemoveUserFromGroup', $userId, $groupId);

        return true;
    }

    /**
     * Adds a single permission to a single group.
     *
     * @param int|string $permission
     * @param int|string $group
     *
     * @return mixed
     */
    public function addPermissionToGroup($permission, $group)
    {
        $permissionId = $this->getPermissionID($permission);
        $groupId      = $this->getGroupID($group);

        // Permission ID
        if (! is_numeric($permissionId)) {
            return false;
        }

        // Group ID
        if (! is_numeric($groupId)) {
            return false;
        }

        // Remove it!
        if (! $this->groupModel->addPermissionToGroup($permissionId, $groupId)) {
            $this->error = $this->groupModel->errors();

            return false;
        }

        return true;
    }

    /**
     * Removes a single permission from a group.
     *
     * @param int|string $permission
     * @param int|string $group
     *
     * @return mixed
     */
    public function removePermissionFromGroup($permission, $group)
    {
        $permissionId = $this->getPermissionID($permission);
        $groupId      = $this->getGroupID($group);

        // Permission ID
        if (! is_numeric($permissionId)) {
            return false;
        }

        // Group ID
        if (! is_numeric($groupId)) {
            return false;
        }

        // Remove it!
        if (! $this->groupModel->removePermissionFromGroup($permissionId, $groupId)) {
            $this->error = $this->groupModel->errors();

            return false;
        }

        return true;
    }

    /**
     * Assigns a single permission to a user, irregardless of permissions
     * assigned by roles. This is saved to the user's meta information.
     *
     * @param int|string $permission
     *
     * @return bool|null
     */
    public function addPermissionToUser($permission, int $userId)
    {
        $permissionId = $this->getPermissionID($permission);

        if (! is_numeric($permissionId)) {
            return null;
        }

        if (! Events::trigger('beforeAddPermissionToUser', $userId, $permissionId)) {
            return false;
        }

        $user = $this->userModel->find($userId);

        if (! $user) {
            $this->error = lang('Auth.userNotFound', [$userId]);

            return false;
        }

        /** @var User $user */
        $permissions = $user->getPermissions();

        if (! in_array($permissionId, $permissions, true)) {
            $this->permissionModel->addPermissionToUser($permissionId, $user->id);
        }

        Events::trigger('didAddPermissionToUser', $userId, $permissionId);

        return true;
    }

    /**
     * Removes a single permission from a user. Only applies to permissions
     * that have been assigned with addPermissionToUser, not to permissions
     * inherited based on groups they belong to.
     *
     * @param int|string $permission
     *
     * @return bool|mixed|null
     */
    public function removePermissionFromUser($permission, int $userId)
    {
        $permissionId = $this->getPermissionID($permission);

        if (! is_numeric($permissionId)) {
            return false;
        }

        if (empty($userId) || ! is_numeric($userId)) {
            return null;
        }

        $userId = (int) $userId;

        if (! Events::trigger('beforeRemovePermissionFromUser', $userId, $permissionId)) {
            return false;
        }

        return $this->permissionModel->removePermissionFromUser($permissionId, $userId);
    }

    /**
     * Checks to see if a user has private permission assigned to it.
     *
     * @param int|string $userId
     * @param int|string $permission
     *
     * @return bool|null
     */
    public function doesUserHavePermission($userId, $permission)
    {
        $permissionId = $this->getPermissionID($permission);

        if (! is_numeric($permissionId)) {
            return false;
        }

        if (empty($userId) || ! is_numeric($userId)) {
            return null;
        }

        return $this->permissionModel->doesUserHavePermission($userId, $permissionId);
    }

    // --------------------------------------------------------------------
    // Groups
    // --------------------------------------------------------------------

    /**
     * Grabs the details about a single group.
     *
     * @param int|string $group
     *
     * @return object|null
     */
    public function group($group)
    {
        if (is_numeric($group)) {
            return $this->groupModel->find((int) $group);
        }

        return $this->groupModel->where('name', $group)->first();
    }

    /**
     * Grabs an array of all groups.
     *
     * @return array of objects
     */
    public function groups()
    {
        return $this->groupModel
            ->orderBy('name', 'asc')
            ->findAll();
    }

    /**
     * @return mixed
     */
    public function createGroup(string $name, string $description = '')
    {
        $data = [
            'name'        => $name,
            'description' => $description,
        ];

        $validation = service('validation', null, false);
        $validation->setRules([
            'name'        => 'required|max_length[255]|is_unique[auth_groups.name]',
            'description' => 'max_length[255]',
        ]);

        if (! $validation->run($data)) {
            $this->error = $validation->getErrors();

            return false;
        }

        $id = $this->groupModel->skipValidation()->insert($data);

        if (is_numeric($id)) {
            return (int) $id;
        }

        $this->error = $this->groupModel->errors();

        return false;
    }

    /**
     * Deletes a single group.
     *
     * @return bool
     */
    public function deleteGroup(int $groupId)
    {
        if (! $this->groupModel->delete($groupId)) {
            $this->error = $this->groupModel->errors();

            return false;
        }

        return true;
    }

    /**
     * Updates a single group's information.
     *
     * @return mixed
     */
    public function updateGroup(int $id, string $name, string $description = '')
    {
        $data = [
            'name' => $name,
        ];

        if (! empty($description)) {
            $data['description'] = $description;
        }

        if (! $this->groupModel->update($id, $data)) {
            $this->error = $this->groupModel->errors();

            return false;
        }

        return true;
    }

    /**
     * Given a group, will return the group ID. The group can be either
     * the ID or the name of the group.
     *
     * @param int|string $group
     *
     * @return false|int
     */
    protected function getGroupID($group)
    {
        if (is_numeric($group)) {
            return (int) $group;
        }

        $g = $this->groupModel->where('name', $group)->first();

        if (! $g) {
            $this->error = lang('Auth.groupNotFound', [$group]);

            return false;
        }

        return (int) $g->id;
    }

    // --------------------------------------------------------------------
    // Permissions
    // --------------------------------------------------------------------

    /**
     * Returns the details about a single permission.
     *
     * @param int|string $permission
     *
     * @return object|null
     */
    public function permission($permission)
    {
        if (is_numeric($permission)) {
            return $this->permissionModel->find((int) $permission);
        }

        return $this->permissionModel->like('name', $permission, 'none', null, true)->first();
    }

    /**
     * Returns an array of all permissions in the system.
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this->permissionModel->findAll();
    }

    /**
     * Creates a single permission.
     *
     * @return mixed
     */
    public function createPermission(string $name, string $description = '')
    {
        $data = [
            'name'        => $name,
            'description' => $description,
        ];

        $validation = service('validation', null, false);
        $validation->setRules([
            'name'        => 'required|max_length[255]|is_unique[auth_permissions.name]',
            'description' => 'max_length[255]',
        ]);

        if (! $validation->run($data)) {
            $this->error = $validation->getErrors();

            return false;
        }

        $id = $this->permissionModel->skipValidation()->insert($data);

        if (is_numeric($id)) {
            return (int) $id;
        }

        $this->error = $this->permissionModel->errors();

        return false;
    }

    /**
     * Deletes a single permission and removes that permission from all groups.
     *
     * @return mixed
     */
    public function deletePermission(int $permissionId)
    {
        if (! $this->permissionModel->delete($permissionId)) {
            $this->error = $this->permissionModel->errors();

            return false;
        }

        // Remove the permission from all groups
        $this->groupModel->removePermissionFromAllGroups($permissionId);

        return true;
    }

    /**
     * Updates the details for a single permission.
     *
     * @return bool
     */
    public function updatePermission(int $id, string $name, string $description = '')
    {
        $data = [
            'name' => $name,
        ];

        if (! empty($description)) {
            $data['description'] = $description;
        }

        if (! $this->permissionModel->update($id, $data)) {
            $this->error = $this->permissionModel->errors();

            return false;
        }

        return true;
    }

    /**
     * Verifies that a permission (either ID or the name) exists and returns
     * the permission ID.
     *
     * @param int|string $permission
     *
     * @return false|int
     */
    protected function getPermissionID($permission)
    {
        // If it's a number, we're done here.
        if (is_numeric($permission)) {
            return (int) $permission;
        }

        // Otherwise, pull it from the database.
        $p = $this->permissionModel->asObject()->where('name', $permission)->first();

        if (! $p) {
            $this->error = lang('Auth.permissionNotFound', [$permission]);

            return false;
        }

        return (int) $p->id;
    }

    /**
     * Returns an array of all permissions in the system for a group
     * The group can be either the ID or the name of the group.
     *
     * @param int|string $group
     *
     * @return mixed
     */
    public function groupPermissions($group)
    {
        if (is_numeric($group)) {
            return $this->groupModel->getPermissionsForGroup($group);
        }

        $g = $this->groupModel->where('name', $group)->first();

        return $this->groupModel->getPermissionsForGroup($g->id);
    }

    /**
     * Returns an array of all users in a group
     * The group can be either the ID or the name of the group.
     *
     * @param int|string $group
     *
     * @return mixed
     */
    public function usersInGroup($group)
    {
        if (is_numeric($group)) {
            return $this->groupModel->getUsersForGroup($group);
        }

        $g = $this->groupModel->where('name', $group)->first();

        return $this->groupModel->getUsersForGroup($g->id);
    }
}
