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
        $data = $this->list();
        $tags = $this->listTags();
        return $this->baseHomeView('announcements/index', ['announcements' => $data, 'tags' => $tags], ['title' => 'Announcements', 'cssPath' => 'css/announcements.css']);
    }

    public function getByTag($tag){
        $query = $this->ann_model->select()
        ->join('users', 'users.id = announcements.created_by')
        ->join('tags', 'tags.id = announcements.tag_id')
        ->get();
        $results = $query->getResult();
        dd($results);
        $eTag = $this->tag_model->getWhere(['tag_compiled' => $tag], true);
        $results = $this->ann_model->getWhere(['tag_id' => $eTag['id']]);
        if($results){
            return $this->baseHomeView('announcements/indexWTag', ['announcements' => $results, 'tag' => $eTag['tag']], ['title' => $tag.' - Announcement', 'cssPath' => 'css/announcements.css']);
        }
        return redirect('announcements');
    }

    public function getByTagAndName($tag, $name){
        $eTag = $this->tag_model->getWhere(['tag_compiled' => $tag], true);
        if($eTag){
            $query = $this->ann_model->select()
            ->where(['title_compiled' => $name, 'tag_id' => $eTag['id']])
            ->get();
            $result = $query->getRow();
            $result->tag = $eTag;
            $user = $this->user_model->getById($result->id);
            if($user)
                $result->author = $user['username'];
            if($result){
                return $this->baseHomeView('announcements/details', ['announcement' => $result], ['title' => $name.' - Announcement', 'cssPath' => 'css/announcements.css']);
            }
        }
        return redirect('announcements');
    }

    public function list(){
        $results = $this->ann_model->listAll();
        return $results;
    }

    public function listTags(){
        $results = $this->tag_model->listAll();
        return $results;
    }



}
