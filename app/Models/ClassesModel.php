<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassesModel extends Model
{   

    protected $table = 'classes';
    protected $allowedFields = ['name', 'name_compiled', 'description, image_path'];

    public function create($validated){
        $data = array(
            'name' => $validated['name'],
            'name_compiled' => url_title($validated['name'], '-', true),
            'description' => $validated['description'],
            'image_path' => $validated['image_path'],
        );
        return $this->insert($data);
    }

    
    
}

?>