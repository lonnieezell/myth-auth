<?php

return [
    // Exceptions
    'invalidModel'            => 'The {0} model must be loaded prior to use.',
    'userNotFound'            => 'Unable to locate a user with ID = {0, number}.',
    'tooManyCredentials'      => 'You may only validate against 1 credential other than a password.',
    'invalidFields'           => 'The "{0}" field cannot be used to validate credentials.',
    'unsetPasswordLength'     => 'You must set the `minimumPasswordLength` setting in the Auth config file.',
    'unknownError'            => 'Sorry, we encountered an issue sending the email to you. Please try again later.',

    'notLoggedIn'             => 'You must be logged in to access that page.',
    'notEnoughPrivilege'      => 'You do not have sufficient permissions to access that page.',

    // Registration
    'registerSuccess'         => 'Welcome aboard! Please login with your new credentials.',

    // Login
    'badAttempt'              => 'Unable to log you in. Please check your credentials.',
    'loginSuccess'            => 'Welcome back!',

    // Forgotten Passwords
    'forgotNoUser'            => 'Unable to locate a user with that email.',
    'forgotSubject'           => 'Password Reset Instructions',
    'resetSuccess'            => 'Your password has been successfully changed. Please login with the new password.',
    'forgotEmailSent'         => 'A security token has been emailed to you. Enter it in the box below to continue.',

    // Passwords
    'errorPasswordLength'     => 'Passwords must be at least {0, number} characters long.',
    'suggestPasswordLength'   => 'Pass phrases - up to 255 characters long - make more secure passwords that are simpler to remember.',
    'errorPasswordCommon'     => 'Password must not be a common password.',
    'suggestPasswordCommon'   => 'The password was checked against over 65k commonly used passwords or passwords that have been leaked through hacks.',
    'errorPasswordPersonal'   => 'Passwords cannot contain re-hashed personal information.',
    'suggestPasswordPersonal' => 'Variations on your email address or username should not be used for passwords.',
    'errorPasswordEmpty'      => 'Passwords are required.',

    // Groups
    'groupNotFound'           => 'Unable to locate group: {0}.',

    // Permissions
    'permissionNotFound'      => 'Unable to locate permission: {0}',
];
