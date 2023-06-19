<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserAdmin extends BaseController
{
    protected $users_model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->users_model = model(UsersModel::class);
    }

    public function manage(){
        return $this->baseHomeView('signup/admin/user/manage');
    }

    public function list(){

    }



}
