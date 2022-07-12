<?php

namespace Myth\Auth\Language\es;

return [
    // Exceptions
    'invalidModel'        => 'Se debe cargar el modelo {0} antes de usarlo.',
    'userNotFound'        => 'No se puede localizar al usuario con ID = {0, number}.',
    'noUserEntity'        => 'Se debe proporcionar la entidad de usuario para la validación de la contraseña.',
    'tooManyCredentials'  => 'Sólo se puede valir contra 1 credencial además de la contraseña.',
    'invalidFields'       => 'El campo "{0}" no puede utilizarse para validar credenciales.',
    'unsetPasswordLength' => 'Se debe setear la variable `minimumPasswordLength` en la configuración.',
    'unknownError'        => 'Ocurrió un problema queriendo enviar el correo. Por favor inténtalo mas tarde.',
    'notLoggedIn'         => 'Se debe entrar al sistema antes de poder acceder a esa página.',
    'notEnoughPrivilege'  => 'No tienes los permisos necesarios para poder acceder a esta página.',

    // Registration
    'registerDisabled' => 'La creación de cuentas de usuario está deshabilitada.',
    'registerSuccess'  => '¡Bienvenido! Por favor, escribe tus credenciales.',
    'registerCLI'      => 'Nuevo usuario creado: {0}, #{1}',

    // Activation
    'activationNoUser'       => 'No se puede localizar al usuario con ese código de activación.',
    'activationSubject'      => 'Activación de cuenta',
    'activationSuccess'      => 'Por favor, confirma tu cuenta haciendo clic en el link de activación del correo electrónico que se te envió.',
    'activationResend'       => 'Reenviar el mensaje de activación una otra vez.',
    'notActivated'           => 'La cuenta de este usuario aún no está activada.',
    'errorSendingActivation' => 'Falló el envío del mensaje de activación para: {0}',

    // Login
    'badAttempt'      => 'No se pudo entrar al sistema. Por favor, comprueba tus credenciales.',
    'loginSuccess'    => '¡Bienvenido de nuevo!',
    'invalidPassword' => 'No se pudo entrarar al sistema. Por favor, comprueba tus credenciales.',

    // Forgotten Passwords
    'forgotDisabled'  => 'La opción de restablecimiento de contraseña ha sido deshabilitada.',
    'forgotNoUser'    => 'No se pudo localizar un usuario con ese correo electrónico.',
    'forgotSubject'   => 'Instrucciones para resetear la contraseña',
    'resetSuccess'    => 'El cambio de contraseña fue correcto. Por favor entre con su nueva contraseña.',
    'forgotEmailSent' => 'Se ha enviado un código de seguridad a su e-mail. Tecléalo en el cuadro siguiente para continuar.',
    'errorEmailSent'  => 'No se pudo enviar el correo electrónico con las instrucciones para el reseteo de contraseña a: {0}',
    'errorResetting'  => 'No se pudo enviar las instrucciones de reseteo a {0}',

    // Passwords
    'errorPasswordLength'       => 'La contraseña debe tener al menos {0, number} caracteres.',
    'suggestPasswordLength'     => 'Las frases - de hasta 255 caracteres - hacen que las contraseñas sean mas seguras y fáciles de recordar.',
    'errorPasswordCommon'       => 'La contraseña no puede ser tan débil.',
    'suggestPasswordCommon'     => 'La contraseña fue contrastada contra 65.000 de uso habitual y las que fueron hackeadas.',
    'errorPasswordPersonal'     => 'Las contraseñas no pueden contener información personal.',
    'suggestPasswordPersonal'   => 'Variaciones de su e-mail o usuario no deben usarse como contraseñas.',
    'errorPasswordTooSimilar'   => 'La contraseña es muy parecida al usuario.',
    'suggestPasswordTooSimilar' => 'No uses partes de tu usuario en tu contraseña.',
    'errorPasswordPwned'        => 'La contraseña {0} ha estado expuesta debido a una violación de datos y se ha visto {1, number} veces en {2} como contraseñas comprometidas.',
    'suggestPasswordPwned'      => '{0} nunca debe ser usada como sontraseña. Si lo estás usando en cualquier lugar, cámbielo inmediatamente.',
    'errorPasswordEmpty'        => 'Se necesita una contraseña.',
    'passwordChangeSuccess'     => 'Contraseña cambiada',
    'userDoesNotExist'          => 'No se cambió la contraseña. El usuario no existe',
    'resetTokenExpired'         => 'Lo sentimos. El token de reseteo ha caducado.',

    // Groups
    'groupNotFound' => 'No se puede localizar al grupo: {0}.',

    // Permissions
    'permissionNotFound' => 'No se puede localizar el permiso: {0}',

    // Banned
    'userIsBanned' => 'El usuario está deshabilitado. Contacta con el administrador',

    // Too many requests
    'tooManyRequests' => 'demasiados intentos. Por favor, espera {0, number} segundos.',

    // Login views
    'home'                      => 'Login',
    'current'                   => 'actual',
    'forgotPassword'            => '¿Olvidaste tu contraseña?',
    'enterEmailForInstructions' => 'Teclea tu correo electrónico y te serán reenviadas instrucciones para poder resetear tu contraseña.',
    'email'                     => 'e-Mail',
    'emailAddress'              => 'Dirección de e-Mail',
    'sendInstructions'          => 'Enviar Instrucciones',
    'loginTitle'                => 'Login',
    'loginAction'               => 'Entrar',
    'rememberMe'                => 'Recordarme',
    'needAnAccount'             => '¿Necesitas una cuenta?',
    'forgotYourPassword'        => '¿Olvidaste tu contraseña?',
    'password'                  => 'Contraseña',
    'repeatPassword'            => 'Repetir Contraseña',
    'emailOrUsername'           => 'e-Mail o Nombre de Usuario',
    'username'                  => 'Nombre de usuario',
    'register'                  => 'Crear Usuario',
    'signIn'                    => 'Entrar',
    'alreadyRegistered'         => '¿Ya registrado?',
    'weNeverShare'              => 'No compartiremos tu correo electrónico con nadie mas.',
    'resetYourPassword'         => 'Resetea tu contraseña',
    'enterCodeEmailPassword'    => 'Teclea el código que recibiste en tu e-mail, tu dirección de email, y tu nueva contraseña.',
    'token'                     => 'Código',
    'newPassword'               => 'Nueva Contraseña',
    'newPasswordRepeat'         => 'Repetir Contraseña',
    'resetPassword'             => 'Resetear Contraseña',
];
