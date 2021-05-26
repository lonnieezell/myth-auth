<?php

return [
    // Exceptions
    'invalidModel'              => 'Het {0} model moet geladen worden voor gebruik.',
    'userNotFound'              => 'De gebruiker met ID = {0, number} kan niet worden gevonden.',
    'noUserEntity'              => 'User Entity moet worden opgegeven voor wachtwoordvalidatie.',
    'tooManyCredentials'        => 'U mag slechts valideren op basis van 1 andere referentie dan een wachtwoord.',
    'invalidFields'             => 'Het veld "{0}" kan niet worden gebruikt om inloggegevens te valideren.',
    'unsetPasswordLength'       => 'Je moet de instelling `minimumPasswordLength` instellen in het Auth configuratiebestand.',
    'unknownError'              => 'Sorry, er is een probleem opgetreden bij het verzenden van de e-mail naar u. Probeer het later nog eens.',
    'notLoggedIn'               => 'Je moet ingelogd zijn om deze pagina te bekijken.',
    'notEnoughPrivilege'        => 'Je hebt niet genoeg rechten om deze pagina te bekijken.',

    // Registration
    'registerDisabled'          => 'Sorry, nieuwe gebruikersaccounts zijn momenteel niet toegestaan.',
    'registerSuccess'           => 'Welkom! Log in met uw nieuwe inloggegevens.',
    'registerCLI'               => 'Nieuwe gebruiker aangemaakt: {0}, #{1}',

    // Activation
    'activationNoUser'          => 'Geen gebruiker gevonden met deze activatie code.',
    'activationSubject'         => 'Activeer je account',
    'activationSuccess'         => 'Activeer je account door op de activatielink in de e-mail te klikken.',
    'activationResend'          => 'Verstuur activatielink opnieuw.',
    'notActivated'              => 'Deze gebruiker is nog niet geactiveerd.',
    'errorSendingActivation'    => 'Versturen van activiatielink aan {0} mislukt.',

    // Login
    'badAttempt'                => 'Inloggen mislukt. Controleer je gegevens.',
    'loginSuccess'              => 'Welkom terug!',
    'invalidPassword'           => 'Inloggen mislukt. Controleer je wachtwoord.',

    // Forgotten Passwords
    'forgotDisabled'            => 'Wachtwoord vergeten is uitgeschakeld.',
    'forgotNoUser'              => 'Er kan geen gebruiker met dit e-mailadres gevonden worden.',
    'forgotSubject'             => 'Instructies wachtwoord herstellen',
    'resetSuccess'              => 'Je wachtwoord is succesvol gewijzigd. Login met je nieuwe wachtwoord.',
    'forgotEmailSent'           => 'Een beveiligingscode is naar het e-mailadres gestuurd. Vul de code in onderstaand veld in om door te gaan.',
    'errorEmailSent'            => 'Kan geen instructies voor wachtwoord herstel naar {0} sturen.',
    'errorResetting'            => 'Kan geen instructies voor wachtwoord herstel sturen naar {0}',

    // Passwords
    'errorPasswordLength'       => 'Het wachtwoord moet minimaal {0, number} karakters lang zijn.',
    'suggestPasswordLength'     => 'Een wachtwoord zin - maximaal 255 karakters - zorgt voor een makkelijk te onthouden, maar sterk wachtwoord.',
    'errorPasswordCommon'       => 'Het wachtwoord mag geen algemeen gebruikt wachtwoord zijn.',
    'suggestPasswordCommon'     => 'Het wachtwoord is gecontroleerd aan de hand van een lijst met 65 duizend veel gebruikte wachtwoorden of wachtwoorden die betrokken zijn geweest bij een datalek.',
    'errorPasswordPersonal'     => 'Het wachtwoord mag geen persoonlijke informatie bevatten.',
    'suggestPasswordPersonal'   => 'Variaties van je e-mailadres en gebruikersnaam horen niet in je wachtwoord voor te komen.',
    'errorPasswordTooSimilar'   => 'Het wachtwoord lijkt te veel op de gebruikersnaam.',
    'suggestPasswordTooSimilar' => 'Je wachtwoord mag geen overeenkomsten bevatten met de gebruikersnaam.',
    'errorPasswordPwned'        => 'Het wachtwoord {0} is betrokken geweest bij een datalek en komt {1, number} keer voor in {2} van buitgemaakte wachtwoorden.',
    'suggestPasswordPwned'      => '{0} is niet toegestaan als wachtwoord. Als je dit wachtwoord ergens anders ook gebruikt moet je dit zo snel mogelijk wijzigen.',
    'errorPasswordEmpty'        => 'Wachtwoord verplicht.',
    'passwordChangeSuccess'     => 'Wachtwoord succesvol gewijzigd.',
    'userDoesNotExist'          => 'Wachtwoord niet gewijzigd. Gebruiker bestaat niet.',
    'resetTokenExpired'         => 'Sorry. Je reset code is verlopen.',

    // Groups
    'groupNotFound'             => 'Kan groep {0} niet vinden.',

    // Permissions
    'permissionNotFound'        => 'Kan rechten {0} niet vinden.',

    // Banned
    'userIsBanned'              => 'Gebruiker is verbannen. Neem contact op met de administrator.',

    // Too many requests
    'tooManyRequests'           => 'Te veel verzoeken. Wacht {0, number} seconden.',

    // Login views
    'home'                      => 'Thuis',
    'current'                   => 'Huidig',
    'forgotPassword'            => 'Wachtwoord vergeten?',
    'enterEmailForInstructions' => 'Geen probleem! Voer hier beneden je e-mailadres in en we sturen je instructies om je wachtwoord te wijzigen.',
    'email'                     => 'E-mail',
    'emailAddress'              => 'E-mailadres',
    'sendInstructions'          => 'Verstuur instructies',
    'loginTitle'                => 'Login',
    'loginAction'               => 'Login',
    'rememberMe'                => 'Onthoud mij',
    'needAnAccount'             => 'Account nodig?',
    'forgotYourPassword'        => 'Wachtwoord vergeten?',
    'password'                  => 'Wachtwoord',
    'repeatPassword'            => 'Herhaal wachtwoord',
    'emailOrUsername'           => 'E-mail of gebruikersnaam',
    'username'                  => 'Gebruikersnaam',
    'register'                  => 'Registreer',
    'signIn'                    => 'Login',
    'alreadyRegistered'         => 'Al geregistreerd?',
    'weNeverShare'              => 'We delen je e-mailadres nooit met iemand anders.',
    'resetYourPassword'         => 'Reset je wachtwoord',
    'enterCodeEmailPassword'    => 'Voer de code uit de e-mail, je e-mailadres en nieuwe wachtwoord in.',
    'token'                     => 'Code',
    'newPassword'               => 'Nieuw wachtwoord',
    'newPasswordRepeat'         => 'Herhaal nieuw wachtwoord',
    'resetPassword'             => 'Reset wachtwoord',
];
