<?php

namespace App\Controllers;

use App\Models\ClassesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class ClassAdminController extends BaseController
{
    protected $class_model;
    
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->class_model = model(ClassesModel::class);
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        $data = $this->list();
        return $this->baseHomeView('signup/admin/class/manage', ['classes' => $data], ['title' => 'Classes Dashboard']);
    }

    public function list(){
        $results = $this->class_model->listAll();
        return $results;
    }

    public function create(){
        if($this->request->getMethod() == "post"){
            $createClass = $this->request->getPost();
            $val = $this->validate_form($createClass, 'createClass');
            if($val){
                if(!$this->class_model->getWhere(['name' => $createClass['name']], true)){
                    $classImage = $this->request->getFile('image');
                    if($classImage->isValid() && !$classImage->hasMoved()){
                        $createClass['image_path'] = $this->upload_image($this->class_model->table, $classImage);
                        $this->resizeImage($createClass['image_path'], 'thumb', 'classes', 250, 280);
                        $this->class_model->create($createClass);
                        $this->session->setFlashdata('success', 'Published!');
                        return redirect('user/admin/classes/manage');
                    }
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
            return redirect()->to('user/admin/classes/manage')->withInput();
        }else if($this->request->getMethod() == "put"){
            $updateClass = [
                'name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'nickname' => $this->request->getVar('nickname')
            ];
            $class_id = $this->request->getVar('id');
            $val = $this->validate_form($updateClass, 'updateClass');
            if($val){
                $existing = $this->class_model->getById($class_id);
                if($existing['name'] !== $updateClass['name']){
                    if($this->class_model->getWhere(['name' => $updateClass['name']], true)){
                        $this->session->setFlashdata('error', 'Publication with the same name already exists.');
                        return redirect()->to('user/admin/classes/edit/'.$class_id)->withInput();
                    }
                }
                $classImage = $this->request->getFile('image');
                if($classImage->isValid() && !$classImage->hasMoved()){
                    $valImage = $this->validate_form(['image' => $classImage], 'validImage');
                    if($valImage){
                        $this->delteImages($this->class_model->table, $existing['image_path']);
                        $updateClass['image_path'] = $this->upload_image('classes', $classImage);
                        $this->resizeImage($updateClass['image_path'], 'thumb', 'classes', 250, 280);
                    }else
                        return redirect()->to('user/admin/classes/edit/'.$class_id)->withInput();                
                }
                $this->class_model->update($class_id, $updateClass);
                $this->session->setFlashdata('success', 'Updated!');
                return redirect('user/admin/classes/manage');
            }
            return redirect()->to('user/admin/classes/edit/'.$class_id)->withInput();
        }
        return redirect()->to('user/admin/classes/manage');
    }

    public function updater($id){
        $results = $this->class_model->getById($id);
        $data = $this->list();
        return $this->baseHomeView('signup/admin/class/manage', ['isPUT' => true, 'classes' => $data, 'classInfo' => $results], ['title' => 'Update Class']);
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        $class = $this->class_model->getById($deleteChar['id']);
        if($class){
            $this->delteImages('classes', $class['image_path']);
            $this->class_model->delete(['id' => $deleteChar['id']]);
        }
        return redirect()->to('user/admin/classes/manage');
    }

}
