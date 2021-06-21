<?php namespace Myth\Auth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\App;

class LoginFilter implements FilterInterface
{
	/**
	 * Verifies that a user is logged in, or redirects to login.
	 *
	 * @param RequestInterface $request
	 * @param array|null $params
	 *
	 * @return mixed
	 */
	public function before(RequestInterface $request, $params = null)
	{
		if (! function_exists('logged_in'))
		{
			helper('auth');
		}

		$current = (string)current_url(true)
			->setHost('')
			->setScheme('')
			->stripQuery('token');

		$config = config(App::class);
		if($config->forceGlobalSecureRequests)
		{
			# Remove "https:/"
			$current = substr($current, 7);
		}

		// Make sure this isn't already a login route
		if (in_array((string)$current, [route_to('login'), route_to('forgot'), route_to('reset-password'), route_to('register'), route_to('activate-account')]))
		{
			return;
		}

		// if no user is logged in then send to the login form
		$authenticate = service('authentication');
		if (! $authenticate->check())
		{
			session()->set('redirect_url', current_url());
			return redirect('login');
		}
	}

	/**
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param array|null $arguments
	 *
	 * @return void
	 */
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
	}
}
