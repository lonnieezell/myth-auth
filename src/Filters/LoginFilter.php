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
		// set the restricted url segments.
		$segments = [
			'login',
			'logout',
			'register',
			'activate-account',
			'resend-activate-account',
			'forgot',
			'reset-password',
		];

		// for the latest CI4, there is a new url_is() function.
		if (function_exists('url_is'))
		{
			foreach ($segments as $segment)
			{
				if (url_is(route_to($segment)))
				{
					return;
				}
			}
		}
		else
		{
			// what if user doesn't have this function?
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
