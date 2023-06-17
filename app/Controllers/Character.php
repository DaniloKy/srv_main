<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CharacterModel;
use Exception;
use PHPUnit\Framework\Constraint\IsEmpty;
use Psr\Log\LoggerInterface;

use function PHPUnit\Framework\isEmpty;

class Character extends BaseController
{

    public $name;
    protected $character_model;
    private $client;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->character_model = model(CharacterModel::class);
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
        $count_results = $this->character_model->countWhere(['belong_to' => session('userdata')['user']['id']]);

        if(!$count_results > 0){
            return $this->baseHomeView('signup/character/select', ["characters" => $data]);
        }
        try{
        //$query_results = $this->character_model->getWhere(['belong_to' => session('userdata')['user']['id']]);
            $response = $this->client->get('character/'.session('userdata')['user']['username']."?username=true");
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            return view("error/503", ['message' => $e]);
        }
        //dd($data);
        return $this->baseHomeView('signup/character/select', ["characters" => $data]);
    }

    public function select(){
        $id = $this->request->getPost();
        if(empty($id) || !$this->character_model->getWhere(['game_id' => $id])){
            $this->session->setFlashdata('error', 'You can not do that!');
            return redirect('game/character/list');
        }
        
        $response = $this->client->get('character'.$id);
        $data = json_decode($response->getBody());
        
        dd(["characters"=> $data]);
        return $this->baseHomeView('signup/character/select', ["characters" => $data]);
    }

    public function createView(){
        $data = [];
        $count_results = $this->character_model->countWhere(['belong_to' => session('userdata')['user']['id']]);
        if($count_results >= 3){
            return $this->baseHomeView('signup/character/select', ["characters" => $data]);
        }
        try{
            $response = $this->client->get('classes');
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
        return $this->baseHomeView('signup/character/create', ["classes" => $data, 'class' => $_GET['class']??false]);
    }

    public function create(){
        $data = [];
        $count_results = $this->character_model->countWhere(['belong_to' => session('userdata')['user']['id']]);
        if($count_results >= 3){
            return $this->baseHomeView('signup/character/select', ["characters" => $data]);
        }
        $createChar = $this->request->getPost();
        $val = $this->validate_form($createChar, 'createChar');
        if($val){
            try{
                if(!$this->client->get('classes/'.$createChar['character'].'?name=true')){
                    $this->session->setFlashdata('creation_error', 'Class does not exist.');
                    return redirect('game/character/create');
                }
                if(!$this->client->get('character/'.$createChar['character_name'].'?username=true')->getBody() != "null"){
                    $this->client->post('character', [
                        'username' => $createChar['character_name'],
                        'class_name' => $createChar['character'],
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

}
