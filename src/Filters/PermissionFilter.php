<?php

namespace Myth\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Exceptions\PermissionException;

class PermissionFilter implements FilterInterface
{
    /**
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! function_exists('logged_in')) {
            helper('auth');
        }

        if (empty($arguments)) {
            return;
        }

        $authenticate = service('authentication');

        // if no user is logged in then send to the login form
        if (! $authenticate->check()) {
            session()->set('redirect_url', current_url());

            return redirect('login');
        }

        $authorize = service('authorization');
        $result    = true;

        // Check each requested permission
        foreach ($arguments as $permission) {
            $result = $result && $authorize->hasPermission($permission, $authenticate->id());
        }

        if (! $result) {
            if ($authenticate->silent()) {
                $redirectURL = session('redirect_url') ?? '/';
                unset($_SESSION['redirect_url']);

                return redirect()->to($redirectURL)->with('error', lang('Auth.notEnoughPrivilege'));
            }

            throw new PermissionException(lang('Auth.notEnoughPrivilege'));
        }
    }

    //--------------------------------------------------------------------

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param array|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    //--------------------------------------------------------------------
}
