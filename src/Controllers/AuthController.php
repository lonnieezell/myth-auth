<?php namespace Myth\Auth\Controllers;

use CodeIgniter\Controller;
use Myth\Auth\Config\Auth;
use Myth\Auth\Config\Services;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class AuthController extends Controller
{
    protected $auth;
    /**
     * @var Auth
     */
    protected $config;

    public function __construct(...$params)
    {
        parent::__construct(...$params);

        $this->config = new Auth();
//        $this->auth = Services::authentication();
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    /**
     * Displays the login form, or redirects
     * the user to their destination/home if
     * they are already logged in.
     */
    public function login()
    {
        echo view($this->config->views['login']);
    }

    /**
     * Attempts to verify the user's credentials
     * through a POST request.
     */
    public function attemptLogin()
    {

    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     */
    public function register()
    {
        echo view($this->config->views['register']);
    }

	/**
	 * Attempt to register a new user.
	 */
	public function attemptRegister()
	{
		$userModel = new UserModel();

		$user = new User($this->request->getPost());

		if (! $userModel->save($user))
		{
			return redirect()->back()->with('errors', $userModel->errors());
		}


    }

}
