<?php namespace ModuleTests\Support;

use CodeIgniter\Session\Handlers\ArrayHandler;
use CodeIgniter\Test\Mock\MockSession;

class SessionTestCase extends \CodeIgniter\Test\CIDatabaseTestCase
{
    /**
     * @var SessionHandler
     */
    protected $session;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockSession();
    }
    
    /**
     * Pre-loads the mock session driver into $this->session.
     *
     * @var string
     */
    protected function mockSession()
    {
        require_once SYSTEMPATH . 'Test/Mock/MockSession.php';
        $config = config('App');
        $this->session = new MockSession(new ArrayHandler($config, '0.0.0.0'), $config);
        \Config\Services::injectMock('session', $this->session);
    }
}
