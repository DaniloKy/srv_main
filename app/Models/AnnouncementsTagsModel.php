<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementsTagsModel extends Model
{   

    protected $table = 'announcements_tags';
    protected $allowedFields = ['announcement_id', 'tag_id'];

    public function create($validated){
        $data = array(
            'announcement_id' => $validated['announcement_id'],
            'tag_id' => $validated['tag_id'],
        );
        return $this->insert($data);
    }
    
}

?>