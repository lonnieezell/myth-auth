<?php

use CodeIgniter\Test\ControllerTestTrait;
use Myth\Auth\Controllers\AuthController;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class LoginTest extends AuthTestCase
{
    use ControllerTestTrait;

    protected $refresh = true;

    protected function setUp(): void
    {
        \Config\Services::reset();

        parent::setUp();

        // Make sure our valiation rules include strong_password
        $vConfig             = new \Config\Validation();
        $vConfig->ruleSets[] = \Myth\Auth\Authentication\Passwords\ValidationRules::class;
        $vConfig->ruleSets   = array_reverse($vConfig->ruleSets);
        \CodeIgniter\Config\Factories::injectMock('Config', 'Validation', $vConfig);

        // Make sure our routes are mapped
        $routes = service('routes');
        $routes->add('login', 'AuthController::login', ['as' => 'login']);
        $routes->add('register', 'AuthController::register', ['as' => 'register']);
        \Config\Services::injectMock('routes', $routes);

        $_SESSION = [];
    }

    public function testLoginDisplaysForm()
    {
        $result = $this->withUri(site_url('login'))
            ->controller(AuthController::class)
            ->execute('login');

        $this->assertTrue($result->isOK());
        $result->see('Login', 'h2');
    }

    public function testAttemptLoginValidationErrors()
    {
        $result = $this->withUri(site_url('login'))
            ->controller(AuthController::class)
            ->execute('attemptLogin');

        $this->assertTrue($result->isRedirect());
        $this->asserttrue(isset($_SESSION['_ci_validation_errors']));
    }

    /**
     * @dataProvider rememberMeProvider
     */
    public function testAttemptLoginSuccess(bool $remembering)
    {
        // Create user
        $user = [
            'username' => 'Joe Cool',
            'email'    => 'jc@example.com',
            'password' => 'xaH96AhjglK',
            'active'   => 1,
        ];
        $this->createUser($user);

        // Set form input
        $data = [
            'login'    => $user['username'],
            'password' => $user['password'],
            'remember' => 'on',
        ];
        $globals = [
            'request' => $data,
            'post'    => $data,
        ];

        $request = service('request', null, false);
        $this->setPrivateProperty($request, 'globals', $globals);

        // Just make sure since it's a default
        $config                   = config('Auth');
        $config->allowRemembering = $remembering;
        \CodeIgniter\Config\Factories::injectMock('Config', 'Auth', $config);

        $result = $this->withUri(site_url('login'))
            ->withRequest($request)
            ->controller(AuthController::class)
            ->execute('attemptLogin');

        $this->assertTrue($result->isRedirect());
        $this->assertSame(lang('Auth.loginSuccess'), $_SESSION['message']);
        $this->assertSame($remembering, $result->response()->hasCookie('remember'));
    }

    public function rememberMeProvider()
    {
        return [
            [true],
            [false],
        ];
    }
}
