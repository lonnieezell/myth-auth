# Events Guide

The following events are thrown by the default configuration of Myth:Auth. These can be used to extend the
flow without having to modify the core files. 

## Authentication

**login ($user)**

This is fired after a user successfully logs into the system. The User entity is passed in as the only argument.

**logout ($user)**

This is fired after a user has been logged out of the system. The user entity is the only argument.


## Authorization

**beforeAddUserToGroup ($userId, $groupId)**

Called before adding a user to the group. Returning false from your event action will stop the user from
being added to the group. 

**didAddUserToGroup ($userId, $groupId)**

Called immediately after adding a user to a group. 

**beforeRemoveUserFromGroup ($userId, $groupId)**

Called before removing a user from the group. Returning false from your event action will stop the user from
being removed.

**didRemoveUserFromGroup ($userId, $groupId)**

Called immediately after removing a user from a group. 

**beforeAddPermissionToUser ($userId, $permissionId)**

Called before adding a permission to the user. Returning false from your event action will stop the permission from
being added.

**didAddPermissionToUser ($userId, $permissionId)**

Called immediately after adding a permission to a user.

**beforeRemovePermissionToUser ($userId, $permissionId)**

Called before removing a permission from the user. Returning false from your event action will stop execution.


