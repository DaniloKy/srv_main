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
        return $this->baseHomeView('signup/admin/user/manage', ['users' => $users], ['title' => 'Users Dashboard', 'jsPath' => 'js/userDialogs.js']);
    }

    public function list(){
        $array = ['status !=' => 0];
        $query = $this->users_model->select()
        ->where($array)
        ->get();
        $result = $query->getResult();
        dd($result);
        return $result;
    }

    public function ban(){
        $post = $this->request->getPost();
        dd($post);
    }

    public function unban(){
        $post = $this->request->getPost();
    }

    public function makeSuper(){
        $post = $this->request->getPost();
    }

    public function removeSuper(){
        $post = $this->request->getPost();
    }

}
