<?php

namespace Myth\Auth\Language\id;

return [
    // Exceptions
    'invalidModel'        => 'Model {0} harus dimuat sebelum digunakan.',
    'userNotFound'        => 'Tidak dapat menemukan pengguna dengan ID = {0, number}.',
    'noUserEntity'        => 'Entitas Pengguna harus disediakan untuk validasi kata sandi.',
    'tooManyCredentials'  => 'Anda hanya dapat memvalidasi terhadap 1 kredensial selain kata sandi.',
    'invalidFields'       => 'Bidang "{0}" tidak dapat digunakan untuk memvalidasi kredensial.',
    'unsetPasswordLength' => 'Anda harus menyetel setelan `minimumPasswordLength` di file konfigurasi Auth.',
    'unknownError'        => 'Maaf, kami mengalami masalah saat mengirim email kepada Anda. Silakan coba lagi nanti.',
    'notLoggedIn'         => 'Anda harus masuk untuk mengakses halaman itu.',
    'notEnoughPrivilege'  => 'Anda tidak memiliki izin yang memadai untuk mengakses halaman itu.',

    // Registration
    'registerDisabled' => 'Maaf, akun pengguna baru tidak diperbolehkan untuk saat ini.',
    'registerSuccess'  => 'Selamat bergabung! Harap masuk dengan kredensial baru Anda.',
    'registerCLI'      => 'Pengguna baru dibuat: {0}, #{1}',

    // Activation
    'activationNoUser'       => 'Tidak dapat menemukan pengguna dengan kode aktivasi tersebut.',
    'activationSubject'      => 'Aktivasi akun anda',
    'activationSuccess'      => 'Silakan konfirmasi akun Anda dengan mengklik link aktivasi di email yang kami kirimkan.',
    'activationResend'       => 'Kirim ulang pesan aktivasi sekali lagi.',
    'notActivated'           => 'Akun pengguna ini belum diaktifkan.',
    'errorSendingActivation' => 'Gagal mengirim pesan aktivasi ke: {0}',

    // Login
    'badAttempt'      => 'Tidak dapat memasukkan Anda. Harap periksa kredensial Anda.',
    'loginSuccess'    => 'Selamat datang kembali!',
    'invalidPassword' => 'Tidak dapat memasukkan Anda. Harap periksa kata sandi Anda.',

    // Forgotten Passwords
    'forgotDisabled'  => 'Opsi pengaturan ulang kata sandi telah dinonaktifkan.',
    'forgotNoUser'    => 'Tidak dapat menemukan pengguna dengan email tersebut.',
    'forgotSubject'   => 'Instruksi Reset Kata Sandi',
    'resetSuccess'    => 'Kata sandi Anda berhasil diubah.  Silakan login dengan kata sandi baru.',
    'forgotEmailSent' => 'Token keamanan telah dikirim ke email Anda.  Masukkan ke dalam kotak di bawah untuk melanjutkan.',
    'errorEmailSent'  => 'Tidak dapat mengirim email dengan instruksi setel ulang kata sandi ke: {0}',
    'errorResetting'  => 'Tidak dapat mengirim instruksi setel ulang ke: {0}',

    // Passwords
    'errorPasswordLength'       => 'Sandi harus terdiri dari setidaknya {0, number} karakter.',
    'suggestPasswordLength'     => 'Lewati frasa - hingga 255 karakter - buat kata sandi yang lebih aman dan mudah diingat.',
    'errorPasswordCommon'       => 'Kata sandi tidak boleh menjadi kata sandi yang umum.',
    'suggestPasswordCommon'     => 'Kata sandi diperiksa terhadap lebih dari 65 ribu kata sandi atau kata sandi yang umum digunakan yang telah bocor melalui peretasan.',
    'errorPasswordPersonal'     => 'Kata sandi tidak boleh berisi informasi pribadi yang di-hash.',
    'suggestPasswordPersonal'   => 'Variasi pada alamat email atau nama pengguna Anda tidak boleh digunakan untuk kata sandi.',
    'errorPasswordTooSimilar'   => 'Kata sandi terlalu mirip dengan nama pengguna.',
    'suggestPasswordTooSimilar' => 'Jangan gunakan bagian dari nama pengguna Anda dalam kata sandi Anda.',
    'errorPasswordPwned'        => 'Sandi {0} telah dibuka karena pelanggaran data dan telah dilihat {1, number} kali dalam {2} sandi yang disusupi.',
    'suggestPasswordPwned'      => '{0} tidak boleh digunakan sebagai sandi.  Jika Anda menggunakannya di mana saja segera ubah.',
    'errorPasswordEmpty'        => 'Kata sandi diperlukan.',
    'passwordChangeSuccess'     => 'Kata sandi berhasil diubah',
    'userDoesNotExist'          => 'Kata sandi tidak berubah. Pengguna tidak ditemukan',
    'resetTokenExpired'         => 'Maaf. Token setel ulang Anda telah kedaluwarsa.',

    // Groups
    'groupNotFound' => 'Tidak dapat menemukan grup: {0}.',

    // Permissions
    'permissionNotFound' => 'Tidak dapat menemukan izin: {0}',

    // Banned
    'userIsBanned' => 'Pengguna telah dibanned. Hubungi administrator',

    // Too many requests
    'tooManyRequests' => 'Terlalu banyak permintaan.  Harap tunggu {0, number} detik.',

    // Login views
    'home'                      => 'Home',
    'current'                   => 'Current',
    'forgotPassword'            => 'Lupa kata sandi Anda?',
    'enterEmailForInstructions' => 'Tidak masalah! Masukkan email Anda di bawah ini dan kami akan mengirimkan instruksi untuk mengatur ulang kata sandi Anda.',
    'email'                     => 'Email',
    'emailAddress'              => 'Alamat Email',
    'sendInstructions'          => 'Kirim Instruksi',
    'loginTitle'                => 'Masuk',
    'loginAction'               => 'Masuk',
    'rememberMe'                => 'Ingat saya',
    'needAnAccount'             => 'Butuh akun?',
    'forgotYourPassword'        => 'Lupa kata sandi Anda?',
    'password'                  => 'Kata sandi',
    'repeatPassword'            => 'Ulangi kata sandi',
    'emailOrUsername'           => 'Email atau nama pengguna',
    'username'                  => 'Nama pengguna',
    'register'                  => 'Daftar',
    'signIn'                    => 'Masuk',
    'alreadyRegistered'         => 'Sudah terdaftar?',
    'weNeverShare'              => 'Kami tidak akan pernah membagi email Anda dengan orang lain.',
    'resetYourPassword'         => 'Mereset password Anda',
    'enterCodeEmailPassword'    => 'Masukkan kode yang Anda terima melalui email, alamat email Anda, dan kata sandi baru Anda.',
    'token'                     => 'Token',
    'newPassword'               => 'kata sandi baru',
    'newPasswordRepeat'         => 'Ulangi Kata Sandi Baru',
    'resetPassword'             => 'Atur Ulang Kata Sandi',
];
