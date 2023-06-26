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
        $users = $this->list();
        return $this->baseHomeView('signup/admin/user/manage', ['users' => $users], ['title' => 'Users Dashboard']);
    }

    public function list(){
        $results = $this->users_model->listAll();
        //$results = $this->users_model->getWhere(['status' => 1]);
        //dd($results);
        return $results;
    }

    public function ban(){

    }

    public function unban(){

    }

    public function makeSuper(){

    }

    public function removeSuper(){

    }

}
