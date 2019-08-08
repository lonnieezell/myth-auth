<?php

return [
    // Exceptions
    'invalidModel'              => 'Se debe cargar el modelo {0} antes de usarlo.',
    'userNotFound'              => 'No se puede localizar al usuario con ID = {0, number}.',
    'tooManyCredentials'        => 'Sólo se puede valir contra 1 credencial además de la contraseña.',
    'invalidFields'             => 'El campo "{0}" no puede utilizarse para validar credenciales.',
    'unsetPasswordLength'       => 'Se debe setear la variable `minimumPasswordLength` en la configuración.',
    'unknownError'              => 'Perdón, ocurrió un problema queriendo enviar el correo. Por favor intentá mas tarde.',

    'notLoggedIn'               => 'Se debe ingresar al sistema antes de poder acceder a esa página.',
    'notEnoughPrivilege'        => 'No tenés los permisos necesarios para poder acceder a esta página.',

    // Registration
    'registerDisabled'          => 'La creación de cuentas de usuario está deshabilitada.',
    'registerSuccess'           => '¡Bienvenido! Por favor ingrese sus credenciales.',

    // Login
    'badAttempt'                => 'No se pudo ingresar al sistema. Por favor, chequee sus credenciales.',
    'loginSuccess'              => '¡Bienvenido nuevamente!',

    // Forgotten Passwords
    'forgotNoUser'              => 'No se pudo localizar un usuario con ese correo electrónico.',
    'forgotSubject'             => 'Instrucciones para resetear la contraseña',
    'resetSuccess'              => 'El cambio de contraseña fue correcto. Por favor ingrese con su nueva contraseña.',
    'forgotEmailSent'           => 'Se ha enviado un código de seguridad a su e-mail. Ingréselo en el cuadro siguiente para continuar.',

    // Passwords
    'errorPasswordLength'       => 'La contraseña debe tener al menos {0, number} caracteres.',
    'suggestPasswordLength'     => 'Las frases - de hasta 255 caracteres - hacen que las contraseñas sean mas seguras y fáciles de recordar.',
    'errorPasswordCommon'       => 'La contraseña no puede tan débil.',
    'suggestPasswordCommon'     => 'La contraseña fue contrastada contra 65.000 de uso habitual y las que fueron hackeadas.',
    'errorPasswordPersonal'     => 'Las contraseñas no pueden contener información personal.',
    'suggestPasswordPersonal'   => 'Variaciones de su e-mail o usuario no deben usarse como contraseñas.',
    'errorPasswordEmpty'        => 'Se requiere una contraseña.',
    'passwordChangeSuccess'     => 'Contraseña cambiada',
    'userDoesNotExist'          => 'No se cambió la contraseña. El usuario no existe',

    // Groups
    'groupNotFound'             => 'No se puede localizar al grupo: {0}.',

    // Permissions
    'permissionNotFound'        => 'No se puede localizar el permiso: {0}',

    // Banned
    'userIsBanned'              => 'El usuario está deshabilitado. Contacte al administrador',

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
