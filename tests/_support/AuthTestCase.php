<?php namespace ModuleTests\Support;

use CodeIgniter\Session\Handlers\ArrayHandler;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Tests\Support\Session\MockSession;

class AuthTestCase extends \CodeIgniter\Test\CIDatabaseTestCase
{
	/**
	 * Should the database be refreshed before each test?
	 *
	 * @var boolean
	 */
	protected $refresh = true;

	/**
	 * The name of a seed file used for all tests within this test case.
	 *
	 * @var string
	 */
	protected $seed = 'ModuleTests\Support\Database\Seeds\AuthSeeder';

	/**
	 * The path to where we can find the test Seeds directory.
	 *
	 * @var string
	 */
	protected $basePath = SUPPORTPATH . 'Database/';

	/**
	 * The namespace to help us find the migration classes.
	 *
	 * @var string
	 */
	protected $namespace = 'Myth\Auth';

	/**
	 * @var \Myth\Auth\Models\UserModel
	 */
	protected $users;

	protected $faker;

	/**
	 * @var SessionHandler
	 */
	protected $session;

	public function setUp(): void
	{
		parent::setUp();

		$this->users = new UserModel();
		$this->mockSession();

		$this->faker = \Faker\Factory::create();
	}

	/**
	 * Pre-loads the mock session driver into $this->session.
	 *
	 */
	protected function mockSession()
	{
		require_once ROOTPATH . 'tests/_support/Session/MockSession.php';
		$config = config('App');
		$this->session = new MockSession(new ArrayHandler($config, '0.0.0.0'), $config);
		\Config\Services::injectMock('session', $this->session);
	}

	/**
	 * Creates a user on-the-fly.
	 *
	 * @param string $reason
	 *
	 * @return $this
	 */
	protected function createUser(array $info = [])
	{
		$defaults = [
			'email' => 'fred@example.com',
			'password' => 'secret'
		];
		$info = array_merge($info, $defaults);
		$user = new User($info);

		$userId = $this->users->insert($user);
		$user = $this->users->find($userId);

		return $user;
	}

    /**
     * Creates a group on the fly
     *
     * @param array $info
     *
     * @return mixed
     */
	protected function createGroup(array $info = [])
    {
        $defaults = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence
        ];
        $info = array_merge($info, $defaults);

        $this->db->table('auth_groups')->insert($info);

        return $this->db->table('auth_groups')->where('id', $this->db->insertID())->get()->getResultObject()[0];
    }

    /**
     * Creates a new permission on the fly.
     *
     * @param array $info
     *
     * @return mixed
     */
    protected function createPermission(array $info = [])
    {
        $defaults = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence
        ];
        $info = array_merge($info, $defaults);

        $this->db->table('auth_permissions')->insert($info);

        return $this->db->table('auth_permissions')->where('id', $this->db->insertID())->get()->getResultObject()[0];
    }
}
