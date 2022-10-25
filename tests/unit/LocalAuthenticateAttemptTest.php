<?php

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Test\CIUnitTestCase;
use Mockery as m;
use Myth\Auth\Authentication\LocalAuthenticator;
use Myth\Auth\Config\Auth;
use Myth\Auth\Config\Services;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

/**
 * @internal
 */
final class LocalAuthenticateAttemptTest extends CIUnitTestCase
{
    protected UserModel $userModel;
    protected LocalAuthenticator $auth;
    protected IncomingRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userModel = m::mock(UserModel::class);
        $this->auth      = m::mock('Myth\Auth\Authentication\LocalAuthenticator[recordLoginAttempt,login,rememberUser,validate]', [new Auth()]);
        $this->auth->setUserModel($this->userModel);

        $this->request = m::mock('CodeIgniter\HTTP\IncomingRequest');
        Services::injectMock('CodeIgniter\HTTP\IncomingRequest', $this->request);
    }

    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testLoginInvalidUser()
    {
        $credentials = [
            'email'    => 'joe@example.com',
            'password' => 'secret',
        ];

        $this->auth->shouldReceive('validate')->once()->with(m::subset($credentials), true)->andReturn(false);
        $this->auth->shouldReceive('recordLoginAttempt')->once()->with($credentials['email'], '0.0.0.0', null, false);

        $result = $this->auth->attempt($credentials, false);

        $this->assertFalse($result);
        $this->assertNull($this->auth->user());
    }

    public function testLoginSuccessRemember()
    {
        $credentials = [
            'email'    => 'joe@example.com',
            'password' => 'secret',
        ];

        $user         = new User();
        $user->id     = 5;
        $user->active = true;

        $this->auth->shouldReceive('validate')->once()->with(m::subset($credentials), true)->andReturn($user);
        $this->auth->shouldReceive('login')->once()->andReturn(true);

        $result = $this->auth->attempt($credentials, true);

        $this->assertTrue($result);
    }
}
