<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class User extends BaseController
{
    protected $login_model;
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
        $results = $this->users_model->getWhere(['id' => session('userdata')['user']['id']]);
        $data = ['userInfo' => $results];
        return $this->baseHomeView('signup/user/manage', $data);
    }

    public function update($id = null){
        dd($this->request->getRawInput(), $id);
    }

}
