<?php namespace Myth\Auth\Authorization;

use CodeIgniter\Database\BaseResult;
use CodeIgniter\Database\Query;
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
     * @param int $userId
     * @param int $permissionId
     *
     * @return bool
     */
    public function doesUserHavePermission(int $userId, int $permissionId): bool
    {
        // Check user permissions and take advantage of caching
        $userPerms = $this->getPermissionsForUser($userId);

        if (count($userPerms) && array_key_exists($permissionId, $userPerms))
        {
            return true;
        }

        // Check group permissions
        $count = $this->db->table('auth_groups_permissions')
            ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups_permissions.group_id', 'inner')
            ->where('auth_groups_permissions.permission_id', $permissionId)
            ->where('auth_groups_users.user_id', $userId)
            ->countAllResults();

        return $count > 0;
    }

    /**
     * Adds a single permission to a single user.
     *
     * @param int $permissionId
     * @param int $userId
     *
     * @return BaseResult|Query|false
     */
    public function addPermissionToUser(int $permissionId, int $userId)
    {
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_users_permissions')->insert([
            'user_id' => $userId,
            'permission_id' => $permissionId
        ]);
    }

    /**
     * Removes a permission from a user.
     *
     * @param int $permissionId
     * @param int $userId
     *
     * @return mixed
     */
    public function removePermissionFromUser(int $permissionId, int $userId)
    {
        $this->db->table('auth_users_permissions')->where([
            'user_id' => $userId,
            'permission_id' => $permissionId
        ])->delete();

        cache()->delete("{$userId}_permissions");
    }

    /**
     * Gets all permissions for a user in a way that can be
     * easily used to check against:
     *
     * [
     *  id => name,
     *  id => name
     * ]
     *
     * @param int $userId
     *
     * @return array
     */
    public function getPermissionsForUser(int $userId): array
    {
        if (null === $found = cache("{$userId}_permissions"))
        {
            $fromUser = $this->db->table('auth_users_permissions')
                ->select('id, auth_permissions.name')
                ->join('auth_permissions', 'auth_permissions.id = permission_id', 'inner')
                ->where('user_id', $userId)
                ->get()
                ->getResultObject();
            $fromGroup = $this->db->table('auth_groups_users')
                ->select('auth_permissions.id, auth_permissions.name')
                ->join('auth_groups_permissions', 'auth_groups_permissions.group_id = auth_groups_users.group_id', 'inner')
                ->join('auth_permissions', 'auth_permissions.id = auth_groups_permissions.permission_id', 'inner')
                ->where('user_id', $userId)
                ->get()
                ->getResultObject();

            $combined = array_merge($fromUser, $fromGroup);

            $found = [];
            foreach ($combined as $row)
            {
                $found[$row->id] = strtolower($row->name);
            }

            cache()->save("{$userId}_permissions", $found, 300);
        }

        return $found;
    }
}
