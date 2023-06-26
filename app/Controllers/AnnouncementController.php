<?php

namespace App\Controllers;

use App\Models\AnnouncementsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AnnouncementController extends BaseController
{
    protected $ann_model;
    protected $tag_model;
    protected $ann_tag_model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->ann_model = model(AnnouncementsModel::class);
        parent::initController($request, $response, $logger);
    }

    public function index(){
        $data = $this->list();
        return $this->baseHomeView('announcements/index', ['announcements' => $data], ['title' => 'Announcements', 'cssPath' => 'css/announcements.css']);
    }

    public function getByTag($tag){
        $query = $this->ann_model->select('announcements.*')
        ->join('announcements_tags', 'announcements.id = announcements_tags.announcement_id')
        ->join('tags', 'announcements_tags.tag_id = tags.id')
        ->where('tags.tag_compiled', $tag)
        ->get();
        $result = $query->getResult();
        if($result){
            return $this->baseHomeView('announcements/index', ['announcements' => $result], ['title' => $tag.' - Announcement', 'cssPath' => 'css/announcements.css']);
        }
        return redirect('announcements');
    }

    public function getByTagAndName($tag, $name){
        $query = $this->ann_model->select('announcements.*')
        ->join('announcements_tags', 'announcements.id = announcements_tags.announcement_id')
        ->join('tags', 'announcements_tags.tag_id = tags.id')
        ->where('tags.tag_compiled', $tag)
        ->where('announcements.title_compiled', $name)
        ->get();
        $result = $query->getResult();
        dd($result);
        if($result){
            return $this->baseHomeView('announcements/details', ['announcement' => $result], ['title' => $name.' - Announcement', 'cssPath' => 'css/announcements.css']);
        }
        return redirect('announcements');
    }

    public function list(){
        $results = $this->ann_model->listAll();
        return $results;
    }

}
