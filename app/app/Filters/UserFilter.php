<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\User;

class UserFilter implements FilterInterface
{

    private $response;

    public function __construct()
    {
        $this->response = \CodeIgniter\Config\Services::response();
    }

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
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $request = \Config\Services::request();

        $user_key = $request->getHeaderLine("X-User-key");

        $failBody = [
            "statuc" => 401,
            "error"  => 401,
            "message" => [
                "error" => "使用者未驗證"
            ]
        ];
        if ($user_key == "") return $this->response->setStatusCode(401, 'Unauthorized')->setJSON($failBody);
        
        User::setUserKey($user_key);
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
