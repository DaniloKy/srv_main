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
        try{
            $response = $this->client->get('classes');
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
        return $this->baseHomeView('signup/character/create', ["classes" => $data]);
    }

    public function create(){

        dd($this->request->getPost());
        $data = [];
        $count_results = $this->character_model->countWhere(['belong_to' => session('userdata')['user']['id']]);

        if($count_results >= 3){
            return $this->baseHomeView('signup/character/select', ["characters" => $data]);
        }

        $response = $this->client->post('character', json_encode([

        ]));
        $data = json_decode($response->getBody());
        
        dd(["characters"=> $data]);

         /*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('SERVER_URL').'character');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 
            json_encode(array('username' => 'TORRES', "class" => "mage"))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        if ($server_output == "OK") { 
            dd('OK', $server_output);
        } else { 
            dd('NOT OK', $server_output);
        }
        */

    }

}
