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
        // Check user permissions and take advantage of caching
        $userPerms = $this->getPermissionsForUser($userId);

        if (count($userPerms) && array_key_exists($permissionId, $userPerms))
        {
            return true;
        }

        // Check group permissions
        $count = $this->db->table('auth_groups_permissions')
            ->join('auth_groups_users', 'auth_groups_users.user_id = '. $this->db->escape($userId), 'inner')
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
     * @return \CodeIgniter\Database\BaseResult|\CodeIgniter\Database\Query|false
     */
    public function addPermissionToUser(int $permissionId, int $userId)
    {
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
        if (! $found = cache("{$userId}_permissions"))
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

            $found = array_merge($fromUser, $fromGroup);

            $result = [];
            foreach ($found as $row)
            {
                $result[$row->id] = strtolower($row->name);
            }

            cache("{$userId}_permissions", $result, config('App'), 300);
        }

        return $result;
    }
}
