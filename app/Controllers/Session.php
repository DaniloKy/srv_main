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
        
        if(filter_var($singin['login_input'], FILTER_VALIDATE_EMAIL))
            $user = $this->users_model->getWhere(['email' => $singin['login_input']], true);
        else
            $user = $this->users_model->getWhere(['username' => $singin['login_input']], true);
        if($user){
            if(password_verify($singin['password'], $user['password'])) {
                if (isset($singin['rememberMe']) && $singin['rememberMe'] === 'on') {
                    $rememberToken = bin2hex(random_bytes(32));
                    $user['remember_token'] = $rememberToken;
                    $this->users_model->save($user);
                    setcookie('remember_token', $rememberToken, time()+(env('REMEMBER_EXPIRES')*86400));
                }
                if(session_regenerate_id()){
                    $user['sId'] = session_id();
                    $this->users_model->save($user);
                }
                $this->login_model->createSession([
                    'id' => $user['id'], 
                    "username" => $user['username'], 
                    "email" => $user['email'], 
                    "verification_code" => $user['verification_code'],
                    "status" => $user['status'],
                    "super" => $user['super'],
                    "created_at" => $user['created_at'],
                ]);
                return redirect()->to('/');
            }else
                $this->session->setFlashdata('login_error', 'Incorrect username or password.');
        }else
            $this->session->setFlashdata('login_error', 'Incorrect username or password.');
        return redirect('login')->withInput();
    }

    public function registering(){
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');
            
        $signup = $this->request->getPost();
        $val = $this->validate_form($signup, 'signup');
        if($val){
            if(!$this->users_model->getWhere(['email' => $signup['email']], true)){
                if(!$this->users_model->getWhere(['username' => $signup['username']], true)){
                    $signup['password'] = password_hash($signup['password'], PASSWORD_BCRYPT);
                    $this->users_model->create($signup);
                    return redirect()->to('/');
                }else
                    $this->session->setFlashdata('register_error', 'Username already in use.');
            }else
                $this->session->setFlashdata('register_error', 'Email already in use.');
        }
        return redirect('register')->withInput();
    }

    public function login()
    {
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');

        return $this->baseHomeView('login', [], ['title' => 'Sing in']);
    }

    public function register()
    {
        if($this->login_model->isLoggedIn())
            return redirect()->to('/');

        return $this->baseHomeView('register', [], ['title' => 'Sing up']);
    }

    public function logout(){
        setcookie('remember_token', null, -1);
        if (session_status() === PHP_SESSION_ACTIVE){
            $this->session->destroy();
        }
	    return redirect()->to('/');
    }

}
