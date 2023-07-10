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

        $this->login_model = model(Session::class);
        $this->users_model = model(UsersModel::class);
    }

    public function manage(){
        $results = $this->users_model->getById(session('userdata')['user']['id'], true);
        if(!$results){
            $this->login_model->logout();
        }
        return $this->baseHomeView('signup/user/manage', ['userInfo' => $results], ['title' => 'Profile Management']);
    }

    public function update(){
        $update = $this->request->getRawInput();
        $val = $this->validate_form($update, 'user_update');
        if($val){
            if(!$this->users_model->getWhere(['username' => $update['username']], true)){
                $status = $this->users_model->updateUser(session('userdata')['user']['id'] ,['username' => $update['username']]);
                if($status){
                    $this->session->setFlashdata('update_success', 'Profile updated with success.');    
                    return redirect()->to('logout');
                }
            }else
                $this->session->setFlashdata('update_error', 'Username already in use.');
        }
        return redirect('user/manage')->withInput();
    }

    public function change_password(){
        $results = $this->users_model->getById(session('userdata')['user']['id'], true);
        if(!$results){
            $this->login_model->logout();
        }
        return $this->baseHomeView('signup/user/change_password', ['userInfo' => $results], ['title' => 'Profile Management']);
    }

    public function update_password(){
        $update = $this->request->getRawInput();
        $val = $this->validate_form($update, 'user_update_password');
        if($val){
            $user = $this->users_model->getById(session('userdata')['user']['id'], true);
            if(password_verify($update['current_password'], $user['password'])){
                $status = $this->users_model->updateUser(session('userdata')['user']['id'] ,['password' => password_hash($update['new_password'], PASSWORD_BCRYPT)]);
                if($status){
                    $this->session->setFlashdata('update_success', 'Password updated with success.');    
                    return redirect()->to('logout');
                }
                $this->session->setFlashdata('update_error', 'Something went wrong.');    
                return redirect()->to('user/change_password');
            }
        }
        return redirect('user/change_password')->withInput();
    }

}
