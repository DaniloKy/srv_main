<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{   

    protected $table = 'tags';
    protected $allowedFields = ['tag', 'tag_compiled'];

    public function create($validated){
        $data = array(
            'tag' => $validated['tag'],
            'tag_compiled' => url_title($validated['tag'], '-', true),
        );
        return $this->insert($data);
    }
    
}

?>