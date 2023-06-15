<?php

namespace App\Filters;

use App\Models\LoginModel;
use App\Models\UsersModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use function PHPUnit\Framework\isEmpty;

class checkRemberMeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (model(LoginModel::class)->isLoggedIn()){
            return null;
        }
        //dd("CheckFILTER, not loged");
        if(isset($_COOKIE) && isset($_COOKIE['remember_token'])){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $users_model = model(UsersModel::class);
            $user = $users_model->getWhere(['remember_token' => $_COOKIE['remember_token']], true);
            if($user){
                if(session_regenerate_id()){
                    $user['sId'] = session_id();
                    $users_model->save($user);
                }
                model(LoginModel::class)->createSession([
                    'id' => $user['id'],
                    "username" => $user['username'], 
                    "email" => $user['email'], 
                    "verification_code" => $user['verification_code'],
                    "status" => $user['status'],
                    "super" => $user['super'],
                    "created_at" => $user['created_at'],
                ]);
            }
        }
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}

?>