<?php

use Mockery as m;
use Myth\Auth\Config\Services;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Authentication\LocalAuthenticator;

class LocalAuthenticateValidateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UserModel
     */
    protected $userModel;
    /**
     * @var LocalAuthenticator
     */
    protected $auth;
    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->userModel = m::mock(UserModel::class);
        $this->auth = Services::authentication('local', $this->userModel, false);

        $this->request = m::mock('CodeIgniter\HTTP\IncomingRequest');
        Services::injectMock('CodeIgniter\HTTP\IncomingRequest', $this->request);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testCannotValidateWithoutPassword()
    {
        $this->assertFalse($this->auth->validate([]));
    }

    /**
     * @expectedException \Myth\Auth\Exceptions\AuthException
     * @expectedExceptionMessage Auth.tooManyCredentials
     */
    public function testThrowsWithTooManyCredentials()
    {
        $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com',
            'username' => 'fred'
        ]);
    }

    /**
     * @expectedException \Myth\Auth\Exceptions\AuthException
     * @expectedExceptionMessage Auth.invalidField
     */
    public function testThrowsWithInvalidFields()
    {
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
        $this->assertEquals('Auth.invalidUser', $this->auth->error());
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
        $this->assertEquals('Auth.invalidPassword', $this->auth->error());
    }

    public function testValidateSuccess()
    {
        $user = new \Myth\Auth\Entities\User(['password_hash' => password_hash('secret', PASSWORD_DEFAULT)]);

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
        $user = new \Myth\Auth\Entities\User(['password_hash' => password_hash('secret', PASSWORD_DEFAULT)]);

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
        $origPassword = password_hash('secret', PASSWORD_DEFAULT, ['cost' => 9]);
        $user = new \Myth\Auth\Entities\User(['password_hash' => $origPassword]);

        $this->userModel->shouldReceive('where')->once()->with(\Mockery::subset(['email' => 'joe@example.com']))->andReturn($this->userModel);
        $this->userModel->shouldReceive('first')->once()->andReturn($user);
        $this->userModel->shouldReceive('save')->once();

        $result = $this->auth->validate([
            'password' => 'secret',
            'email' => 'joe@example.com'
        ], true);

        $this->assertTrue($result instanceof \Myth\Auth\Entities\User);
        $this->assertNotEquals($origPassword, $user->password_hash);
    }
}
