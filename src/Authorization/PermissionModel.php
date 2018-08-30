<?php namespace Myth\Auth\Authorization;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table = 'auth_permissions';

    protected $allowedFields = [
        'name', 'description'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[auth_permissions.name,name,{name}]',
        'description' => 'max_length[255',
    ];

    /**
     * Checks to see if a user, or one of their groups,
     * has a specific permission.
     *
     * @param $userId
     * @param $permissionId
     *
     * @return bool
     */
    public function doesUserHavePermission(int $userId, int $permissionId): bool
    {
        // Get user permissions
        $user_permissions = $this->builder()
            ->join('auth_users_permissions', 'auth_users_permissions.permission_id = auth_permissions.id', 'inner')
            ->where('auth_users_permissions.user_id', $userId)
            ->get()->getResultArray();

        $userIds = array_column($user_permissions, 'permission_id');

        // Get group permissions
        $group_permissions = $this->builder()
            ->join('auth_groups_permissions', 'auth_groups_permissions.permission_id = auth_permissions.id', 'inner')
            ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups_permissions.group_id', 'inner')
            ->where('auth_groups_users.user_id', $userId)
            ->get()->getResult();

        $groupIds = array_column($group_permissions, 'permission_id');

        // Merge both permissions into an array
        // Order is important as User permissions override Group permissions
        $ids = array_merge($userIds, $groupIds);

        // Remove duplicates giving more preference
        // to user permissions over group permissions
        $unique_permissions = array_unique($ids);

        return in_array($permissionId, $unique_permissions);
    }
}
