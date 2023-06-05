<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Session extends BaseController
{
    protected $login_model;
    protected $users_model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->login_model = model(LoginModel::class);
        $this->users_model = model(UsersModel::class);
    }

    public function logining(){
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');

        $singin = $this->request->getPost();
        $val = $this->validate_form($singin, 'singin');
        if($val){
            if($user = $this->login_model->getByEmail($singin['email'])){
                if(password_verify($singin['password'], $user['password'])) {
                    $this->login_model->createSession($user);
                    return redirect()->to('signup/main');
                }else
                    $this->session->setFlashdata('login_error', 'Incorrect password.');
            }else
                $this->session->setFlashdata('login_error', 'Email does not exist.');
        }
        return redirect('login')->withInput();
    }

    public function registering(){
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');

        $signup = $this->request->getPost();
        $val = $this->validate_form($signup, 'signup');
        if($val){
            if(!$this->login_model->getByEmail($signup['email'])){
                $signup['password'] = password_hash($signup['password'], PASSWORD_BCRYPT);
                $createdUserId = $this->users_model->create($signup);
                $createdUser = $this->users_model->getById($createdUserId);
                $this->login_model->createSession($createdUser);
                return redirect()->to('signup/main');
            }else
                $this->session->setFlashdata('register_error', 'Email already in use.');
        }
        return redirect('register')->withInput();
    }


    public function login()
    {
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');

        return $this->baseHomeView('login');
    }

    public function register()
    {
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');

        return $this->baseHomeView('register');
    }

    public function logout(){
        $this->session->destroy();
	    return redirect()->to('/');
    }

}
