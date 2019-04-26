<?php

use Myth\Auth\Authentication\LocalAuthenticator;

class LocalAttemptTest extends \CIDatabaseTestCase
{
    /**
     * @var LocalAuthenticator
     */
    protected $auth;

    protected $namespace = 'Myth\Auth';

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
}
