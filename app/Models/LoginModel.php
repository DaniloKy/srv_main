<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{   

    protected $table = 'users';
	protected $allowedFields = ['sId'];
    protected $session;

    public function __construct()
    {
        parent::__construct();

        $this->session = session();
    }
    
	public function isLoggedIn(){
		$userdata = $this->session->get('userdata');
		if($userdata && $userdata['logged_in'] == TRUE){
			$user = $this->find($userdata['user']['id']);
			if(!$this->checkSession($user)){
				$this->update($user['id'], ['sId' => '']);
				$this->session->destroy();
				return false;
			}
			$this->createSession($userdata['user']);
			return true;
		}
		return false;
	}

	public function checkSession($user){
		return $user['sId'] == session_id();
	}

	public function createSession($user_data){
		$this->session->set('userdata', array('logged_in' =>TRUE, 'user'=>$user_data));
	}

}

?>