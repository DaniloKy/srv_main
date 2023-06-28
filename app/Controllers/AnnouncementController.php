<?php

namespace App\Controllers;

use App\Models\AnnouncementsModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AnnouncementController extends BaseController
{
    protected $ann_model;
    protected $tag_model;
    protected $user_model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->ann_model = model(AnnouncementsModel::class);
        $this->tag_model = model(TagsModel::class);
        $this->user_model = model(UsersModel::class);
        parent::initController($request, $response, $logger);
    }

    public function index(){
        $data = $this->list([]);
        $tags = $this->listTags();
        return $this->baseHomeView('announcements/index', ['announcements' => $data, 'tags' => $tags], ['title' => 'Announcements', 'cssPath' => 'css/announcements.css']);
    }

    public function getByTag($tag){
        $results = $this->list(['tag_compiled' => $tag]);
        if($results){
            return $this->baseHomeView('announcements/indexWTag', ['announcements' => $results], ['title' => $tag.' - Announcement', 'cssPath' => 'css/announcements.css']);
        }
        return redirect('announcements');
    }

    public function getByTagAndName($tag, $name){
        $result = $this->list(['tags.tag_compiled' => $tag, 'announcements.title_compiled' => $name]);
        if($result[0]){            
            return $this->baseHomeView('announcements/details', ['announcement' => $result[0]], ['title' => $name.' - Announcement', 'cssPath' => 'css/announcements.css']);
        }
        return redirect('announcements');
    }

    public function list($where){
        $query = $this->ann_model->select('announcements.*, tags.*, users.username')
            ->join('users', 'users.id = announcements.created_by')
            ->join('tags', 'tags.id = announcements.tag_id')
            ->where($where)
            ->get();
        $results = $query->getResult();
        return $results;
    }

    public function listTags(){
        $results = $this->tag_model->listAll();
        return $results;
    }



}
