<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class AnnouncementsModel extends Model
{   

    protected $table = 'announcements';
    protected $allowedFields = ['title', 'title_compiled', 'description', 'image_path', 'tag_id', 'created_by', 'created_at'];

    public function create($validated){
        $data = [
            'title' => $validated['title'],
            'title_compiled' => url_title($validated['title'], '-', true),
            'description' => $validated['description'],
            'image_path' => $validated['image_path'],
            'tag_id' => $validated['tag_id'],
            'created_by' => $validated['created_by'],
            'created_at' => date("Y-m-d H:i:s"),
        ];
        return $this->insert($data);
    }

    public function list($where){
        $query = $this->select('announcements.*, tags.tag, tags.tag_compiled, users.username')
            ->join('users', 'users.id = announcements.created_by')
            ->join('tags', 'tags.id = announcements.tag_id')
            ->where($where)
            ->get();
        $results = $query->getResult();
        return $results;
    }

    public function listPaginate($where){
        return $this->select('announcements.*, tags.tag, tags.tag_compiled, users.username')
            ->join('users', 'users.id = announcements.created_by')
            ->join('tags', 'tags.id = announcements.tag_id')
            ->where($where);
    }
    
}

?>