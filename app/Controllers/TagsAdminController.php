<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class TagsAdminController extends BaseController
{
    protected $tag_model;
    
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->tag_model = model(TagsModel::class);
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        $tags = $this->tag_model->listAll();
        return $this->baseHomeView('signup/admin/tags/manage', ['tags' => $tags]);
    }

    public function create(){
        $createTag = $this->request->getPost();
        $val = $this->validate_form($createTag, 'createTags');
        if($val){
            if(!$this->tag_model->getWhere(['tag' => $createTag['tag']], true)){
                $this->tag_model->create($createTag);
                $this->session->setFlashdata('success', 'Created!');
                return redirect('user/admin/tags/manage');
            }else
                $this->session->setFlashdata('error', '`'.$createTag['tag'].'` tag already exists.');
        }
        return redirect()->to('user/admin/tags/manage')->withInput();
        //return redirect()->to('user/admin/tags/manage');
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        $tag = $this->tag_model->getById($deleteChar['id']);
        if($tag){
            $this->tag_model->delete(['id' => $deleteChar['id']]);
        }
        return redirect()->to('user/admin/tags/manage');
    }

}
