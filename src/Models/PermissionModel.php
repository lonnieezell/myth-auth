<?php

namespace Myth\Auth\Models;

use CodeIgniter\Model;
use Faker\Generator;
use Myth\Auth\Entities\Permission;

class PermissionModel extends Model
{
    protected $table         = 'auth_permissions';
    protected $returnType    = Permission::class;
    protected $allowedFields = [
        'name',
        'description',
    ];
    protected $validationRules = [
        'name'        => 'required|max_length[255]|is_unique[auth_permissions.name,name,{name}]',
        'description' => 'max_length[255]',
    ];

    /**
     * Checks to see if a user, or one of their groups,
     * has a specific permission.
     */
    public function doesUserHavePermission(int $userId, int $permissionId): bool
    {
        return array_key_exists($permissionId, $this->getPermissionsForUser($userId));
    }

    /**
     * Adds a single permission to a single user.
     *
     * @return bool
     */
    public function addPermissionToUser(int $permissionId, int $userId)
    {
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_users_permissions')->insert([
            'user_id'       => $userId,
            'permission_id' => $permissionId,
        ]);
    }

    /**
     * Removes a permission from a user.
     *
     * @return bool
     */
    public function removePermissionFromUser(int $permissionId, int $userId)
    {
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_users_permissions')->where([
            'user_id'       => $userId,
            'permission_id' => $permissionId,
        ])->delete();
    }

    /**
     * Gets all permissions for a user in a way that can be
     * easily used to check against:
     *
     * @return array<int, string> An array in format permissionId => permissionName
     */
    public function getPermissionsForUser(int $userId): array
    {
        if (null === $found = cache("{$userId}_permissions")) {
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

            foreach ($combined as $row) {
                $found[$row->id] = strtolower($row->name);
            }

            cache()->save("{$userId}_permissions", $found, 300);
        }

        return $found;
    }

    /**
     * Faked data for Fabricator.
     *
     * @return array|Permission See PermissionFaker
     */
    public function fake(Generator &$faker)
    {
        return new Permission([
            'name'        => $faker->word,
            'description' => $faker->sentence,
        ]);
    }
}
