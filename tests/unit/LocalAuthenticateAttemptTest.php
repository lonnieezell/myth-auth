<?php

use Mockery as m;
use Myth\Auth\Config\Auth;
use Myth\Auth\Entities\User;
use Myth\Auth\Config\Services;
use Myth\Auth\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use Myth\Auth\Authentication\LocalAuthenticator;

class LocalAuthenticateAttemptTest extends CIUnitTestCase
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

    public function setUp(): void
    {
        parent::setUp();

        $this->userModel = m::mock(UserModel::class);
        $this->auth = m::mock('Myth\Auth\Authentication\LocalAuthenticator[recordLoginAttempt,login,rememberUser,validate]', [new Auth()]);
        $this->auth->setUserModel($this->userModel);

        $this->request = m::mock('CodeIgniter\HTTP\IncomingRequest');
        Services::injectMock('CodeIgniter\HTTP\IncomingRequest', $this->request);
    }

    public function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testLoginInvalidUser()
    {
        $credentials = [
            'email' => 'joe@example.com',
            'password' => 'secret'
        ];

        $this->auth->shouldReceive('validate')->once()->with(\Mockery::subset($credentials), true)->andReturn(false);
        $this->auth->shouldReceive('recordLoginAttempt')->once()->with($credentials['email'], '0.0.0.0', null, false);

        $result = $this->auth->attempt($credentials, false);

        $this->assertFalse($result);
        $this->assertNull($this->auth->user());
    }

    public function testLoginSuccessRemember()
    {
        $credentials = [
            'email' => 'joe@example.com',
            'password' => 'secret'
        ];

        $user = new User();
        $user->id = 5;
        $user->active = true;

        $this->auth->shouldReceive('validate')->once()->with(\Mockery::subset($credentials), true)->andReturn($user);
        $this->auth->shouldReceive('login')->once()->andReturn(true);

        $result = $this->auth->attempt($credentials, true);

        $this->assertTrue($result);
    }
}
