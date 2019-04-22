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
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $params = null)
    {
        $authenticate = Services::authentication();

        if (! $authenticate->check() || empty($params))
        {
        	die('fail');
            return;
        }

        $user = $authenticate->user();
        $authorize = Services::authorization();

        if (! $authorize->inGroup($params, $user->id))
        {
            throw new \RuntimeException('You do not have permission to view that page.');
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
