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
        $data = [
            'announcements' => $this->ann_model->listPaginate([])->paginate(5),
            'tags' => $this->tag_model->listAll(),
            'pager' => $this->ann_model->pager,
        ];
        
        //return $this->baseHomeView('signup/admin/announcement/manage', [$data, 'tags' => $tags], ['title' => 'Announcements Dashboard']);
        return $this->baseHomeView('signup/admin/announcement/manage', $data, ['title' => 'Announcements Dashboard']);
    }

    public function create(){
        if($this->request->getMethod() == "post"){
            $createAnn = $this->request->getPost();
            $val = $this->validate_form($createAnn, 'createAnnouncement');
            if($val){
                if(!$this->ann_model->getWhere(['title' => $createAnn['title']], true)){
                    $annImage = $this->request->getFile('image');
                    if($annImage->isValid() && !$annImage->hasMoved()){
                        $createAnn['image_path'] = $this->upload_image('announcements', $annImage);
                        $this->resizeImage($createAnn['image_path'], 'thumb', 'announcements', 350, 200);
                        $createAnn['created_by'] = session('userdata')['user']['id'];
                        $id = $this->ann_model->create($createAnn);
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
                'tag_id' => $this->request->getVar('tag_id'),
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
                        $updateAnn['image_path'] = $this->upload_image('announcements', $annImage);
                        $this->resizeImage($updateAnn['image_path'], 'thumb', 'announcements', 350, 200);
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
        $annInfo = $this->ann_model->list(['announcements.id' => $id]);
        if($annInfo[0]){
            $announcements = $this->ann_model->list([]);
            $tags = $this->tag_model->listAll();
            return $this->baseHomeView('signup/admin/announcement/manage', 
                ['isPUT' => true, 'announcements' => $announcements, 
                'tags' => $tags,
                'annInfo' => $annInfo[0]
            ], ['title' => 'Update Announcement']);
        }
        return redirect()->to('user/admin/announcements/manage');
    }

    public function delete(){
        $deleteId = $this->request->getVar('id');
        $ann = $this->ann_model->getById($deleteId);
        if($ann){
            $this->delteImages('announcements', $ann['image_path']);
            $this->ann_model->delete(['id' => $ann['id']]);
        }
        return redirect()->to('user/admin/announcements/manage');
    }

}
