<?php

namespace App\Controllers;

use App\Models\ClassesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class AnnouncementAdminController extends BaseController
{
    protected $class_model;
    
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->class_model = model(AnnouncementsModel::class);
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        $data = $this->list();
        //dd($data);
        return $this->baseHomeView('signup/admin/announcement/manage', ['announcements' => $data]);
    }

    public function list(){
        $results = $this->class_model->listAll();
        return $results;
    }

    public function create(){
        if($this->request->getMethod() == "post"){
            $createClass = $this->request->getPost();
            $val = $this->validate_form($createClass, 'createAnnouncement');
            if($val){
                if(!$this->class_model->getWhere(['title' => $createClass['title']], true)){
                    $classImage = $this->request->getFile('image');
                    if($classImage->isValid() && !$classImage->hasMoved()){
                        $createClass['image_path'] = $this->upload_image($this->class_model->table, $classImage);
                        $createClass['created_by'] = session('userdata')['user']['id'];
                        $this->class_model->create($createClass);
                        $this->session->setFlashdata('success', 'Published!');
                        return redirect('user/admin/announcements/manage');
                    }
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
            return redirect()->to('user/admin/announcements/manage')->withInput();
        }else if($this->request->getMethod() == "put"){
            $updateClass = [
                'name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
            ];
            $class_id = $this->request->getVar('id');
            $val = $this->validate_form($updateClass, 'updateAnnouncement');
            if($val){
                $existing = $this->class_model->getById($class_id);
                if($existing['name'] !== $updateClass['name']){
                    if($this->class_model->getWhere(['name' => $updateClass['name']], true)){
                        $this->session->setFlashdata('error', 'Publication with the same name already exists.');
                        return redirect()->to('user/admin/announcements/edit/'.$class_id)->withInput();
                    }
                }
                $classImage = $this->request->getFile('image');
                if($classImage->isValid() && !$classImage->hasMoved()){
                    $valImage = $this->validate_form(['image' => $classImage], 'validImage');
                    if($valImage){
                        $this->delteFiles('classes', $existing['image_path']);
                        $updateClass['image_path'] = $this->upload_image($this->class_model->table, $classImage);
                    }else
                        return redirect()->to('user/admin/announcements/edit/'.$class_id)->withInput();                
                }
                $this->class_model->update($class_id, $updateClass);
                $this->session->setFlashdata('success', 'Updated!');
                return redirect('user/admin/announcements/manage');
            }
            return redirect()->to('user/admin/announcements/edit/'.$class_id)->withInput();
        }
        return redirect()->to('user/admin/announcements/manage');
    }

    public function updater($id){
        $results = $this->class_model->getById($id);
        $data = $this->list();
        return $this->baseHomeView('signup/admin/announcement/manage', ['isPUT' => true, 'announcements' => $data, 'annInfo' => $results]);
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        $class = $this->class_model->getById($deleteChar['id']);
        if($class){
            $this->delteFiles('announcements', $class['image_path']);
            $this->class_model->delete(['id' => $deleteChar['id']]);
        }
        return redirect()->to('user/admin/announcements/manage');
    }

}
