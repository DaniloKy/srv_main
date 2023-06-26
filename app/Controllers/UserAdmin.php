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
        //dd($result);
        return $result;
    }

    public function ban(){
        $post = $this->request->getPost();
        $status = $this->users_model->update($post['id'], ['status' => -1]);
        if($status)
            $this->session->setFlashdata('success', 'User banned.');
        else
            $this->session->setFlashdata('error', 'Something went wrong.');
        return redirect()->to('user/admin/users/manage');
    }

    public function removeBan(){
        $post = $this->request->getPost();
        $status = $this->users_model->update($post['id'], ['status' => 1]);
        if($status)
            $this->session->setFlashdata('success', 'User unbanned.');
        else
            $this->session->setFlashdata('error', 'Something went wrong.');
        return redirect()->to('user/admin/users/manage');
    }

    public function makeSuper(){
        $post = $this->request->getPost();
        $status = $this->users_model->update($post['id'], ['super' => 1]);
        if($status)
            $this->session->setFlashdata('success', 'Super admin added.');
        else
            $this->session->setFlashdata('error', 'Something went wrong.');
        return redirect()->to('user/admin/users/manage');
    }

    public function removeSuper(){
        $post = $this->request->getPost();
        $status = $this->users_model->update($post['id'], ['super' => 0]);
        if($status)
            $this->session->setFlashdata('success', 'Super admin removed.');
        else
            $this->session->setFlashdata('error', 'Something went wrong.');
        return redirect()->to('user/admin/users/manage');
    }

}
