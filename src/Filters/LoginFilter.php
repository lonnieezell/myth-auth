<?php

namespace Myth\Auth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter extends BaseFilter implements FilterInterface
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
        // Make sure this isn't already a Myth\Auth routes.
        foreach ($this->reservedRoutes as $key => $reservedRoute) {
            if (url_is(route_to($reservedRoute))) {
                return;
            }
        }

        // If no user is logged in then send them to the login form.
        if (! $this->authenticate->check()) {
            session()->set('redirect_url', current_url());
            return redirect($this->reservedRoutes['login']);
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
