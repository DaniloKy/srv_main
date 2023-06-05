<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{   

    protected $table = 'users';
    protected $session;

    public function __construct()
    {
        parent::__construct();

        $this->session = session();
    }

    public function getByEmail($email){
        $user = $this->where(['email' => $email])->first();
		return $user?:false;
	}
    
	public function isLoggedIn(){
		$userdata = $this->session->get('userdata');
		if($userdata && $userdata['logged_in']== TRUE){
			$this->createSession($userdata['user']);
			return true;
		}
		return false;
	}

	public function createSession($user_data){
		$this->session->set('userdata', array('logged_in' =>TRUE, 'user'=>$user_data));
	}

}

?>