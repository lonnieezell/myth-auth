<?php

use ModuleTests\Support\AuthTestCase;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Authentication\LocalAuthenticator;

class LocalAuthTest extends AuthTestCase
{
    /**
     * @var LocalAuthenticator
     */
    protected $auth;

    /**
     * @var \Tests\Support\Session\MockSession
     */
    protected $session;

    protected $refresh = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->auth = \Myth\Auth\Config\Services::authentication('local');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $_SESSION = [];
    }

    public function testValidateNoPassword()
    {
        $this->hasInDatabase('users', [
            'email' => 'fred@example.com',
            'password_hash' => 'secret'
        ]);

        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'foo' => 'bar']));
    }

    public function testValidateOneCredential()
    {
        $this->hasInDatabase('users', [
            'email' => 'fred@example.com',
            'password_hash' => 'secret'
        ]);

        $this->assertFalse($this->auth->validate(['password' => 'fred@example.com']));
    }

    public function testValidateInvalidCredential()
    {
        $this->expectException(\Myth\Auth\Exceptions\AuthException::class);

        $this->hasInDatabase('users', [
            'email' => 'fred@example.com',
            'password_hash' => 'secret'
        ]);

        $this->assertFalse($this->auth->validate(['password' => 'fred@example.com', 'foo' => 'bar']));
    }

    public function testValidateUserNotFound()
    {
        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'password' => 'bar']));
        $this->assertEquals(lang('Auth.badAttempt'), $this->auth->error());
    }

    public function testValidateBadPassword()
    {
        $user = $this->createUser();

        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'password' => 'bar']));
        $this->assertEquals(lang('Auth.invalidPassword'), $this->auth->error());
    }

    public function testValidateSuccess()
    {
        $user = $this->createUser();

        // Should return a boolean
        $this->assertTrue($this->auth->validate(['email' => 'fred@example.com', 'password' => 'secret']));

        // It should return a user instance
        $foundUser = $this->auth->validate(['email' => 'fred@example.com', 'password' => 'secret'], true);
        $this->assertEquals($user->email, $foundUser->email);
    }

    public function testCheckNotRemembered()
    {
        $_SESSION = [];
        $user = $this->createUser();

        $this->assertFalse($this->auth->check());
    }

    public function testCheckWithForcedPasswordReset()
    {
        $users = model(UserModel::class);
        $user = $this->createUser();
        $user->forcePasswordReset();
        $users->save($user);

        $_SESSION = [
            'logged_in' => $user->id
        ];
        $this->assertTrue(! empty($user->reset_hash));
        $this->expectException(\CodeIgniter\Router\Exceptions\RedirectException::class);
        $this->expectExceptionMessage(route_to('reset-password') .'?token='. $user->reset_hash);

        $this->auth->check();

        unset($_SESSION['logged_in']);
    }
}
