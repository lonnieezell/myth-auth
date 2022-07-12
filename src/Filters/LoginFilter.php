<?php

namespace Myth\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter extends BaseFilter implements FilterInterface
{
    /**
     * Verifies that a user is logged in, or redirects to login.
     *
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Make sure this isn't already a Myth\Auth routes.
        foreach ($this->reservedRoutes as $reservedRoute) {
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
     * @param array|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
