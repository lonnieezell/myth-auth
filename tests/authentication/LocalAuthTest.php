<?php

use CodeIgniter\Router\Exceptions\RedirectException;
use Myth\Auth\Authentication\LocalAuthenticator;
use Myth\Auth\Config\Services;
use Myth\Auth\Exceptions\AuthException;
use Myth\Auth\Models\UserModel;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class LocalAuthTest extends AuthTestCase
{
    /**
     * @var LocalAuthenticator
     */
    protected $auth;

    /**
     * @var bool
     */
    protected $refresh = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth = Services::authentication('local');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $_SESSION = [];
    }

    public function testValidateNoPassword()
    {
        $this->hasInDatabase('users', [
            'email'         => 'fred@example.com',
            'password_hash' => 'secret',
        ]);

        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'foo' => 'bar']));
    }

    public function testValidateOneCredential()
    {
        $this->hasInDatabase('users', [
            'email'         => 'fred@example.com',
            'password_hash' => 'secret',
        ]);

        $this->assertFalse($this->auth->validate(['password' => 'fred@example.com']));
    }

    public function testValidateInvalidCredential()
    {
        $this->expectException(AuthException::class);

        $this->hasInDatabase('users', [
            'email'         => 'fred@example.com',
            'password_hash' => 'secret',
        ]);

        $this->assertFalse($this->auth->validate(['password' => 'fred@example.com', 'foo' => 'bar']));
    }

    public function testValidateUserNotFound()
    {
        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'password' => 'bar']));
        $this->assertSame(lang('Auth.badAttempt'), $this->auth->error());
    }

    public function testValidateBadPassword()
    {
        $this->createUser();

        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'password' => 'bar']));
        $this->assertSame(lang('Auth.invalidPassword'), $this->auth->error());
    }

    public function testValidateSuccess()
    {
        $user = $this->createUser();

        // Should return a boolean
        $this->assertTrue($this->auth->validate(['email' => 'fred@example.com', 'password' => 'secret']));

        // It should return a user instance
        $foundUser = $this->auth->validate(['email' => 'fred@example.com', 'password' => 'secret'], true);
        $this->assertSame($user->email, $foundUser->email);
    }

    public function testCheckNotRemembered()
    {
        $_SESSION = [];
        $this->createUser();

        $this->assertFalse($this->auth->check());
    }

    public function testCheckWithForcedPasswordReset()
    {
        $users = model(UserModel::class);
        $user  = $this->createUser();
        $user->forcePasswordReset();
        $users->save($user);

        $_SESSION = [
            'logged_in' => $user->id,
        ];
        $this->assertNotEmpty($user->reset_hash);
        $this->expectException(RedirectException::class);
        $this->expectExceptionMessage(route_to('reset-password') . '?token=' . $user->reset_hash);

        $this->auth->check();

        unset($_SESSION['logged_in']);
    }
}
