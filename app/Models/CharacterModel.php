<?php

namespace App\Models;

use CodeIgniter\Model;

class CharacterModel extends Model
{   

    protected $table = 'player_ids';
	protected $allowedFields = ['game_id', 'belong_to'];

    public function __construct()
    {
        parent::__construct();        
    }
    
    public function countInTable(){
        return $this->countAll();
    }

    public function getAll(){

        return $this->get();
    }

}

?>