<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassesModel extends Model
{   

    protected $table = 'classes';
    protected $allowedFields = ['name', 'name_compiled', 'nickname', 'description', 'image_path'];

    public function create($validated){
        $data = [
            'name' => $validated['name'],
            'name_compiled' => url_title($validated['name'], '-', true),
            'nickname' => $validated['nickname'],
            'description' => $validated['description'],
            'image_path' => $validated['image_path'],
        ];
        return $this->insert($data);
    }

    public function listClasses(){
        return $this->select();
    }

}

?>