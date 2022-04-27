<?php

use Myth\Auth\Entities\User;

if (! function_exists('logged_in')) {
    /**
     * Checks to see if the user is logged in.
     *
     * @return bool
     */
    function logged_in()
    {
        return service('authentication')->check();
    }
}

if (! function_exists('user')) {
    /**
     * Returns the User instance for the current logged in user.
     *
     * @return User|null
     */
    function user()
    {
        $authenticate = service('authentication');
        $authenticate->check();

        return $authenticate->user();
    }
}

if (! function_exists('user_id')) {
    /**
     * Returns the User ID for the current logged in user.
     *
     * @return int|null
     */
    function user_id()
    {
        $authenticate = service('authentication');
        $authenticate->check();

        return $authenticate->id();
    }
}

if (! function_exists('in_groups')) {
    /**
     * Ensures that the current user is in at least one of the passed in
     * groups. The groups can be passed in as either ID's or group names.
     * You can pass either a single item or an array of items.
     *
     * Example:
     *  in_groups([1, 2, 3]);
     *  in_groups(14);
     *  in_groups('admins');
     *  in_groups( ['admins', 'moderators'] );
     *
     * @param mixed $groups
     */
    function in_groups($groups): bool
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        if ($authenticate->check()) {
            return $authorize->inGroup($groups, $authenticate->id());
        }

        return false;
    }
}

if (! function_exists('has_permission')) {
    /**
     * Ensures that the current user has the passed in permission.
     * The permission can be passed in either as an ID or name.
     *
     * @param int|string $permission
     */
    function has_permission($permission): bool
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        if ($authenticate->check()) {
            return $authorize->hasPermission($permission, $authenticate->id()) ?? false;
        }

        return false;
    }
}
