<?php

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\ControllerTestTrait;
use Config\Services;
use Config\Validation;
use Myth\Auth\Authentication\Passwords\ValidationRules;
use Myth\Auth\Config\Auth;
use Myth\Auth\Controllers\AuthController;
use Myth\Auth\Models\UserModel;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class RegisterTest extends AuthTestCase
{
    use ControllerTestTrait;

    protected $refresh = true;

    protected function setUp(): void
    {
        Services::reset();

        parent::setUp();

        // Make sure our valiation rules include strong_password
        $vConfig             = new Validation();
        $vConfig->ruleSets[] = ValidationRules::class;
        $vConfig->ruleSets   = array_reverse($vConfig->ruleSets);
        Factories::injectMock('Config', 'Validation', $vConfig);

        // Make sure our routes are mapped
        $routes = service('routes');
        $routes->add('login', 'AuthController::login', ['as' => 'login']);
        $routes->add('register', 'AuthController::register', ['as' => 'register']);
        Services::injectMock('routes', $routes);

        $_SESSION = [];
    }

    public function testRegisterDisplaysForm()
    {
        $result = $this->withUri(site_url('register'))
            ->controller(AuthController::class)
            ->execute('register');

        $this->assertTrue($result->isOK());

        /**
         * @see https://github.com/codeigniter4/CodeIgniter4/pull/6756
         *
         * @phpstan-ignore-next-line
         */
        $result->see('Register', 'h2');
    }

    public function testAttemptRegisterDisabled()
    {
        $config                    = new Auth();
        $config->allowRegistration = false;
        Factories::injectMock('Config', 'Auth', $config);

        $result = $this->withUri(site_url('register'))
            ->controller(AuthController::class)
            ->execute('attemptRegister');

        $this->assertTrue($result->isRedirect());
        $this->assertSame(lang('Auth.registerDisabled'), $_SESSION['error']);
    }

    public function testAttemptRegisterValidationErrors()
    {
        $config                    = new Auth();
        $config->allowRegistration = true;
        Factories::injectMock('Config', 'Auth', $config);

        $result = $this->withUri(site_url('register'))
            ->controller(AuthController::class)
            ->execute('attemptRegister');

        /**
         * @see https://github.com/codeigniter4/CodeIgniter4/pull/6756
         *
         * @phpstan-ignore-next-line
         */
        $this->assertTrue($result->isRedirect(), print_r($result->getBody(), true));
        $this->assertNotNull($_SESSION['_ci_validation_errors']);
    }

    public function testAttemptRegisterCreatesUser()
    {
        // Set form input
        $data = [
            'username'     => 'Joe Cool',
            'email'        => 'jc@example.com',
            'password'     => 'xaH96AhjglK',
            'pass_confirm' => 'xaH96AhjglK',
        ];
        $globals = [
            'request' => $data,
            'post'    => $data,
        ];

        $request = service('request', null, false);
        $this->setPrivateProperty($request, 'globals', $globals);

        // don't require activation for this...
        $config                    = config('Auth');
        $config->requireActivation = null;
        Factories::injectMock('Config', 'Auth', $config);

        $result = $this->withUri(site_url('register'))
            ->withRequest($request)
            ->controller(AuthController::class)
            ->execute('attemptRegister');

        $this->assertTrue($result->isRedirect());
        $this->assertSame(lang('Auth.registerSuccess'), $_SESSION['message']);

        $this->seeInDatabase('users', [
            'username' => $data['username'],
            'email'    => $data['email'],
        ]);
    }

    public function testAttemptRegisterCreatesUserWithDefaultGroup()
    {
        // Set form input
        $data = [
            'username'     => 'Joe Cool',
            'email'        => 'jc@example.com',
            'password'     => 'xaH96AhjglK',
            'pass_confirm' => 'xaH96AhjglK',
        ];
        $globals = [
            'request' => $data,
            'post'    => $data,
        ];

        $request = service('request', null, false);
        $this->setPrivateProperty($request, 'globals', $globals);

        $group = $this->createGroup();

        // don't require activation for this...
        $config                    = config('Auth');
        $config->requireActivation = null;
        $config->defaultUserGroup  = $group->name;
        Factories::injectMock('Config', 'Auth', $config);

        $result = $this->withUri(site_url('register'))
            ->withRequest($request)
            ->controller(AuthController::class)
            ->execute('attemptRegister');

        $this->assertTrue($result->isRedirect());
        $this->assertSame(lang('Auth.registerSuccess'), $_SESSION['message']);

        $this->seeInDatabase('users', [
            'username' => $data['username'],
            'email'    => $data['email'],
        ]);

        $users = new UserModel();
        $user  = $users->where('username', $data['username'])->first();
        $this->seeInDatabase('auth_groups_users', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
    }
}
