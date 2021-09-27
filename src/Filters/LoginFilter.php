<?php

namespace Myth\Auth\Filters;

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
		// List of the restricted Myth\Auth name routes.
		$names = [
			'login',
			'logout',
			'register',
			'activate-account',
			'resend-activate-account',
			'forgot',
			'reset-password',
		];

		// Since url_is() function is available start from CI 4.0.5
		// please update your application soon.
		foreach ($names as $name)
		{
			if (url_is(route_to($name)))
			{
				// Make sure this isn't already a Myth\Auth routes.
				return;
			}
		}

		// Load the service.
		$authenticate = service('authentication');

		// If no user is logged in then send them to the login form.
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
		// Do something here
	}
}
