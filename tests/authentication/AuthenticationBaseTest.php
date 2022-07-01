<?php

use Myth\Auth\Authentication\LocalAuthenticator;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class AuthenticationBaseTest extends AuthTestCase
{
    /**
     * @var LocalAuthenticator
     */
    protected $auth;

    protected $refresh = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth = \Myth\Auth\Config\Services::authentication('local', null, null, false);
        $this->setPrivateProperty($this->auth, 'user', null);

        $db = db_connect();
        $db->table('auth_logins')->truncate();
    }

    public function testIsLoggedInFailsWithInvalidUser()
    {
        $user = $this->createUser();

        $_SESSION['logged_in'] = 12035;

        $this->assertFalse($this->auth->isLoggedIn());
    }

    public function testIsLoggedInFresh()
    {
        $user = $this->createUser();

        $_SESSION['logged_in'] = $user->id;

        $this->assertTrue($this->auth->isLoggedIn());
    }

    public function testLoginReturnsFalseWithNoUser()
    {
        $this->assertFalse($this->auth->login());
    }

    public function testLoginByID()
    {
        $user = $this->createUser();

        $this->assertTrue($this->auth->loginByID($user->id));

        // Should have logged login attempt
        $this->seeInDatabase('auth_logins', [
            'email'   => $user->email,
            'user_id' => $user->id,
            'success' => 1,
        ]);
    }

    public function testLoginSuccessNoRemember()
    {
        $user = $this->createUser();

        $this->assertTrue($this->auth->login($user));

        // Should have logged login attempt
        $this->seeInDatabase('auth_logins', [
            'email'   => $user->email,
            'user_id' => $user->id,
            'success' => 1,
        ]);

        $this->assertSame(4, $_SESSION['logged_in']);

        $this->dontSeeInDatabase('auth_tokens', [
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->auth->isLoggedIn());

        $this->assertSame($user, $this->auth->user());
        $this->assertSame($user->id, $this->auth->id());
    }

    public function testLoginSuccessAndRemember()
    {
        $user = $this->createUser();

        $config                   = config('Auth');
        $config->allowRemembering = true;
        $this->setPrivateProperty($this->auth, 'config', $config);

        $this->assertTrue($this->auth->login($user, true));

        // Should have logged login attempt
        $this->seeInDatabase('auth_logins', [
            'email'   => $user->email,
            'user_id' => $user->id,
            'success' => 1,
        ]);

        $this->assertSame($user->id, $_SESSION['logged_in']);

        $this->seeInDatabase('auth_tokens', [
            'user_id' => $user->id,
        ]);
    }

    public function testLogoutLogsOut()
    {
        $user = $this->createUser();

        $this->assertTrue($this->auth->login($user));

        $this->auth->logout();

        $this->assertFalse($this->auth->isLoggedIn());
        $this->assertNull($this->auth->user());
    }
}
