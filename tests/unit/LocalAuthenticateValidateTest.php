<?php

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Test\CIUnitTestCase;
use Mockery as m;
use Mockery\MockInterface;
use Myth\Auth\Authentication\LocalAuthenticator;
use Myth\Auth\Config\Services;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\LoginModel;
use Myth\Auth\Models\UserModel;

/**
 * @internal
 */
final class LocalAuthenticateValidateTest extends CIUnitTestCase
{
    protected MockInterface $userModel;
    protected MockInterface $loginModel;

    /**
     * @var LocalAuthenticator
     */
    protected $auth;

    protected IncomingRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel  = m::mock(UserModel::class);
        $this->loginModel = m::mock(LoginModel::class);
        $this->auth       = Services::authentication('local', $this->userModel, $this->loginModel, false);

        $this->request = m::mock('CodeIgniter\HTTP\IncomingRequest');
        Services::injectMock('CodeIgniter\HTTP\IncomingRequest', $this->request);
    }

    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testCannotValidateWithoutPassword()
    {
        $this->assertFalse($this->auth->validate([]));
    }

    public function testThrowsWithTooManyCredentials()
    {
        $this->expectException('\Myth\Auth\Exceptions\AuthException');
        $this->expectExceptionMessage('You may only validate against 1 credential other than a password.');

        $this->auth->validate([
            'password' => 'secret',
            'email'    => 'joe@example.com',
            'username' => 'fred',
        ]);
    }

    public function testThrowsWithInvalidFields()
    {
        $this->expectException('\Myth\Auth\Exceptions\AuthException');
        $this->expectExceptionMessage('The "foo" field cannot be used to validate credentials.');

        $this->auth->validate([
            'password' => 'secret',
            'foo'      => 'bar',
        ]);
    }

    public function testFailsBadUser()
    {
        $this->userModel->shouldReceive('where')->once()->with(m::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn(null);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email'    => 'joe@example.com',
        ]);

        $this->assertFalse($result);
        $this->assertSame(lang('Auth.badAttempt'), $this->auth->error());
    }

    public function testFailsPasswordValidation()
    {
        $user = new User(['password_hash' => password_hash('nope!', PASSWORD_DEFAULT)]);

        $this->userModel->shouldReceive('where')->once()->with(m::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email'    => 'joe@example.com',
        ]);

        $this->assertFalse($result);
        $this->assertSame(lang('Auth.invalidPassword'), $this->auth->error());
    }

    public function testValidateSuccess()
    {
        $user = new User(['password' => 'secret']);

        $this->userModel->shouldReceive('where')->once()->with(m::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email'    => 'joe@example.com',
        ]);

        $this->assertTrue($result);
    }

    public function testValidateSuccessReturnsUser()
    {
        $user = new User(['password' => 'secret']);

        $this->userModel->shouldReceive('where')->once()->with(m::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email'    => 'joe@example.com',
        ], true);

        $this->assertInstanceOf(User::class, $result);
    }

    public function testValidateSuccessReHashPassword()
    {
        $user = new User(['password' => 'secret']);

        $this->userModel->shouldReceive('where')->once()->with(m::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email'    => 'joe@example.com',
        ], true);

        $this->assertInstanceOf(User::class, $result);
    }
}
