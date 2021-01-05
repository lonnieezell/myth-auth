<?php

return [
    // Exceptions
    'invalidModel'              => 'Il modello {0} deve essere caricato prima dell\'uso.',
    'userNotFound'              => 'Impossibile trovare un utente con l\'ID = {0, number}.',
    'noUserEntity'              => 'L\'entità utente deve essere fornita per la convalida della password.',
    'tooManyCredentials'        => 'Puoi fare la convalida solo con una credenziale diversa dalla password',
    'invalidFields'             => 'Non è possibile utilizzare il campo "{0}" per convalidare le credenziali.',
    'unsetPasswordLength'       => 'Devi impostare il valore `minimumPasswordLength` nel file di configurazione Auth.',
    'unknownError'              => 'Siamo spiacenti, si è verificato un problema durante l\'invio dell\'email. Riprova più tardi.',
    'notLoggedIn'               => 'Devi essere loggato per accedere alla pagina.',
    'notEnoughPrivilege'        => 'Non hai i permessi necessari per poter accedere alla pagina.',

    // Registration
    'registerDisabled'          => 'Siamo spiacenti, non è possibile creare nuovi account utente in questo momento.',
    'registerSuccess'           => 'Benvenuto! Effettua l\'accesso con le tue nuove credenziali.',
    'registerCLI'               => 'Nuovo accunt utente creato: {0}, #{1}',

    // Activation
    'activationNoUser'          => 'Impossibile trovare un utente con questo codice di attivazione.',
    'activationSubject'         => 'Attiva il tuo account',
    'activationSuccess'         => 'Conferma il tuo account facendo clic sul link di attivazione nell\'email che ti abbiamo inviato.',
    'activationResend'          => 'Invia ancora il messaggio di attivazione.',
    'notActivated'              => 'Questo account utente non è stato ancora attivato.',
    'errorSendingActivation'    => 'Impossibile inviare il messaggio di attivazione a: {0}',

    // Login
    'badAttempt'                => 'Impossibile accedere. Controlla le tue credenziali.',
    'loginSuccess'              => 'Ben tornato!',
    'invalidPassword'           => 'Impossibile accedere. Verifica la password.',

    // Forgotten Passwords
    'forgotDisabled'            => 'Resseting password option has been disabled.', // translate
    'forgotNoUser'              => 'Impossibile trovare un utente con questo indirizzo email.',
    'forgotSubject'             => 'Istruzioni per il Ripristino della Password',
    'resetSuccess'              => 'La tua password è stata cambiata con successo. Effettua l\'accesso con le tue nuove credenziali.',
    'forgotEmailSent'           => 'Un token di sicurezza è stato inviato al tuo indirizzo email. Inseriscilo nella casella qui sotto per continuare.',
    'errorEmailSent'            => 'Impossibile inviare l\'email con le istruzioni per resettare la password all\'indirizzo: {0}',
    'errorResetting'            => 'Impossibile inviare le istruzioni per resettare la password a: {0}',

    // Passwords
    'errorPasswordLength'       => 'La password deve contenere almeno {0, number} caratteri.',
    'suggestPasswordLength'     => 'Una frase utilizzata come password - di lunghezza fino a 255 caratteri - la rende più sicura e facile da ricordare.',
    'errorPasswordCommon'       => 'La password non deve essere una password comune.',
    'suggestPasswordCommon'     => 'La password inserita è stata messa a confronto con oltre 65.000 password comuni, password violate e password interessate a leaks e hacks.',
    'errorPasswordPersonal'     => 'La password non possono contenere informazioni personali rielaborate.',
    'suggestPasswordPersonal'   => 'La password non dovrebbe contenere variazioni del tuo indirizzo email o del tuo nome utente.',
    'errorPasswordTooSimilar'    => 'La password è troppo simile al nome utente.',
    'suggestPasswordTooSimilar'  => 'Non usare parti del tuo nome utente nella password.',
    'errorPasswordPwned'        => 'La password {0} è stata esposta a causa di una violazione dei dati ed è stata vista  {1, number} volte su {2} password compromesse.',
    'suggestPasswordPwned'      => 'La stringa {0} non dovrebbe mai essere utilizzata come password. Se la stai usando altrove cambiala immediatamente.',
    'errorPasswordEmpty'        => 'Una passoword è necessaria.',
    'passwordChangeSuccess'     => 'Password modificata correttamente',
    'userDoesNotExist'          => 'Password non modificata. L\'utente non esiste.',
    'resetTokenExpired'         => 'Siamo spiacenti. Il tuo token di ripristino password è scaduto.',

    // Groups
    'groupNotFound'             => 'Impossibile trovare il gruppo: {0}.',

    // Permissions
    'permissionNotFound'        => 'Impossibile trovare il permesso: {0}',

    // Banned
    'userIsBanned'              => 'L\'utente è stato bannato. Contatta l\'amministratore',

    // Too many requests
    'tooManyRequests'           => 'Troppe richieste. Attendere {0, number} secondi.',

    // Login views
    'home'                      => 'Home',
    'current'                   => 'Attuale',
    'forgotPassword'            => 'Password dimenticata?',
    'enterEmailForInstructions' => 'Nessun problema! Inserisci il tuo indirizzo email qui sotto per ricevere le istruzioni per il ripristino della tua password.',
    'email'                     => 'Email',
    'emailAddress'              => 'Indirizzo Email',
    'sendInstructions'          => 'Invia Istruzioni',
    'loginTitle'                => 'Accesso',
    'loginAction'               => 'Accedi',
    'rememberMe'                => 'Ricordami',
    'needAnAccount'             => 'Hai bisogno di un account?',
    'forgotYourPassword'        => 'Password dimenticata?',
    'password'                  => 'Password',
    'repeatPassword'            => 'Ripeti Password',
    'emailOrUsername'           => 'Email o nome utente',
    'username'                  => 'Nome utente',
    'register'                  => 'Registrazione',
    'signIn'                    => 'Registrati',
    'alreadyRegistered'         => 'Hai già un account?',
    'weNeverShare'              => 'Non condivideremo mai il tuo indirizzo email con chiunque altro.',
    'resetYourPassword'         => 'Reimposta la tua password',
    'enterCodeEmailPassword'    => 'Inserisci il codice ricevuto via email, il tuo indirizzo email e la tua nuova password.',
    'token'                     => 'Token',
    'newPassword'               => 'Nuova Password',
    'newPasswordRepeat'         => 'Ripeti Nuova Password',
    'resetPassword'             => 'Reimposta Password',
];
