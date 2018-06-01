<?php namespace Myth\Authorization;

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
        $permissions = $this->builder()
            ->join('auth_groups_permissions', 'auth_groups_permissions.permission_id = auth_permissions.id', 'inner')
            ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups_permissions.group_id', 'inner')
            ->where('auth_groups_users.user_id', $userId)
            ->asArray()
            ->findAll();

        if (! $permissions)
        {
            return false;
        }

        $ids = array_column($permissions, 'permission_id');

        return in_array($permissionId, $ids);
    }
}
