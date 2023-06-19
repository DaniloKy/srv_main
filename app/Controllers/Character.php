<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class Character extends BaseController
{

    public $name;
    private $client;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->client = \Config\Services::curlrequest([
            'baseURI' => env('SERVER_URL'),
            'headers' => [
                'Authorization' => env('AUTHORIZATION_TOKEN'),
                'Accept' => 'application/json',
                'Content-Type' =>  'application/json',
            ],
        ]);
    }

    public function list()
    {
        $data = [];
        try{
            $response = $this->client->get('character/belong_to/'.session('userdata')['user']['id']);
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            var_dump($e);
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
        return $this->baseHomeView('signup/character/select', ["characters" => $data]);
    }

    public function select(){
        $id = $this->request->getPost();
        //if(empty($id) || !$this->character_model->getWhere(['game_id' => $id])){
            $this->session->setFlashdata('error', 'You can not do that!');
            return redirect('game/character/list');
        //}
        
        $response = $this->client->get('character'.$id);
        $data = json_decode($response->getBody());
        
        dd(["characters"=> $data]);
        return redirect('game/character/list');
    }

    public function createView(){
        $data = [];        
        try{
            $response = $this->client->get('character/belong_to/'.session('userdata')['user']['id']);
            $char_data = json_decode($response->getBody());
            if(count($char_data) >= 3){
                return redirect('game/character/list');
            }
            $response = $this->client->get('classes');
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
        return $this->baseHomeView('signup/character/create', ["classes" => $data, 'class' => $_GET['class']??false]);
    }

    public function create(){
        
        $createChar = $this->request->getPost();
        $val = $this->validate_form($createChar, 'createChar');
        if($val){
            try{
                $userId = session('userdata')['user']['id'];
                $response = $this->client->get('character/belong_to/'.$userId);
                $char_data = json_decode($response->getBody());
                if(count($char_data) >= 3){
                    return redirect('game/character/list');
                }
                if(!$this->client->get('classes/'.$createChar['character'].'?name=true')){
                    $this->session->setFlashdata('creation_error', 'Class does not exist.');
                    return redirect('game/character/create');
                }
                
                if($this->client->get('character/'.$createChar['character_name'].'?username=true')->getBody() == "null"){
                    //dd("post");
                    $this->client->post('character', [
                        'body' => 
                            json_encode([
                                'username' => $createChar['character_name'],
                                'belong_to' => $userId,
                                'class_name' => $createChar['character'],
                            ])
                        ]);
                    return redirect()->to('game/character/list');
                }else
                    $this->session->setFlashdata('creation_error', 'Username already in use.');
            }catch(Exception $e){
                return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
            }
        }
        return redirect('game/character/create')->withInput();
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        try{
            $this->client->delete('character/'.session('userdata')['user']['id']."/".$deleteChar['character_name']);
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
        return redirect()->to('game/character/list');
    }

}
