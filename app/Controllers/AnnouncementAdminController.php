<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class AnnouncementAdminController extends BaseController
{
    protected $ann_model;
    protected $tag_model;
    
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->ann_model = model(AnnouncementsModel::class);
        $this->tag_model = model(TagsModel::class);
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        $data = $this->list();
        $tags = $this->tag_model->listAll();
        return $this->baseHomeView('signup/admin/announcement/manage', ['announcements' => $data, 'tags' => $tags]);
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
                        $this->ann_model->create($createAnn);
                        $this->session->setFlashdata('success', 'Published!');
                        return redirect('user/admin/announcements/manage');
                    }
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
            return redirect()->to('user/admin/announcements/manage')->withInput();
        }else if($this->request->getMethod() == "put"){
            $updateAnn = [
                'name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
            ];
            $ann_id = $this->request->getVar('id');
            $val = $this->validate_form($updateAnn, 'updateAnnouncement');
            if($val){
                $existing = $this->ann_model->getById($ann_id);
                if($existing['name'] !== $updateAnn['name']){
                    if($this->ann_model->getWhere(['name' => $updateAnn['name']], true)){
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
        return $this->baseHomeView('signup/admin/announcement/manage', ['isPUT' => true, 'announcements' => $data, 'annInfo' => $results]);
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        $ann = $this->ann_model->getById($deleteChar['id']);
        if($ann){
            $this->delteImages('announcements', $ann['image_path']);
            $this->ann_model->delete(['id' => $deleteChar['id']]);
        }
        return redirect()->to('user/admin/announcements/manage');
    }

}
