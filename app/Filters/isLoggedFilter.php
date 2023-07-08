<?php

namespace App\Filters;

use App\Models\LoginModel;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class isLoggedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!model(LoginModel::class)->isLoggedIn() || !model(LoginModel::class)->isActive()){
            return redirect()->to('home');
        }
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}

?>