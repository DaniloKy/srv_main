<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{   

    protected $table = 'users';
    protected $allowedFields = ['email', 'password', 'created_at', 'active', 'super', 'sId', 'verification_code'];

    public function getById($id){
        $user = $this->find($id);
        return $user;
    }

    public function create($validated){
        $data = array(
            'email' => $validated['email'],
            'password' => $validated['password'],
            'created_at' => date("Y-m-d H:i:s"),
            'active' => 0,
            'super' => 0,
            'sId' => session_id(),
            'verification_code' => $this->makeVerfCode(),
        );
        $user = $this->insert($data);
        return $user;
    }

    public function makeVerfCode($length = 32){
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvvwxyz";
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $str;
    }

}

?>