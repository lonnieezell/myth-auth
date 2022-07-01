<?php

namespace Myth\Auth\Authorization;

use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table         = 'auth_groups';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'name', 'description',
    ];
    protected $useTimestamps   = false;
    protected $validationRules = [
        'name'        => 'required|max_length[255]|is_unique[auth_groups.name,name,{name}]',
        'description' => 'max_length[255]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    //--------------------------------------------------------------------
    // Users
    //--------------------------------------------------------------------

    /**
     * Adds a single user to a single group.
     *
     * @return bool
     */
    public function addUserToGroup(int $userId, int $groupId)
    {
        cache()->delete("{$groupId}_users");
        cache()->delete("{$userId}_groups");
        cache()->delete("{$userId}_permissions");

        $data = [
            'user_id'  => $userId,
            'group_id' => $groupId,
        ];

        return (bool) $this->db->table('auth_groups_users')->insert($data);
    }

    /**
     * Removes a single user from a single group.
     *
     * @param int|string $groupId
     *
     * @return bool
     */
    public function removeUserFromGroup(int $userId, $groupId)
    {
        cache()->delete("{$groupId}_users");
        cache()->delete("{$userId}_groups");
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_groups_users')
            ->where([
                'user_id'  => $userId,
                'group_id' => (int) $groupId,
            ])->delete();
    }

    /**
     * Removes a single user from all groups.
     *
     * @return bool
     */
    public function removeUserFromAllGroups(int $userId)
    {
        cache()->delete("{$userId}_groups");
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_groups_users')
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Returns an array of all groups that a user is a member of.
     *
     * @return array
     */
    public function getGroupsForUser(int $userId)
    {
        if (null === $found = cache("{$userId}_groups")) {
            $found = $this->builder()
                ->select('auth_groups_users.*, auth_groups.name, auth_groups.description')
                ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
                ->where('user_id', $userId)
                ->get()->getResultArray();

            cache()->save("{$userId}_groups", $found, 300);
        }

        return $found;
    }

    /**
     * Returns an array of all users that are members of a group.
     *
     * @return array
     */
    public function getUsersForGroup(int $groupId)
    {
        if (null === $found = cache("{$groupId}_users")) {
            $found = $this->builder()
                ->select('auth_groups_users.*, users.*')
                ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
                ->join('users', 'auth_groups_users.user_id = users.id', 'left')
                ->where('auth_groups.id', $groupId)
                ->get()->getResultArray();

            cache()->save("{$groupId}_users", $found, 300);
        }

        return $found;
    }

    //--------------------------------------------------------------------
    // Permissions
    //--------------------------------------------------------------------

    /**
     * Gets all permissions for a group in a way that can be
     * easily used to check against:
     *
     * [
     *  id => name,
     *  id => name
     * ]
     */
    public function getPermissionsForGroup(int $groupId): array
    {
        $permissionModel = model(PermissionModel::class);
        $fromGroup       = $permissionModel
            ->select('auth_permissions.*')
            ->join('auth_groups_permissions', 'auth_groups_permissions.permission_id = auth_permissions.id', 'inner')
            ->where('group_id', $groupId)
            ->findAll();

        $found = [];

        foreach ($fromGroup as $permission) {
            $found[$permission['id']] = $permission;
        }

        return $found;
    }

    /**
     * Add a single permission to a single group, by IDs.
     *
     * @return mixed
     */
    public function addPermissionToGroup(int $permissionId, int $groupId)
    {
        $data = [
            'permission_id' => $permissionId,
            'group_id'      => $groupId,
        ];

        return $this->db->table('auth_groups_permissions')->insert($data);
    }

    //--------------------------------------------------------------------

    /**
     * Removes a single permission from a single group.
     *
     * @return mixed
     */
    public function removePermissionFromGroup(int $permissionId, int $groupId)
    {
        return $this->db->table('auth_groups_permissions')
            ->where([
                'permission_id' => $permissionId,
                'group_id'      => $groupId,
            ])->delete();
    }

    //--------------------------------------------------------------------

    /**
     * Removes a single permission from all groups.
     *
     * @return mixed
     */
    public function removePermissionFromAllGroups(int $permissionId)
    {
        return $this->db->table('auth_groups_permissions')
            ->where('permission_id', $permissionId)
            ->delete();
    }
}
