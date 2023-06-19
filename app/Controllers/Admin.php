<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Admin extends BaseController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        /*$results = $this->users_model->getById(session('userdata')['user']['id'], true);
        if(!$results){
            $this->login_model->logout();
        }*/
        return $this->baseHomeView('signup/admin/manage');
    }

}
