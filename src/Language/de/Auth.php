<?php

return [

    // Exceptions
    'invalidModel'              => 'Das {0} Model muss geladen werden, bevor es genutzt werden kann.',
    'userNotFound'              => 'Der Benutzer mit der ID = {0, number} konnte nicht gefunden werden.',
    'noUserEntity'              => 'Für die Passwortüberprüfung muss das User Entity angegebenen werden.',
    'tooManyCredentials'        => 'Es kann nur 1 weiteres Benutzerfeld zusätzlich zu dem Passwort überprüft werden.',
    'invalidFields'             => 'Das Feld "{0}" kann nicht genutzt werden um Anmeldedaten zu überprüfen.',
    'unsetPasswordLength'       => 'Die Einstellung `minimumPasswordLength` muss in der Auth Config Datei gesetzt werden.',
    'unknownError'              => 'Es tut uns leid, aber es ist ein Fehler aufgetreten beim Zusenden der E-Mail. Bitte versuchen Sie es später noch einmal.',
    'notLoggedIn'               => 'Für den Zugriff auf diese Seite müssen Sie eingeloggt sein.',
    'notEnoughPrivilege'        => 'Sie haben keine ausreichende Berechtigung um diese Seite anzusehen.',

    // Registration
    'registerDisabled'          => 'Es tut uns leid, aber momentan können keine neuen Benutzerkonten angelegt werden.',
    'registerSuccess'           => 'Willkommen! Bitte loggen Sie sich mit Ihren neuen Anmeldedaten ein.',
    'registerCLI'               => 'Neuer Benutzer angelegt: {0}, #{1}',

    // Activation
    'activationNoUser'          => 'Es konnte kein Benutzer für diesen Aktivierungs Code gefunden werden.',
    'activationSubject'         => 'Aktivieren Sie Ihr Benutzerkonto.',
    'activationSuccess'         => 'Bitte aktivieren Sie Ihr Benutzerkonto, indem Sie auf den Link klicken, den wir Ihnen per E-Mail zugesendet haben.',
    'activationResend'          => 'Aktivierungs Code nochmals zusenden.',
    'notActivated'              => 'Dieses Benutzerkonto wurde noch nicht aktiviert.',
    'errorSendingActivation'    => 'Fehler beim Senden des Aktivierungs Codes an: {0}',

    // Login
    'badAttempt'                => 'Sie konnten nicht eingeloggt werden. Bitte überprüfen Sie Ihre eingegebenen Benutzerdaten.',
    'loginSuccess'              => 'Willkommen zurück!',
    'invalidPassword'           => 'Sie konnten nicht eingeloggt werden. Bitte überprüfen Sie Ihr eingegebenens Passwort.',

    // Forgotten Passwords
    'forgotDisabled'            => 'Das Zurücksetzen des Passworts ist deaktiviert.',
    'forgotNoUser'              => 'Es konnte kein Benutzer mit dieser E-Mail gefunden werden.',
    'forgotSubject'             => 'Anleitung zum Zurücksetzen des Passworts',
    'resetSuccess'              => 'Ihr Passwort wurde erfolgreich geändert. Bitte loggen Sie sich mit Ihrem neuen Passwort ein.',
    'forgotEmailSent'           => 'Ein Code zum Zurücksetzen Ihres Passworts wurde Ihnen per E-Mail zugesendet. Geben Sie den Code in die Box unten ein um fortzufahren.',
    'errorEmailSent'            => 'Fehler beim Senden der Anleitung zum zurücksetzten des Passworts an: {0}',
    'errorResetting'            => 'Fehler beim Senden der Passwort Zurücksetz-Anleitung an: {0}',

    // Passwords
    'errorPasswordLength'       => 'Das Passwort muss mindestens {0, number} Zeichen lang sein.',
    'suggestPasswordLength'     => 'Passwörter mit bis zu 255 Zeichen sind sicherer und einfacher zu merken.',
    'errorPasswordCommon'       => 'Das Passwort darf kein gewöhnliches Passwort sein.',
    'suggestPasswordCommon'     => 'Das Passwort wurde mit 65.000 Passwörtern, die häufig genutzt werden und mit Passwörtern aus Passwortleaks abgeglichen',
    'errorPasswordPersonal'     => 'Das Passwort darf keine gehashten Benutzerdaten enthalten.',
    'suggestPasswordPersonal'   => 'Variationen Ihres Benutzernamens oder Ihrer E-Mail-Adresse sollten nicht als Passwort verwendet werden.',
    'errorPasswordTooSimilar'    => 'Das Passwort ähnelt dem Benutzernamen zu sehr.',
    'suggestPasswordTooSimilar'  => 'Nutzen Sie keine Teile Ihres Benutzernamens in Ihrem Passwort.',
    'errorPasswordPwned'        => 'Das Passwort {0} wurde durch einen Datenleak veröffentlicht und verbreitet. Es kommt {1, number} mal in {2} von gestohlenen Passwörtern vor.',
    'errorPasswordPwnedDatabase' => 'einer Datenbank',
    'errorPasswordPwnedDatabases' => 'Datenbanken',
    'suggestPasswordPwned'      => '{0} sollte niemals als Passwort verwendet werden. Wenn Sie es irgendwo als Passwort nutzen, sollten Sie es umgehen ändern!',
    'errorPasswordEmpty'        => 'Passwort erforderlich.',
    'passwordChangeSuccess'     => 'Passwort wurde erfolgreich geändert',
    'userDoesNotExist'          => 'Das Passwort wurde nicht geändert. Der Benutzer existiert nicht.',
    'resetTokenExpired'         => 'Es tut uns leid, aber der Code zum Zurücksetzen des Passworts ist abgelaufen',

    // Groups
    'groupNotFound'             => 'Die Gruppe: {0} konnte nicht gefunden werden.',

    // Permissions
    'permissionNotFound'        => 'Berechtigung konnte nicht gefunden werden: {0}',

    // Banned
    'userIsBanned'              => 'Der Benutzer wurde gebannt. Bitte kontaktieren Sie den Administrator.',

    // Too many requests
    'tooManyRequests'           => 'Zu viele Anfragen. Bitte warten Sie {0, number} Sekunden.',

    // Login views
    'home'                      => 'Home',
    'current'                   => 'Momentan',
    'forgotPassword'            => 'Passwort vergessen?',
    'enterEmailForInstructions' => 'Kein Problem! Geben Sie Ihre E-Mail-Adresse ein und wir senden Ihnen eine Anleitung um Ihr Passwort zurückzusetzen.',
    'email'                     => 'E-Mail',
    'emailAddress'              => 'E-Mail-Adresse',
    'sendInstructions'          => 'Anleitung senden',
    'loginTitle'                => 'Login',
    'loginAction'               => 'Login',
    'rememberMe'                => 'Eingeloggt bleiben',
    'needAnAccount'             => 'Benutzerkonto benötigt?',
    'forgotYourPassword'        => 'Passwort vergessen?',
    'password'                  => 'Passwort',
    'repeatPassword'            => 'Passwort wiederholen',
    'emailOrUsername'           => 'E-Mail-Adresse oder Benutzername',
    'username'                  => 'Benutzername',
    'register'                  => 'Registrieren',
    'signIn'                    => 'Einloggen',
    'alreadyRegistered'         => 'Bereits registriert?',
    'weNeverShare'              => 'Wir werden Ihre E-Mail-Adresse niemals mit Dritten teilen.',
    'resetYourPassword'         => 'Ihr Passwort zurücksetzen',
    'enterCodeEmailPassword'    => 'Geben Sie den Code zum Zurücksetzten Ihres Passworts, den Sie per E-Mail erhalten haben, Ihre E-Mail-Adresse und Ihr neues Passwort ein.',
    'token'                     => 'Code',
    'newPassword'               => 'Neues Passwort',
    'newPasswordRepeat'         => 'Neues Passwort wiederholen',
    'resetPassword'             => 'Passwort zurücksetzen',

];
