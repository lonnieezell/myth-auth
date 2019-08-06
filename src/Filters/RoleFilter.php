<?php namespace Myth\Auth\Filters;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param \CodeIgniter\HTTP\RequestInterface $request
     * @param array|null                         $params
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $params = null)
    {
		if (empty($params))
		{
			return;
		}
		
        $authenticate = Services::authentication();
		
		// if no user is logged in then send to the login form
        if (! $authenticate->check())
        {
			session()->set('redirect_url', current_url());
            return redirect('login');
        }

        $authorize = Services::authorization();
		$result = true;
		
		// Check each requested permission
		foreach ($params as $group)
		{
            /**
             *  Fixed a bug right here.
             *  It happens when there's more than one role checked.
             *  
             *  Maybe better with an example:
             *
             *  user: dog
             *  role: doberman
             *
             *  Routes set:
             *  $routes->get('changepass', 'Admin::changePassword', ['filter' => 'role:doberman,german_sheppard', 'as' => 'change-password']);
             *
             *  It'll fail because this function should end right after role doberman is checked, but it doesn't
             *  and in the end $result is FALSE.
             *
             *  Therefore, once $result is TRUE, this check should end.
             */

            if(! $result)
            {
                $result = $result && $authorize->inGroup($group, $authenticate->id());
            }
		}
		
        if (! $result)
        {
        	if ($authenticate->silent())
        	{
				$redirectURL = session('redirect_url') ?? '/';
				unset($_SESSION['redirect_url']);
				return redirect()->to($redirectURL)->with('error', lang('Auth.notEnoughPrivilege'));
        	}
        	else {
                /**
                 *
                 *  Added redirect so when it fails user is headed back to previous page
                 *  with the error message.
                 *
                 */
                return redirect()->back()->with('error', lang('Auth.notEnoughPrivilege'));
        		// throw new \RuntimeException(lang('Auth.notEnoughPrivilege'));
        	}
        }
    }

    //--------------------------------------------------------------------

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param \CodeIgniter\HTTP\RequestInterface  $request
     * @param \CodeIgniter\HTTP\ResponseInterface $response
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {

    }

    //--------------------------------------------------------------------
}
