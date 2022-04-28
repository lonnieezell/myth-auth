<?php

namespace Myth\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;

class LoginFilter implements FilterInterface
{
    /**
     * Verifies that a user is logged in, or redirects to login.
     *
     * @param array|null $params
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $params = null)
    {
        if (! function_exists('logged_in')) {
            helper('auth');
        }

        $current = (string) current_url(true)
            ->setHost('')
            ->setScheme('')
            ->stripQuery('token');

        $config = config(App::class);
        if ($config->forceGlobalSecureRequests) {
            // Remove "https:/"
            $current = substr($current, 7);
        }

        // Make sure this isn't already a login route
        if (in_array($current, [route_to('login'), route_to('forgot'), route_to('reset-password'), route_to('register'), route_to('activate-account')], true)) {
            return;
        }

        // if no user is logged in then send to the login form
        $authenticate = service('authentication');
        if (! $authenticate->check()) {
            session()->set('redirect_url', current_url());

            return redirect('login');
        }
    }

    /**
     * @param array|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
