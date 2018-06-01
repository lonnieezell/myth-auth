<?php namespace  Myth\Auth\Authorization;

interface AuthorizeInterface  {

    /**
     * Returns the latest error string.
     *
     * @return mixed
     */
    public function error();

    //--------------------------------------------------------------------
    // Actions
    //--------------------------------------------------------------------

    /**
     * Checks to see if a user is in a group.
     *
     * Groups can be either a string, with the name of the group, an INT
     * with the ID of the group, or an array of strings/ids that the
     * user must belong to ONE of. (It's an OR check not an AND check)
     *
     * @param     $groups
     * @param int $userId
     *
     * @return bool
     */
    public function inGroup($groups, int $userId);

    /**
     * Checks a user's groups to see if they have the specified permission.
     *
     * @param int|string $permission
     * @param int        $userId
     *
     * @return mixed
     */
    public function hasPermission($permission, int $userId);

    /**
     * Makes a member a part of a group.
     *
     * @param $userid
     * @param $group        // Either ID or name
     *
     * @return bool
     */
    public function addUserToGroup(int $userid, $group);

    /**
     * Removes a single user from a group.
     *
     * @param $userId
     * @param $group
     *
     * @return mixed
     */
    public function removeUserFromGroup(int $userId, $group);

    /**
     * Adds a single permission to a single group.
     *
     * @param int|string $permission
     * @param int|string $group
     *
     * @return mixed
     */
    public function addPermissionToGroup($permission, $group);

    /**
     * Removes a single permission from a group.
     *
     * @param int|string $permission
     * @param int|string $group
     *
     * @return mixed
     */
    public function removePermissionFromGroup($permission, $group);

    //--------------------------------------------------------------------
    // Groups
    //--------------------------------------------------------------------

    /**
     * Grabs the details about a single group.
     *
     * @param $group
     *
     * @return object|null
     */
    public function group($group);

    /**
     * Grabs an array of all groups.
     *
     * @return array of objects
     */
    public function groups();

    /**
     * @param $name
     * @param string $description
     *
     * @return mixed
     */
    public function createGroup(string $name, string $description='');

    /**
     * Deletes a single group.
     *
     * @param int $groupId
     *
     * @return bool
     */
    public function deleteGroup(int $groupId);

    /**
     * Updates a single group's information.
     *
     * @param $id
     * @param $name
     * @param string $description
     *
     * @return mixed
     */
    public function updateGroup(int $id, string $name, string $description='');

    //--------------------------------------------------------------------
    // Permissions
    //--------------------------------------------------------------------

    /**
     * Returns the details about a single permission.
     *
     * @param int|string $permission
     *
     * @return object|null
     */
    public function permission($permission);

    /**
     * Returns an array of all permissions in the system.
     *
     * @return mixed
     */
    public function permissions();

    /**
     * Creates a single permission.
     *
     * @param $name
     * @param string $description
     *
     * @return mixed
     */
    public function createPermission(string $name, string $description='');

    /**
     * Deletes a single permission and removes that permission from all groups.
     *
     * @param $permissionId
     *
     * @return mixed
     */
    public function deletePermission(int $permissionId);

    /**
     * Updates the details for a single permission.
     *
     * @param int    $id
     * @param string $name
     * @param string $description
     *
     * @return bool
     */
    public function updatePermission(int $id, string $name, string $description='');

}
