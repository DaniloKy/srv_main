<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CharacterModel;
use Psr\Log\LoggerInterface;

class Character extends BaseController
{

    public $name;
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('SERVER_URL').'character');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data);
        //dd(["characters"=> $data]);
        return $this->baseHomeView('signup/character/select', ["characters" => $data]);
        
        /*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://www.example.com/tester.phtml");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 
            http_build_query(array('username' => 'ben'))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        if ($server_output == "OK") { 
            dd('OK');
        } else { 
            dd('NOT OK');
        }
        */
    }

}
