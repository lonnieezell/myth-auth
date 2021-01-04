<?php

return [
    // Exceptions
    'invalidModel'              => '{0} modeli kullanılmadan önce yüklenmelidir.',
    'userNotFound'              => 'Belirtilen kriterde, ID = {0, number}, kullanıcı bulunamadı.',
    'noUserEntity'              => 'Parola doğrulaması için User Entity oluşturulmalıdır.',
    'tooManyCredentials'        => 'Parola dışında yalnızca 1 kimlik bilgisine göre doğrulama yapabilirsiniz.',
    'invalidFields'             => '"{0}" alanı kimlik bilgilerini doğrulamak için kullanılamaz.',
    'unsetPasswordLength'       => 'Auth konfigürasyon dosyasındaki `minimumPasswordLength` ayarını yapmalısınız.',
    'unknownError'              => 'Üzgünüz, sana email gönderirken bir sorunla karşılaştık. Lütfen daha sonra tekrar deneyin.',
    'notLoggedIn'               => 'Bu sayfaya erişmek için giriş yapmalısın.',
    'notEnoughPrivilege'        => 'Bu sayfaya erişmek için yeterli iznin yok.',

    // Registration
    'registerDisabled'          => 'Üzgünüz, şu anda yeni kayıtlara izin veremiyoruz.',
    'registerSuccess'           => 'Aramıza hoşgeldin! Oluşturmuş olduğun hesabına giriş yapabilirsin.',
    'registerCLI'               => 'Yeni kullanıcı oluşturuldu: {0}, #{1}',

    // Activation
    'activationNoUser'          => 'Bu aktivasyon koduna sahip bir kullanıcı bulunamıyor.',
    'activationSubject'         => 'Hesabını etkinleştir.',
    'activationSuccess'         => 'Lütfen bizim gönderdiğimiz emaildeki aktivasyon linkine tıklayarak hesabınızı doğrulayın.',
    'activationResend'          => 'Resend activation message one more time.', // translate
    'notActivated'              => 'Bu kullanıcı henüz etkinleştirilmedi.',
    'errorSendingActivation'    => 'Aktivasyon mesajı gönderilemedi: {0}',

    // Login
    'badAttempt'                => 'Giriş yapılamıyor. Lütfen bilgilerinizi kontrol edin.',
    'loginSuccess'              => 'Tekrar Hoşgeldiniz!',
    'invalidPassword'           => 'Giriş yapılamıyor. Lütfen bilgilerinizi kontrol edin.',

    // Forgotten Passwords
    'forgotDisabled'            => 'Reseting password option has been disabled.', // translate
    'forgotNoUser'              => 'Bu emaile sahip bir kullanıcı bulunamıyor.',
    'forgotSubject'             => 'Parola Sıfırlama Adımları',
    'resetSuccess'              => 'Parolanız başarıyla değiştirildi. Yeni parolanızla giriş yapabilirsiniz.',
    'forgotEmailSent'           => 'Email adresinize bir güvenlik kodu gönderildi. Devam etmek için bu kodu aşağıdaki alana girin.',
    'errorEmailSent'            => 'Unable to send email with password reset instructions to: {0}', // translate
    'errorResetting'            => 'Unable to send reset instructions to {0}', // translate

    // Passwords
    'errorPasswordLength'       => 'Parola en az {0, number} karakter uzunluğunda olmalıdır.',
    'suggestPasswordLength'     => '255 karaktere kadar, hatırlanması kolay ve daha güvenli parolalar oluşturun.',
    'errorPasswordCommon'       => 'Parolanız yaygın kullanılan bir parola olmamalıdır.',
    'suggestPasswordCommon'     => 'Parolanız, 65 binin üzerinde yaygın kullanılan veya hack olaylarından sızan parolalara karşı kontrol edildi.',
    'errorPasswordPersonal'     => 'Parolanız, kişisel bilgilerinizi içeremez.',
    'suggestPasswordPersonal'   => 'Email adresinizdeki veya kullanıcı adınızdaki varyasyonlar parola için kullanılmamalıdır.',
    'errorPasswordTooSimilar'   => 'Parolanız, kullanıcı adınıza çok benziyor.',
    'suggestPasswordTooSimilar' => 'Kullanıcı adınızın bir kısmını veya tamamını parolanızda kullanmayın.',
    'errorPasswordPwned'        => '{0} parolası veri ihlalleri nedeniyle {2} vakada {1, number} defa görüldü.',
    'suggestPasswordPwned'      => '{0} asla parola olarak kullanılmamalıdır. Eğer herhangi bir yerde kullanıyorsanız hemen değiştirin.',
    'errorPasswordEmpty'        => 'Parola gereklidir.',
    'passwordChangeSuccess'     => 'Parola başarıyla değiştirildi.',
    'userDoesNotExist'          => 'Parola değiştirilemedi. Kullanıcı mevcut değil.',
    'resetTokenExpired'         => 'Üzgünüm. Sıfırlama kodunun süresi doldu.',

    // Groups
    'groupNotFound'             => 'Grup bulunamıyor: {0}.',

    // Permissions
    'permissionNotFound'        => 'İzin bulunamıyor: {0}',

    // Banned
    'userIsBanned'              => 'Bu kullanıcının erişimi engellendi. Lütfen, yönetici ile iletişime geçin',

    // Too many requests
    'tooManyRequests'           => 'Çok fazla istek geliyor. Lütfen {0, number} saniye bekleyin.',

    // Login views
    'home'                      => 'Ana Sayfa',
    'current'                   => 'Şu An',
    'forgotPassword'            => 'Parolanı mı unuttun?',
    'enterEmailForInstructions' => 'Sorun yok! Aşağıya emailini gir ve sana parolanı sıfırlaman için talimatları göndereceğiz.',
    'email'                     => 'Email',
    'emailAddress'              => 'Email Adresi',
    'sendInstructions'          => 'Talimatları Gönder',
    'loginTitle'                => 'Giriş Yap',
    'loginAction'               => 'Giriş Yap',
    'rememberMe'                => 'Beni hatırla',
    'needAnAccount'             => 'Bir hesaba mı ihtiyacınız var?',
    'forgotYourPassword'        => 'Parolamı unuttum?',
    'password'                  => 'Parola',
    'repeatPassword'            => 'Parolayı Tekrarla',
    'emailOrUsername'           => 'Email yada kullanıcı adı',
    'username'                  => 'Kullanıcı Adı',
    'register'                  => 'Kayıt Ol',
    'signIn'                    => 'Oturum Aç',
    'alreadyRegistered'         => 'Çoktan Kaydoldun mu?',
    'weNeverShare'              => 'Email adresini hiçbir zaman kimseyle paylaşmayacağız.',
    'resetYourPassword'         => 'Parolanı Sıfırla',
    'enterCodeEmailPassword'    => 'Email ile gelen kodu, email adresini ve yeni parolanı gir.',
    'token'                     => 'Token',
    'newPassword'               => 'Yeni Parola',
    'newPasswordRepeat'         => 'Yeni Parolayı Tekrarla',
    'resetPassword'             => 'Parolayı Sıfırla',
];
