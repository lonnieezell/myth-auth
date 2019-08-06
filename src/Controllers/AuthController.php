<?php namespace Myth\Auth\Controllers;

use CodeIgniter\Controller;
use Config\Email;
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

	public function __construct()
	{
		// Most services in this controller require
		// the session to be started - so fire it up!
		$this->session = Services::session();

		$this->config = config('Auth');
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

		echo view($this->config->views['login'], ['config' => $this->config]);
	}

	/**
	 * Attempts to verify the user's credentials
	 * through a POST request.
	 */
	public function attemptLogin()
	{
		$rules = [
			'login'	=> 'required',
			'password' => 'required',
		];
		if ($this->config->validFields == ['email'])
		{
			$rules['login'] .= '|valid_email';
		}

		if (! $this->validate($rules))
		{
			return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
		}

		$login = $this->request->getPost('login');
		$password = $this->request->getPost('password');
		$remember = (bool)$this->request->getPost('remember');
		
		// Determine credential type
		$type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		
		// Try to log them in...
		if (! $this->auth->attempt([$type => $login, 'password' => $password], $remember))
		{
			return redirect()->back()->withInput()->with('error', lang('Auth.badAttempt'));
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
		// Check if registration is allowed
		if (! $this->config->allowRegistration)
		{
			return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
		}
		
		echo view($this->config->views['register']);
	}

	/**
	 * Attempt to register a new user.
	 */
	public function attemptRegister()
	{
		// Check if registration is allowed
		if (! $this->config->allowRegistration)
		{
			return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
		}
		
		$users = new UserModel();

		// Validate here first, since some things,
		// like the password, can only be validated properly here.
		$rules = array_merge($users->getValidationRules(['only' => ['email', 'username']]), [
			'password'	 => 'required|strong_password',
			'pass_confirm' => 'required|matches[password]',
		]);

		if (! $this->validate($rules))
		{
			return redirect()->back()->withInput()->with('errors', $users->errors());
		}

		// Save the user
		$user = new User($this->request->getPost());

		if (! $users->save($user))
		{
			return redirect()->back()->withInput()->with('errors', $users->errors());
		}

		// Success!
		return redirect()->route('login')->with('message', lang('Auth.registerSuccess'));
	}

	//--------------------------------------------------------------------
	// Forgot Password
	//--------------------------------------------------------------------

	/**
	 * Displays the forgot password form.
	 */
	public function forgotPassword()
	{
		echo view($this->config->views['forgot']);
	}

	/**
	 * Attempts to find a user account with that password
	 * and send password reset instructions to them.
	 */
	public function attemptForgot()
	{
		$users = new UserModel();

		$user = $users->where('email', $this->request->getPost('email'))->first();

		if (is_null($user))
		{
			return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
		}

		// Save the reset hash /
		$user->generateResetHash();
		$users->save($user);

		$email = Services::email();
		$config = new Email();

		$sent = $email->setFrom($config->fromEmail, $config->fromEmail)
			  ->setTo($user->email)
			  ->setSubject(lang('Auth.forgotSubject'))
			  ->setMessage(view($this->config->views['emailForgot'], ['hash' => $user->reset_hash]))
			  ->setMailType('html')
			  ->send();

		if (! $sent)
		{
			log_message('error', "Failed to send forgotten password email to: {$email}");
			return redirect()->back()->withInput()->with('error', lang('Auth.unknownError'));
		}

		return redirect()->route('reset-password')->with('message', lang('Auth.forgotEmailSent'));
	}

	/**
	 * Displays the Reset Password form.
	 */
	public function resetPassword()
	{
		$token = $this->request->getGet('token');

		echo view($this->config->views['reset'], [
			'token' => $token,
		]);
	}

	/**
	 * Verifies the code with the email and saves the new password,
	 * if they all pass validation.
	 *
	 * @return mixed
	 */
	public function attemptReset()
	{
		$users = new UserModel();

		// First things first - log the reset attempt.
		$users->logResetAttempt(
			$this->request->getPost('email'),
			$this->request->getPost('token'),
			$this->request->getIPAddress(),
			(string)$this->request->getUserAgent()
		);

		$rules = [
			'token'		=> 'required',
			'email'		=> 'required|valid_email',
			'password'	 => 'required|strong_password',
			'pass_confirm' => 'required|matches[password]',
		];

		if (! $this->validate($rules))
		{
			return redirect()->back()->withInput()->with('errors', $users->errors());
		}

		$user = $users->where('email', $this->request->getPost('email'))->first();

		if (is_null($user))
		{
			return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
		}

		// Success! Save the new password, and cleanup the reset hash.
		$user->password = $this->request->getPost('password');
		$user->reset_hash = null;
		$user->reset_start_time = null;
		$users->save($user);

		return redirect()->route('login')->with('message', lang('Auth.resetSuccess'));
	}
}
