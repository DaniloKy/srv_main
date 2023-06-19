<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ClassController extends BaseController
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

}
