<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CharacterModel;
use Psr\Log\LoggerInterface;

class Character extends BaseController
{

    protected $character_model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->character_model = model(CharacterModel::class);
    }

    public function list()
    {
        $data = ['characters' => []];
        if($this->character_model->countInTable() > 0)
            dd($this->character_model->getAll());
            //$data = ['characters' => $this->character_model->getAll()];
        return $this->baseHomeView('signup/character/select', $data);
    }
}
