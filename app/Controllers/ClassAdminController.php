<?php

namespace App\Controllers;

use App\Models\ClassesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
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
        return $this->baseHomeView('signup/admin/class/manage', ['classes' => $data]);
    }

    public function list(){
        $results = $this->class_model->listAll();
        return $results;
    }

    public function createView(){
        return $this->baseHomeView('');
    }

    public function create(){
        dd($updateClass = $this->request->getRawInput());
        if($this->request->getMethod() == "post"){
            $createClass = $this->request->getPost();
            $val = $this->validate_form($createClass, 'createClass');
            if($val){
                if(!$this->class_model->getWhere(['name' => $createClass['name']], true)){
                    $classImage = $this->request->getFile('image');
                    if($classImage->isValid() && !$classImage->hasMoved()){
                        $randName = $classImage->getRandomName();
                        $classImage->move('./images/publishedClasses', $randName);
                        $createClass['image_path'] = $randName;
                        $this->class_model->create($createClass);
                        $this->session->setFlashdata('success', 'Published!');
                        return redirect('user/admin/classes/manage');
                    }
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
        }else if($this->request->getMethod() == "put"){
            $updateClass = $this->request->getRawInput();
            dd($updateClass);
            $val = $this->validate_form($updateClass, 'updateClass');
            if($val){
                if(!$this->class_model->getWhere(['name' => $updateClass['name']], true)){
                    $classImage = $this->request->getFile('image');
                    if($classImage->isValid() && !$classImage->hasMoved()){
                        $randName = $classImage->getRandomName();
                        $classImage->move('./images/publishedClasses', $randName);
                        $updateClass['image_path'] = $randName;
                    }
                    $this->class_model->update($updateClass['id'], $updateClass);
                    $this->session->setFlashdata('success', 'Updated!');
                    return redirect('user/admin/classes/manage');
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
        }
        return redirect('user/admin/classes/manage')->withInput();
    }

    public function updater($id){
        $results = $this->class_model->getById($id);
        $data = $this->list();
        return $this->baseHomeView('signup/admin/class/manage', ['isPUT' => true, 'classes' => $data, 'classInfo' => $results]);
    }

    public function delete(){
        return null;
    }

}
