<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{   

    protected $table = 'users';
    protected $allowedFields = ['username', 'email', 'password', 'remember_token', 'sId', 'verification_code', 'active', 'super', 'created_at'];

    public function getById($id){
        return $this->find($id);   
    }
    public function getWhere($assoc, $first = false){
        if($first)
            $val = $this->where($assoc)->first();
        else
            $val = $this->where($assoc)->get()->getResult();
		return $val?:false;
	}

    public function create($validated){
        $data = array(
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'sId' => session_id(),
            'verification_code' => $this->makeVerfCode(),
            'active' => 0,
            'super' => 0,
            'created_at' => date("Y-m-d H:i:s"),
        );
        return $this->insert($data);
    }

    public function updateUser($id, $data){
        return $this->update($id, $data);
    }

    public function makeVerfCode($length = 8){
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvvwxyz";
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $str;
    }

}

?>