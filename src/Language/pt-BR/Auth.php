<?php

return [
    // Exceptions
    'invalidModel'        => 'O {0} model deve ser carregado antes da utilização.',
    'userNotFound'        => 'Não foi possível localizar um usuário com ID = {0, number}.',
    'noUserEntity'        => 'A entidade do usuário deve ser fornecida para validação da senha.',
    'tooManyCredentials'  => 'Você só pode validar com 1 credencial que não seja uma senha.',
    'invalidFields'       => 'O campo "{0}" não pode ser utilizado para validar credenciais.',
    'unsetPasswordLength' => 'Você deve definir a configuração `minimumPasswordLength` no arquivo de configuração do Auth.',
    'unknownError'        => 'Desculpe, nós encontramos um problema ao enviar o e-mail para você. Por favor, tente novamente mais tarde.',
    'notLoggedIn'         => 'Você precisa estar logado para acessar esta página.',
    'notEnoughPrivilege'  => 'Você não possui permissões suficientes para acessar esta página.',

    // Registration
    'registerDisabled' => 'Desculpe, novas contas de usuário não são permitidas no momento.',
    'registerSuccess'  => 'Bem-vindo abordo! Por favor, entre com suas novas credenciais.',
    'registerCLI'      => 'Novo usuário criado: {0}, #{1}',

    // Activation
    'activationNoUser'       => 'Não foi possível localizar um usuário com esse código de ativação.',
    'activationSubject'      => 'Ative sua conta',
    'activationSuccess'      => 'Por favor, confirme sua conta clicando no link de ativação no e-mail que nós enviamos.',
    'activationResend'       => 'Reenviar mensagem de ativação mais uma vez.',
    'notActivated'           => 'Esta conta não está ativada ainda.',
    'errorSendingActivation' => 'Falha ao enviar mensagem de ativação para: {0}',

    // Login
    'badAttempt'   => 'Não foi possível fazer o login. Por favor, verifique suas credenciais.',
    'loginSuccess' => 'Bem-vindo de volta!',

    // Forgotten Passwords
    'forgotDisabled'  => 'Reseting password option has been disabled.', // translate
    'forgotNoUser'    => 'Não foi possível localizar um  usuário com esse e-mail.',
    'forgotSubject'   => 'Instruções de redefinição de senha',
    'resetSuccess'    => 'Sua senha foi alterada com sucesso. Por favor, entre com sua nova senha.',
    'forgotEmailSent' => 'Um token de segurança foi enviado para o seu e-mail. Cole-o no campo abaixo para continuar.',
    'errorEmailSent'  => 'Unable to send email with password reset instructions to: {0}', // translate
    'errorResetting'  => 'Unable to send reset instructions to {0}', // translate

    // Passwords
    'errorPasswordLength'       => 'A senha deve conter pelo menos {0, number} caracteres.',
    'suggestPasswordLength'     => 'As frases secretas - com até 255 caracteres - tornam senhas mais seguras e fáceis de lembrar.',
    'errorPasswordCommon'       => 'A senha não deve ser uma senha comum.',
    'suggestPasswordCommon'     => 'A senha foi verificada em mais de 65k senhas comumente usadas ou vazadas por hacks.',
    'errorPasswordPersonal'     => 'As senhas não podem conter informações pessoais remontadas.',
    'suggestPasswordPersonal'   => 'Variações do seu e-mail ou nome de usuário não devem ser usadas como senhas.',
    'errorPasswordTooSimilar'   => 'A senha é muito parecida com o nome de usuário.',
    'suggestPasswordTooSimilar' => 'Não utilize partes do seu nome de usuário na sua senha.',
    'errorPasswordPwned'        => 'A senha {0} foi exposta devido a uma violação de dados e foi vista {1, number} vezes em {2} de senhas comprometidas.',
    'suggestPasswordPwned'      => '{0} nunca deve ser usado como senha. Caso você utilize em algum lugar, mude imediatamente.',
    'errorPasswordEmpty'        => 'A senha é requerida.',
    'passwordChangeSuccess'     => 'Senha alterada com sucesso',
    'userDoesNotExist'          => 'Senha não alterada. Usuário não existe',
    'resetTokenExpired'         => 'Desculpe. Seu token está expirado.',

    // Groups
    'groupNotFound' => 'Não foi possível localizar o grupo: {0}.',

    // Permissions
    'permissionNotFound' => 'Não foi possível localizar a permissão: {0}',

    // Banned
    'userIsBanned' => 'Usuário banido. Entre em contato com o administrador',

    // Too many requests
    'tooManyRequests' => 'Muitas requisições. Por favor, aguarde {0, number} segundos.',

    // Login views
    'home'                      => 'Home',
    'current'                   => 'Atual',
    'forgotPassword'            => 'Esqueceu sua senha?',
    'enterEmailForInstructions' => 'Sem problemas! Digite seu e-mail abaixo e nós enviaremos instruções para resetar sua senha.',
    'email'                     => 'E-mail',
    'emailAddress'              => 'E-mail',
    'sendInstructions'          => 'Enviar instruções',
    'loginTitle'                => 'Entrar',
    'loginAction'               => 'Entrar',
    'rememberMe'                => 'Lembrar-me',
    'needAnAccount'             => 'Precisa de uma conta?',
    'forgotYourPassword'        => 'Esqueceu sua senha?',
    'password'                  => 'Senha',
    'repeatPassword'            => 'Senha novamente',
    'emailOrUsername'           => 'E-mail ou nome de usuário',
    'username'                  => 'Nome de usuário',
    'register'                  => 'Registrar-se',
    'signIn'                    => 'Entre',
    'alreadyRegistered'         => 'Já é registrado?',
    'weNeverShare'              => 'Nós não compartilharemos seu e-mail com mais ninguém.',
    'resetYourPassword'         => 'Resetar sua senha',
    'enterCodeEmailPassword'    => 'Entre com o código recebido via e-mail, seu e-mail e sua nova senha.',
    'token'                     => 'Token',
    'newPassword'               => 'Nova senha',
    'newPasswordRepeat'         => 'Repetir nova senha',
    'resetPassword'             => 'Resetar senha',
];
