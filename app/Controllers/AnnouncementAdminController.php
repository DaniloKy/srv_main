<?php

namespace App\Controllers;

use App\Models\AnnouncementsTagsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class AnnouncementAdminController extends BaseController
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
        $this->tag_model = model(TagsModel::class);
        $this->ann_tag_model = model(AnnouncementsTagsModel::class);
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        $data = $this->list();
        $tags = $this->tag_model->listAll();
        return $this->baseHomeView('signup/admin/announcement/manage', ['announcements' => $data, 'tags' => $tags], ['title' => 'Announcements Dashboard']);
    }

    public function list(){
        $results = $this->ann_model->listAll();
        return $results;
    }

    public function create(){
        if($this->request->getMethod() == "post"){
            $createAnn = $this->request->getPost();
            $val = $this->validate_form($createAnn, 'createAnnouncement');
            if($val){
                if(!$this->ann_model->getWhere(['title' => $createAnn['title']], true)){
                    $annImage = $this->request->getFile('image');
                    if($annImage->isValid() && !$annImage->hasMoved()){
                        $createAnn['image_path'] = $this->upload_image($this->ann_model->table, $annImage);
                        $createAnn['created_by'] = session('userdata')['user']['id'];
                        $id = $this->ann_model->create($createAnn);
                        if(count($createAnn['tags']) > 0){
                            foreach($createAnn['tags'] as $tag => $on){
                                if($etag = $this->tag_model->getById($tag)){
                                    $this->ann_tag_model->create(['announcement_id' => $id, 'tag_id' => $etag['id']]);
                                }
                            }
                        }
                        $this->session->setFlashdata('success', 'Published!');
                        return redirect('user/admin/announcements/manage');
                    }
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
            return redirect()->to('user/admin/announcements/manage')->withInput();
        }else if($this->request->getMethod() == "put"){
            $updateAnn = [
                'title' => $this->request->getVar('title'),
                'description' => $this->request->getVar('description'),
                'tags' => $this->request->getVar('tags'),
            ];
            $ann_id = $this->request->getVar('id');
            $val = $this->validate_form($updateAnn, 'updateAnnouncement');
            if($val){
                $existing = $this->ann_model->getById($ann_id);
                if($existing['title'] !== $updateAnn['title']){
                    if($this->ann_model->getWhere(['title' => $updateAnn['title']], true)){
                        $this->session->setFlashdata('error', 'Publication with the same name already exists.');
                        return redirect()->to('user/admin/announcements/edit/'.$ann_id)->withInput();
                    }
                }
                $annImage = $this->request->getFile('image');
                if($annImage->isValid() && !$annImage->hasMoved()){
                    $valImage = $this->validate_form(['image' => $annImage], 'validImage');
                    if($valImage){
                        $this->delteImages($this->ann_model->table, $existing['image_path']);
                        $updateAnn['image_path'] = $this->upload_image($this->ann_model->table, $annImage);
                    }else
                        return redirect()->to('user/admin/announcements/edit/'.$ann_id)->withInput();                
                }
                //dd($updateAnn['tags'], );
                /*
                $currentTags = $this->containTags($ann_id);
                foreach($updateAnn['tags'] as $tag => $on){
                    foreach($currentTags as $cTag => $on){
                        if($tag == $cTag){
                            return "has";
                        }else{
                            return "no ghas";
                        }
                            $this->ann_tag_model->delete(['announcement_id' => $ann_id, 'tag_id' => $etag['id']]);
                            $this->ann_tag_model->delete(['announcement_id' => $ann_id, 'tag_id' => $etag['id']]);
                    }
                }
                */
                $this->ann_model->update($ann_id, $updateAnn);
                $this->session->setFlashdata('success', 'Updated!');
                return redirect('user/admin/announcements/manage');
            }
            return redirect()->to('user/admin/announcements/edit/'.$ann_id)->withInput();
        }
        return redirect()->to('user/admin/announcements/manage');
    }

    public function updater($id){
        $results = $this->ann_model->getById($id);
        $data = $this->list();
        $tags = $this->tag_model->listAll();
        $check_tags = $this->containTags($id);
        return $this->baseHomeView('signup/admin/announcement/manage', ['isPUT' => true, 'announcements' => $data, 'tags' => $tags, 'annInfo' => $results, 'check_tags' => $check_tags], ['title' => 'Update Announcement']);
    }

    public function containTags($id){
        $check_tags = $this->ann_tag_model->getWhere(['announcement_id' => $id]);
        $tags = [];
        foreach($check_tags as $tag){
            $tags[] = $this->tag_model->getById($tag->tag_id);
        }
        return $tags;
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        $ann = $this->ann_model->getById($deleteChar['id']);
        if($ann){
            $this->delteImages('announcements', $ann['image_path']);
            $this->ann_model->delete(['id' => $deleteChar['id']]);
            $this->ann_tag_model->delete(['announcement_id' => $deleteChar['id']]);
        }
        return redirect()->to('user/admin/announcements/manage');
    }

}
