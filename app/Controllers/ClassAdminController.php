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
        return $this->baseHomeView('signup/admin/class/manage', ['classes' => $data]);
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
                        $randName = $classImage->getRandomName();
                        $imageStatus = $classImage->move('./images/storage/classes', $randName);
                        if($imageStatus){
                            $this->resizeImage($randName, 'thumbnail','classes');
                            $this->resizeImage($randName, 'publish','classes');
                        }
                        $createClass['image_path'] = $randName;
                        $this->class_model->create($createClass);
                        $this->session->setFlashdata('success', 'Published!');
                        return redirect('user/admin/classes/manage');
                    }
                }else
                    $this->session->setFlashdata('error', 'Publication with the same name already exists.');
            }
            return redirect('user/admin/classes/manage')->withInput();
        }else if($this->request->getMethod() == "put"){
            $updateClass = [
                'name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
            ];
            $class_id = $this->request->getVar('id');
            $val = $this->validate_form($updateClass, 'updateClass');
            if($val){
                $existing = $this->class_model->getById($class_id);
                if($existing['name'] !== $updateClass['name']){                    
                    if($this->class_model->getWhere(['name' => $updateClass['name']], true)){
                        $this->session->setFlashdata('error', 'Publication with the same name already exists.');
                        return redirect('user/admin/classes/edit/'.$class_id)->withInput();
                    }
                }
                $classImage = $this->request->getFile('image');
                $valImage = $this->validate_form(['image' => $classImage], 'validImage');
                if($valImage){
                    if($classImage->isValid() && !$classImage->hasMoved())
                        $updateClass['image_path'] = $this->update_image($existing['image_path'], $classImage);
                }else
                    return redirect()->to('user/admin/classes/edit/'.$class_id)->withInput();
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
        return $this->baseHomeView('signup/admin/class/manage', ['isPUT' => true, 'classes' => $data, 'classInfo' => $results]);
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        $class = $this->class_model->getById($deleteChar['id']);
        if($class){
            $this->delteFiles('classes', $class['image_path']);
            $this->class_model->delete(['id' => $deleteChar['id']]);
        }
        return redirect()->to('user/admin/classes/manage');
    }

    public function delteFiles($folder ,$file){
        unlink('./images/storage/'.$folder.'/'.$file);
        unlink('./images/thumb/'.$folder.'/'.$file);
        unlink('./images/publish/'.$folder.'/'.$file);
    }

    public function resizeImage($fileName, $type, $path){
        if($type === 'publish'){
            $size['width'] = 100;
            $size['height'] = 100;
            $place = 'thumb';
        }else if($type === 'thumbnail'){
            $size['width'] = 1250;
            $size['height'] = 1000;
            $place = 'publish';
        }
        $image = \Config\Services::image('gd');
        $image->withFile('./images/storage/'.$path.'/'.$fileName)
            ->fit($size['width'], $size['height'], 'center')
            ->save('./images/'.$place.'/'.$path.'/'.$fileName);
    }

    public function update_image($image_path, $image){
        $this->delteFiles('classes', $image_path);
        $randName = $image->getRandomName();
        $imageStatus = $image->move('./images/storage/classes', $randName);
        if($imageStatus){
            $this->resizeImage($randName, 'thumbnail','classes');
            $this->resizeImage($randName, 'publish','classes');
        }
        return $randName;
    }

}
