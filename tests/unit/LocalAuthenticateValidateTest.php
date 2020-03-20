<?php

use Mockery as m;
use Myth\Auth\Config\Services;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\LoginModel;
use CodeIgniter\Test\CIUnitTestCase;
use Myth\Auth\Authentication\LocalAuthenticator;

class LocalAuthenticateValidateTest extends CIUnitTestCase
{
    /**
     * @var UserModel
     */
    protected $userModel;
    /**
     * @var LoginModel
     */
    protected $loginModel;
    /**
     * @var LocalAuthenticator
     */
    protected $auth;
    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->userModel = m::mock(UserModel::class);
        $this->loginModel = m::mock(LoginModel::class);
        $this->auth = Services::authentication('local', $this->userModel, $this->loginModel,false);

        $this->request = m::mock('CodeIgniter\HTTP\IncomingRequest');
        Services::injectMock('CodeIgniter\HTTP\IncomingRequest', $this->request);
    }

    public function tearDown(): void
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
            'email' => 'joe@example.com',
            'username' => 'fred'
        ]);
    }

    public function testThrowsWithInvalidFields()
    {
        $this->expectException('\Myth\Auth\Exceptions\AuthException');
        $this->expectExceptionMessage('The "foo" field cannot be used to validate credentials.');

        $this->auth->validate([
            'password' => 'secret',
            'foo' => 'bar'
        ]);
    }

    public function testFailsBadUser()
    {
        $this->userModel->shouldReceive('where')->once()->with(\Mockery::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn(null);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com'
        ]);

        $this->assertFalse($result);
        $this->assertEquals(lang('Auth.badAttempt'), $this->auth->error());
    }

    public function testFailsPasswordValidation()
    {
        $user = new \Myth\Auth\Entities\User(['password_hash' => password_hash('nope!', PASSWORD_DEFAULT)]);

        $this->userModel->shouldReceive('where')->once()->with(\Mockery::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com'
        ]);

        $this->assertFalse($result);
        $this->assertEquals(lang('Auth.invalidPassword'), $this->auth->error());
    }

    public function testValidateSuccess()
    {
        $user = new \Myth\Auth\Entities\User(['password' => 'secret']);

        $this->userModel->shouldReceive('where')->once()->with(\Mockery::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com'
        ]);

        $this->assertTrue($result);
    }

    public function testValidateSuccessReturnsUser()
    {
        $user = new \Myth\Auth\Entities\User(['password' => 'secret']);

        $this->userModel->shouldReceive('where')->once()->with(\Mockery::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com'
        ], true);

        $this->assertTrue($result instanceof \Myth\Auth\Entities\User);
    }

    public function testValidateSuccessReHashPassword()
    {
        $user = new \Myth\Auth\Entities\User(['password' => 'secret']);

        $this->userModel->shouldReceive('where')->once()->with(\Mockery::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);

        $result = $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com'
        ], true);

        $this->assertTrue($result instanceof \Myth\Auth\Entities\User);
    }
}
