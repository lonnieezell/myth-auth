<?php

return [
    // Exceptions
    'invalidModel'                => 'Pred použitím je potrebné načítať model {0}.',
    'userNotFound'                => 'Nepodarilo sa nájsť používateľa s ID = {0, number}.',
    'noUserEntity'                => 'Na overenie hesla je potrebné uviesť používateľskú entitu',
    'tooManyCredentials'          => 'Môžete overiť iba na základe 1 poverenia iného ako heslo.',
    'invalidFields'               => 'Pole "{0}" nemožno použiť na overenie poverení.',
    'unsetPasswordLength'         => 'V konfiguračnom súbore Auth musíte nastaviť "minimumPasswordLength".',
    'unknownError'                => 'Ľutujeme, pri zasielaní e-mailu sa vyskytol problém. Skúste neskôr prosím.',
    'notLoggedIn'                 => 'Pre prístup na túto stránku musíte byť prihlásený.',
    'notEnoughPrivilege'          => 'Na prístup na túto stránku nemáte dostatočné povolenie.',

    // Registration
    'registerDisabled'            => 'Je nám ľúto, nové používateľské účty nie sú v súčasnosti povolené.',
    'registerSuccess'             => 'Vitajte na palube! Prihláste sa pomocou svojich nových poverení.',
    'registerCLI'                 => 'Vytvorený nový používateľ: {0}, #{1}',

    // Activation
    'activationNoUser'            => 'Nepodarilo sa nájsť používateľa s týmto aktivačným kódom.',
    'activationSubject'           => 'Aktivujte svoj účet',
    'activationSuccess'           => 'Potvrďte svoj účet kliknutím na aktivačný odkaz v e-maile, ktorý sme poslali.',
    'activationResend'            => 'Znova poslať aktivačnú správu',
    'notActivated'                => 'Tento používateľský účet ešte nie je aktivovaný.',
    'errorSendingActivation'      => 'Nepodarilo sa odoslať aktivačnú správu na adresu: {0}',

    // Login
    'badAttempt'                  => 'Nie je možné sa prihlásiť. Skontrolujte svoje prihlasovacie údaje.',
    'loginSuccess'                => 'Vitajte späť!',
    'invalidPassword'             => 'Nie je možné sa prihlásiť. Skontrolujte svoje heslo.',

    // Forgotten Passwords
    'forgotDisabled'              => 'Možnosť resetovania hesla bola zakázaná',
    'forgotNoUser'                => 'Nie je možné nájsť používateľa s týmto e-mailom.',
    'forgotSubject'               => 'Pokyny na obnovenie hesla',
    'resetSuccess'                => 'Vaše heslo bolo úspešne zmenené. Prihláste sa pomocou nového hesla.',
    'forgotEmailSent'             => 'Bezpečnostný token vám bol zaslaný e-mailom. Pokračujte zadaním do poľa nižšie.',
    'errorEmailSent'              => 'Nepodarilo sa odoslať e-mail s pokynmi na obnovenie hesla na adresu: {0}',
    'errorResetting'              => 'Pokyny na resetovanie nie je možné odoslať na adresu {0}',

    // Passwords
    'errorPasswordLength'         => 'Heslá musia mať najmenej {0, number} znakov.',
    'suggestPasswordLength'       => 'Heslo - až 255 znakov - umožňuje vytvárať bezpečnejšie heslá, ktoré si ľahko zapamätáte.',
    'errorPasswordCommon'         => 'Heslo nesmie byť bežné heslo.',
    'suggestPasswordCommon'       => 'Heslo bolo skontrolované oproti viac ako 65 000 bežne používaným heslám alebo heslám, ktoré boli uniknuté hackermi.',
    'errorPasswordPersonal'       => 'Heslá nemôžu obsahovať opätovne hašované osobné informácie.',
    'suggestPasswordPersonal'     => 'Pre heslá by sa nemali používať variácie vašej e-mailovej adresy alebo používateľského mena.',
    'errorPasswordTooSimilar'     => 'Heslo je príliš podobné používateľskému menu.',
    'suggestPasswordTooSimilar'   => 'Vo svojom hesle nepoužívajte časti svojho používateľského mena.',
    'errorPasswordPwned'          => 'Heslo {0} bolo odhalené z dôvodu porušenia ochrany údajov a bolo videné {1, number} krát v {2} zneužitých heslách.',
    'suggestPasswordPwned'        => '{0} by sa nikdy nemalo používať ako heslo. Ak ho používate kdekoľvek, okamžite to zmeňte.',
    'errorPasswordPwnedDatabase'  => 'databáza',
    'errorPasswordPwnedDatabases' => 'databázy',
    'errorPasswordEmpty'          => 'Vyžaduje sa heslo.',
    'passwordChangeSuccess'       => 'Heslo bolo úspešne zmenené',
    'userDoesNotExist'            => 'Heslo nebolo zmenené. Používateľ neexistuje.',
    'resetTokenExpired'           => 'Prepáčte. Platnosť vášho resetovacieho tokenu uplynula.',

    // Groups
    'groupNotFound'               => 'Skupinu sa nepodarilo nájsť: {0}.',

    // Permissions
    'permissionNotFound'          => 'Nepodarilo sa nájsť povolenie: {0}',

    // Banned
    'userIsBanned'                => 'Používateľ bol zakázaný. Kontaktujte správcu.',

    // Too many requests
    'tooManyRequests'             => 'Príliš veľa požiadaviek. Počkajte prosím {0, number} sekúnd.',

    // Login views
    'home'                        => 'Domov',
    'current'                     => 'Aktuálne',
    'forgotPassword'              => 'Zabudli ste heslo?',
    'enterEmailForInstructions'   => 'Žiaden problém! Nižšie zadajte svoj e-mail a my vám pošleme pokyny na obnovenie hesla.',
    'email'                       => 'Email',
    'emailAddress'                => 'Emailová adresa',
    'sendInstructions'            => 'Odoslať pokyny',
    'loginTitle'                  => 'Prihlásiť sa',
    'loginAction'                 => 'Prihlásiť sa',
    'rememberMe'                  => 'Pamätáť si ma',
    'needAnAccount'               => 'Potrebujete účet?',
    'forgotYourPassword'          => 'Zabudli ste heslo?',
    'password'                    => 'Heslo',
    'repeatPassword'              => 'Zopakujte heslo',
    'emailOrUsername'             => 'E-mail alebo užívateľské meno',
    'username'                    => 'Užívateľské meno',
    'register'                    => 'Registrovať',
    'signIn'                      => 'Prihlásiť sa',
    'alreadyRegistered'           => 'Už zaregistrovaný?',
    'weNeverShare'                => 'Váš e-mail nikdy nebudeme zdieľať s nikým iným.',
    'resetYourPassword'           => 'Obnoviť heslo',
    'enterCodeEmailPassword'      => 'Zadajte kód, ktorý ste dostali e-mailom, vašu e-mailovú adresu a nové heslo.',
    'token'                       => 'Token',
    'newPassword'                 => 'Nové heslo',
    'newPasswordRepeat'           => 'Zopakujte nové heslo',
    'resetPassword'               => 'Obnoviť heslo',
];
