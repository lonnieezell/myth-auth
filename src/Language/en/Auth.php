<?php

return [
    // Exceptions
    'invalidModel'    => 'The {0, string} model must be loaded prior to use.',
    'userNotFound'    => 'Unable to locate a user with ID = {0, number}.',
    'tooManyCredentials' => 'You may only validate against 1 credential other than a password.',
    'invalidFields'   => 'The "{0, string}" field cannot be used to validate credentials.',
    'invalidPasswordLength' => 'You must set the `minimumPasswordLength` setting in the Auth config file.',

    // Registration
    'registerSuccess' => 'Welcome aboard! Please login with your new credentials.',

    // Login
    'badAttempt' => 'Unable to log you in. Please check your credentials.',
    'loginSuccess' => 'Welcome back!',

    // Passwords
    'errorPasswordLength' => 'Passwords must be at least {0, integer} characters long.',
    'suggestPasswordLength' => 'Pass phrases - up to 255 characters long - make more secure passwords that are simpler to remember.',
];
