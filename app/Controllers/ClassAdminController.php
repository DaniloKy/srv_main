<?php

namespace App\Controllers;

use App\Models\ClassesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ClassAdminController extends BaseController
{
    protected $classe_model;
    
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        $this->classe_model = model(ClassesModel::class);
        parent::initController($request, $response, $logger);
    }

    public function manage(){
        $data = $this->list();
        dd($data);
        return $this->baseHomeView('signup/admin/manage', $data);
    }

    public function list(){
        $results = $this->classe_model->listAll();
        return $results;
    }

    public function createView(){
        return $this->baseHomeView('');
    }

    public function create(){
        $createClass = $this->request->getPost();
        $val = $this->validate_form($createClass, 'createClass');
        if($val){

        }
    }

}
