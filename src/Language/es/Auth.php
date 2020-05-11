<?php

return [
    // Exceptions
    'invalidModel'              => 'Se debe cargar el modelo {0} antes de usarlo.',
    'userNotFound'              => 'No se puede localizar al usuario con ID = {0, number}.',
    'noUserEntity'              => 'User Entity must be provided for password validation.', //todo: translate
    'tooManyCredentials'        => 'Sólo se puede valir contra 1 credencial además de la contraseña.',
    'invalidFields'             => 'El campo "{0}" no puede utilizarse para validar credenciales.',
    'unsetPasswordLength'       => 'Se debe setear la variable `minimumPasswordLength` en la configuración.',
    'unknownError'              => 'Perdón, ocurrió un problema queriendo enviar el correo. Por favor intentá mas tarde.',
    'notLoggedIn'               => 'Se debe ingresar al sistema antes de poder acceder a esa página.',
    'notEnoughPrivilege'        => 'No tenés los permisos necesarios para poder acceder a esta página.',

    // Registration
    'registerDisabled'          => 'La creación de cuentas de usuario está deshabilitada.',
    'registerSuccess'           => '¡Bienvenido! Por favor ingrese sus credenciales.',
    'registerCLI'               => 'New user created: {0}, #{1}', //todo: translate

    // Activation
    'activationNoUser'          => 'Unable to locate a user with that activation code.', // translate
    'activationSubject'         => 'Activate your account', // translate
    'activationSuccess'         => 'Please confirm your account by clicking the activation link in the email we have sent.', // translate
    'activationResend'          => 'Resend activation message one more time.', // translate
    'notActivated'              => 'This user account is not yet activated.', // translate
    'errorSendingActivation'    => 'Failed to send activation message to: {0}', // translate

    // Login
    'badAttempt'                => 'No se pudo ingresar al sistema. Por favor, chequee sus credenciales.',
    'loginSuccess'              => '¡Bienvenido nuevamente!',
    'invalidPassword'           => 'No se pudo ingresar al sistema. Por favor, chequee sus credenciales.',

    // Forgotten Passwords
    'forgotDisabled'            => 'Resseting password option has been disabled.', // translate
    'forgotNoUser'              => 'No se pudo localizar un usuario con ese correo electrónico.',
    'forgotSubject'             => 'Instrucciones para resetear la contraseña',
    'resetSuccess'              => 'El cambio de contraseña fue correcto. Por favor ingrese con su nueva contraseña.',
    'forgotEmailSent'           => 'Se ha enviado un código de seguridad a su e-mail. Ingréselo en el cuadro siguiente para continuar.',
    'errorEmailSent'            => 'Unable to send email with password reset instructions to: {0}', // translate

    // Passwords
    'errorPasswordLength'       => 'La contraseña debe tener al menos {0, number} caracteres.',
    'suggestPasswordLength'     => 'Las frases - de hasta 255 caracteres - hacen que las contraseñas sean mas seguras y fáciles de recordar.',
    'errorPasswordCommon'       => 'La contraseña no puede tan débil.',
    'suggestPasswordCommon'     => 'La contraseña fue contrastada contra 65.000 de uso habitual y las que fueron hackeadas.',
    'errorPasswordPersonal'     => 'Las contraseñas no pueden contener información personal.',
    'suggestPasswordPersonal'   => 'Variaciones de su e-mail o usuario no deben usarse como contraseñas.',
    'errorPasswordTooSimilar'    => 'Password is too similar to the username.',  //todo: translate
    'suggestPasswordTooSimilar'  => 'Do not use parts of your username in your password.',  //todo: translate
    'errorPasswordPwned'        => 'The password {0} has been exposed due to a data breach and has been seen {1, number} times in {2} of compromised passwords.',//todo: translate
    'suggestPasswordPwned'      => '{0} should never be used as a password. If you are using it anywhere change it immediately.', //todo: translate
    'errorPasswordEmpty'        => 'Se requiere una contraseña.',
    'passwordChangeSuccess'     => 'Contraseña cambiada',
    'userDoesNotExist'          => 'No se cambió la contraseña. El usuario no existe',
    'resetTokenExpired'         => 'Sorry. Your reset token has expired.', //todo: translate

    // Groups
    'groupNotFound'             => 'No se puede localizar al grupo: {0}.',

    // Permissions
    'permissionNotFound'        => 'No se puede localizar el permiso: {0}',

    // Banned
    'userIsBanned'              => 'El usuario está deshabilitado. Contacte al administrador',

    // Too many requests
    'tooManyRequests'           => 'Too many requests. Please wait {0, number} seconds.', // translate

    // Login views
    'home'                      => 'Login',
    'current'                   => 'actual',
    'forgotPassword'            => '¿Olvidaste tu contraseña?',
    'enterEmailForInstructions' => 'Ingresá tu correo electrónico y te serán reenviadas instrucciones para poder resetear tu contraseña.',
    'email'                     => 'e-Mail',
    'emailAddress'              => 'Dirección de e-Mail',
    'sendInstructions'          => 'Enviar Instrucciones',
    'loginTitle'                => 'Login',
    'loginAction'               => 'Ingresar',
    'rememberMe'                => 'Recordarme',
    'needAnAccount'             => '¿Necesitás una cuenta?',
    'forgotYourPassword'        => '¿Olvidaste tu contraseña?',
    'password'                  => 'Contraseña',
    'repeatPassword'            => 'Repetir Contraseña',
    'emailOrUsername'           => 'e-Mail o Nombre de Usuario',
    'username'                  => 'Nombre de usuario',
    'register'                  => 'Crear Usuario',
    'signIn'                    => 'Ingresar',
    'alreadyRegistered'         => '¿Ya registrado?',
    'weNeverShare'              => 'No divulgaremos tu correo electrónico con nadie mas.',
    'resetYourPassword'         => 'Resetea tu contraseña',
    'enterCodeEmailPassword'    => 'Ingresá el código que recibiste en tu e-mail, tu dirección de email, y tu nueva contraseña.',
    'token'                     => 'Código',
    'newPassword'               => 'Nueva Contraseña',
    'newPasswordRepeat'         => 'Repetir Contraseña',
    'resetPassword'             => 'Resetear Contraseña',
];
