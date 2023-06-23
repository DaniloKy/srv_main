<?php

namespace App\Models;

use CodeIgniter\Model;

class TagsModel extends Model
{   

    protected $table = 'tags';
    protected $allowedFields = ['tag', 'tag_compiled'];

    public function create($validated){
        $data = [
            'tag' => $validated['tag'],
            'tag_compiled' => url_title($validated['tag'], '-', true),
        ];
        return $this->insert($data);
    }
    
}

?>