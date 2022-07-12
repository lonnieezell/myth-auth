<?php

namespace Myth\Auth\Language\fr;

return [
    // Exceptions
    'invalidModel'        => 'Le {0} model doit être chargé avant utilisation.',
    'userNotFound'        => 'Aucun utilisateur avec l’ID = {0, number}.',
    'noUserEntity'        => '"User Entity" doit être spécifiée pour la validation du mot de passe.',
    'tooManyCredentials'  => 'Vous ne pouvez valider que sur un seul identifiant autre qu’un mot de passe.',
    'invalidFields'       => 'Le champ "{0}" ne peut pas être utilisé pour vous identifier.',
    'unsetPasswordLength' => 'Vous devez définir le paramètre `minimumPasswordLength` dans le fichier de configuration Auth.',
    'unknownError'        => 'Échec lors de l’envoi de votre e-mail. Veuillez essayer ultérieurement.',
    'notLoggedIn'         => 'Vous devez être connecté pour accéder à cette page.',
    'notEnoughPrivilege'  => 'Vous n’avez pas les autorisations nécessaires pour accéder à cette page.',

    // Registration
    'registerDisabled' => 'La création d’un compte n’est pas autorisée pour le moment.',
    'registerSuccess'  => "Bienvenue\u{202f}! Veuillez vous connecter avec vos identifiants.",
    'registerCLI'      => "Nouvel utilisateur créé\u{202f}: {0}, #{1}",

    // Activation
    'activationNoUser'       => 'Aucun utilisateur avec ce code d’activation n’a été trouvé.',
    'activationSubject'      => 'Activez votre compte',
    'activationSuccess'      => 'Veuillez confirmer votre compte en cliquant sur le lien d’activation que nous vous avons envoyé par e-mail.',
    'activationResend'       => 'Envoyer le message d’activation une nouvelle fois.',
    'notActivated'           => 'Ce compte n’est pas encore activé.',
    'errorSendingActivation' => "Échec d’envoi du message d’activation à\u{202f}: {0}",

    // Login
    'badAttempt'      => 'Connexion échouée. Veuillez vérifier vos identifiants.',
    'loginSuccess'    => "Heureux de vous revoir\u{202f}!",
    'invalidPassword' => 'Connexion échouée. Veuillez vérifier votre mot de passe.',

    // Forgotten Passwords
    'forgotDisabled'  => 'L’option de réinitialisation du mot de passe à été désactivée.',
    'forgotNoUser'    => 'Aucun utilisateur trouvé avec cet e-mail.',
    'forgotSubject'   => 'Instructions de réinitialisation de mot de passe',
    'resetSuccess'    => 'Votre mot de passe a été changé avec succès. Veuillez vous connecter avec le nouveau mot de passe.',
    'forgotEmailSent' => 'Un jeton d’authentification vous a été envoyée par e-mail. Saisissez le dans le champ ci-dessous pour continuer.',
    'errorEmailSent'  => 'Échec lors de l’envoi de l’e-mail d’instructions de réinitialisation de mot de passe à: {0}',
    'errorResetting'  => 'Échec lors de l’envoi d’instructions de réinitialisation de mot de passe à: {0}',

    // Passwords
    'errorPasswordLength'       => 'Les mots de passe doivent contenir au moins {0, number} caractères.',
    'suggestPasswordLength'     => 'Les phrases secrètes - allant jusqu’à 255 caractères de long - constituent des mots de passe plus sécurisées et simples à retenir.',
    'errorPasswordCommon'       => 'Le mot de passe ne doit pas être un mot de passe commun.',
    'suggestPasswordCommon'     => 'Le mot de passe a été vérifié par rapport à 65 mille mots de passes communs ou mots de passes qui ont été découvert par piratages.',
    'errorPasswordPersonal'     => 'Les mots de passe ne peuvent pas contenir d’informations à caratère personel.',
    'suggestPasswordPersonal'   => 'Les mots de passe ne doivent pas contenir une variante de votre e-mail ou de votre nom d’utilisateur.',
    'errorPasswordTooSimilar'   => 'Le mot de passe est trop similaire au nom d’utilisateur.',
    'suggestPasswordTooSimilar' => 'N’utilisez pas des parties de votre nom d’utilisateur dans votre mot de passe.',
    'errorPasswordPwned'        => 'Le mot de passe {0} a été exposé lors d’une faille de données et a été vu {1, number} fois dans {2} des mots de passe compromis.',
    'suggestPasswordPwned'      => '{0} ne devrait jamais être utilisé en tant que mot de passe. Si vous l’utilisez autre part, changez le immédiatement.',
    'errorPasswordEmpty'        => 'Un mot de passe est requis.',
    'passwordChangeSuccess'     => 'Mot de passe modifié avec succès',
    'userDoesNotExist'          => 'Le mot de passe n’a pas été modifié. L’utilisateur n’existe pas.',
    'resetTokenExpired'         => 'Votre jeton d’authentification a expiré.',

    // Groups
    'groupNotFound' => 'Le groupe {0} n’a pu être trouvé.',

    // Permissions
    'permissionNotFound' => 'L’autorisation {0} n’a pu être trouvée.',

    // Banned
    'userIsBanned' => 'L’utilisateur a été bloqué. Contactez l’administrateur.',

    // Too many requests
    'tooManyRequests' => 'Trop de requêtes. Veuillez attendre {0, number} secondes.',

    // Login views
    'home'                      => 'Accueil',
    'current'                   => 'Courant',
    'forgotPassword'            => "Mot de passe oublié\u{202f}?",
    'enterEmailForInstructions' => "Aucun problème\u{202f}! Saisissez votre e-mail ci-dessous et nous vous enverrons des instructions pour réinitialiser votre mot de passe.",
    'email'                     => 'E-mail',
    'emailAddress'              => 'Adresse e-mail',
    'sendInstructions'          => 'Envoyer des instructions',
    'loginTitle'                => 'Connexion',
    'loginAction'               => 'Se connecter',
    'rememberMe'                => 'Se souvenir de moi',
    'needAnAccount'             => "Besoin d’un compte\u{202f}?",
    'forgotYourPassword'        => "Mot de passe oublié\u{202f}?",
    'password'                  => 'Mot de passe',
    'repeatPassword'            => 'Répéter le mot de passe',
    'emailOrUsername'           => 'E-mail ou nom d’utilisateur',
    'username'                  => 'Nom d’utilisateur',
    'register'                  => 'S’enregistrer',
    'signIn'                    => 'Se connecter',
    'alreadyRegistered'         => "Vous avez déjà un compte\u{202f}?",
    'weNeverShare'              => 'Nous ne partagerons jamais votre e-mail avec qui que ce soit.',
    'resetYourPassword'         => 'Réinitialiser votre mot de passe',
    'enterCodeEmailPassword'    => 'Saisissez le code que vous avez reçu par e-mail, votre adresse e-mail et votre nouveau mot de passe.',
    'token'                     => 'Jeton d’authentification',
    'newPassword'               => 'Nouveau mot de passe',
    'newPasswordRepeat'         => 'Répétez le mot de passe',
    'resetPassword'             => 'Réinitialiser le mot de passe',
];
