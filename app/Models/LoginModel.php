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
			if(!$user){
				$this->session->destroy();
				return false;
			}
			if($user['sId'] != session_id()){
				//dd($user['sId'], session_id());
				$this->update($user['id'], ['sId' => '']);
				$this->session->destroy();
				return false;
				//return true;
			}
			return true;
		}
		return false;
	}

	public function isAdmin(){
		$userdata = $this->session->get('userdata');
		if($userdata){
			$user = $this->find($userdata['user']['id']);
			if($user && $user['super'] == "1"){
				return true;
			}
		}
		return false;
	}

	public function createSession($user_data){
		$this->session->set('userdata', ['logged_in' =>TRUE, 'user'=>$user_data]);
	}

}

?>