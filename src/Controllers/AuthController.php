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

    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    public function __construct(...$params)
    {
        parent::__construct(...$params);

        // Most services in this controller require
        // the session to be started - so fire it up!
        $this->session = Services::session();
        $this->session->start();

        $this->config = new Auth();
        $this->auth = Services::authentication();
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
        // No need to show a login form if the user
        // is already logged in.
        if ($this->auth->check())
        {
            $redirectURL = session('redirect_url') ?? '/';
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL);
        }

        echo view($this->config->views['login']);
    }

    /**
     * Attempts to verify the user's credentials
     * through a POST request.
     */
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');

        // Try to log them in...
        if (! $this->auth->attempt(['email' => $email, 'password' => $password], $remember))
        {
            return redirect()->back()->withInput()->with('error', lang('Auth.'));
        }

        // Is the user being forced to reset their password?
        if ($this->auth->user()->force_pass_reset === true)
        {
            return redirect('change_pass');
        }

        $redirectURL = session('redirect_url') ?? '/';
        unset($_SESSION['redirect_url']);

        return redirect()->to($redirectURL)->with('message', lang('Auth.loginSuccess'));
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        if ($this->auth->check())
        {
            $this->auth->logout();
        }

        return redirect('/');
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

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = array_merge($userModel->getValidationRules(['only' => ['email', 'username']]), [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ]);

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        // Save the user
        $user = new User($this->request->getPost());

        $user->name = $user->username;

        if (! $userModel->save($user))
        {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        // Success!
        return redirect()->route('login')->with('message', lang('Auth.registerSuccess'));
    }

}
