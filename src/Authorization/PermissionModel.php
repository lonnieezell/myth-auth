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
        'description' => 'max_length[255]',
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
        // Check user permissions
        $count = $this->db->table('auth_users_permissions')
            ->where('permission_id', $permissionId)
            ->where('user_id', $userId)
            ->countAll();

        if ($count > 0)
        {
            return true;
        }

        // Check group permissions
        $count = $this->db->table('auth_groups_permissions')
            ->join('auth_groups_users', 'auth_groups_users.user_id = '. $this->db->escape($userId), 'inner')
            ->where('auth_groups_permissions.permission_id', $permissionId)
            ->where('auth_groups_users.user_id', $userId)
            ->countAll();

        return $count > 0;
    }
}
