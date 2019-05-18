<?php

use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class CIDatabaseTestCase extends \CodeIgniter\Test\CIDatabaseTestCase
{
    /**
     * Should the db be refreshed before
     * each test?
     *
     * @var boolean
     */
    protected $refresh = true;

    /**
     * The name of the fixture used for all tests
     * within this test case.
     *
     * @var string
     */
    protected $seed = '';

    /**
     * The path to where we can find the migrations
     * and seeds directories. Allows overriding
     * the default application directories.
     *
     * @var string
     */
    protected $basePath = __DIR__ . '/../../src/Database/';

    /**
     * The namespace to help us find the migration classes.
     *
     * @var string
     */
    protected $namespace = 'Myth\Auth';

    /**
     * @var SessionHandler
     */
    protected $session;

    /**
     * @var \Myth\Auth\Models\UserModel
     */
    protected $users;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = new UserModel();
        $this->mockSession();
    }

    protected function createUser(array $info = [])
    {
        $defaults = [
            'email' => 'fred@example.com',
            'password' => 'secret'
        ];

        $info = array_merge($info, $defaults);

        $user = new User($info);
        $user->setPassword($info['password']);
        $this->users->save($user);
        $user = $this->users->find($user->id)[0];

        return $user;
    }

    protected function mockSession()
    {
        require_once ROOTPATH.'tests/_support/Session/MockSession.php';
        $config = config('App');
        $this->session = new \Tests\Support\Session\MockSession(new \CodeIgniter\Session\Handlers\ArrayHandler($config, '0.0.0.0'), $config);
        \Config\Services::injectMock('session', $this->session);
    }
}
