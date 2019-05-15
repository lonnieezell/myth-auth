<?php

use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Authentication\LocalAuthenticator;

class LocalAttemptTest extends \CIDatabaseTestCase
{
    /**
     * @var LocalAuthenticator
     */
    protected $auth;

    public function setUp(): void
    {
        parent::setUp();

        $this->auth = \Myth\Auth\Config\Services::authentication('local');
    }

    public function testValidateNoPassword()
    {
        $this->hasInDatabase('users', [
            'id' => 1,
            'email' => 'fred@example.com',
            'password_hash' => 'secret'
        ]);

        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'foo' => 'bar']));
    }

    public function testValidateOneCredential()
    {
        $this->hasInDatabase('users', [
            'id' => 1,
            'email' => 'fred@example.com',
            'password_hash' => 'secret'
        ]);

        $this->assertFalse($this->auth->validate(['password' => 'fred@example.com']));
    }

    public function testValidateInvalidCredential()
    {
        $this->expectException(\Myth\Auth\Exceptions\AuthException::class);

        $this->hasInDatabase('users', [
            'id' => 1,
            'email' => 'fred@example.com',
            'password_hash' => 'secret'
        ]);

        $this->assertFalse($this->auth->validate(['password' => 'fred@example.com', 'foo' => 'bar']));
    }

    public function testValidateUserNotFound()
    {
        $this->assertFalse($this->auth->validate(['email' => 'fred@example.com', 'password' => 'bar']));
        $this->assertEquals(lang('Auth.invalidUser'), $this->auth->error());
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
}
