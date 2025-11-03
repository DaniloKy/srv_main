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
        
        // APONTAR PARA O FICHEIRO DE CERTIFICADOS INTERNO DO APACHE/XAMPP
        // Este é o caminho standard do XAMPP
        $caBundlePath = 'C:/xampp/apache/bin/curl-ca-bundle.pem';
        #var_dump(file_exists('C:/xampp/apache/bin/curl-ca-bundle.pem'));

        // Vamos verificar se ele existe, só para ter a certeza
        if (!file_exists($caBundlePath)) {
            // Se falhar, é porque o seu XAMPP está instalado noutro local.
            die("ERRO CRÍTICO: Não foi possível encontrar o ficheiro de certificados do XAMPP em " . $caBundlePath);
        }

        $this->client = \Config\Services::curlrequest([
            'baseURI' => env('SERVER_URL'),
            'headers' => [
                'Authorization' => env('AUTHORIZATION_TOKEN'),
                'Accept' => 'application/json',
                'Content-Type' =>  'application/json',
            ],
            'verify' => false,
            /*'curl' => [
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_CAINFO => $caBundlePath,
            ],*/
        ]);

    }

    public function index(){
        if(session()->has('playerInfo'))
            return redirect()->to('game/lobby');
        return redirect()->to('user/character/list');
    }

    public function list()
    {
        $data = [];
        $response = null;
        try{
            $response = $this->client->get('character/belong_to/'.session('userdata')['user']['id']);
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            return $e->__toString(); #view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance. '.$e]);
        }
        return $this->baseHomeView('signup/character/select', ["characters" => $data], ['title' => 'Select Your Character']);
    }

    public function select(){
        $characterId = $this->request->getVar('character');
        try{
            $response = $this->client->get('character/'.$characterId.'/'.session('userdata')['user']['id']);
            $data = json_decode($response->getBody());
            session()->set('playerInfo', ["player" => $data]);
            return redirect()->to('game/lobby');
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.'.$e]);
        }
    }

    public function createView(){
        $data = [];        
        try{
            $response = $this->client->get('character/belong_to/'.session('userdata')['user']['id']);
            $char_data = json_decode($response->getBody());
            if(count($char_data) >= env('MAX_CHARACTERS')){
                return redirect()->to('user/character/list');
            }
            $response = $this->client->get('classes');
            $data = json_decode($response->getBody());
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.'.$e]);
        }
        return $this->baseHomeView('signup/character/create', ["classes" => $data, 'class' => $_GET['class']??false], ['title' => 'Create Your Character']);
    }

    public function create(){
        
        $createChar = $this->request->getPost();
        $val = $this->validate_form($createChar, 'createChar');
        if($val){
            try{
                $userId = session('userdata')['user']['id'];
                $response = $this->client->get('character/belong_to/'.$userId);
                $char_data = json_decode($response->getBody());
                if(count($char_data) >= env('MAX_CHARACTERS')){
                    return redirect()->to('user/character/list');
                }
                if(!$this->client->get('classes/'.$createChar['character'].'?name=true')){
                    $this->session->setFlashdata('creation_error', 'Class does not exist.');
                    return redirect()->to('user/character/create');
                }
                
                if($this->client->get('character/'.$createChar['character_name'].'?username=true')->getBody() == "null"){
                    $this->client->post('character', [
                        'body' => 
                            json_encode([
                                'username' => $createChar['character_name'],
                                'belong_to' => $userId,
                                'class_name' => $createChar['character'],
                            ])
                        ]);
                    return redirect()->to('user/character/list');
                }else
                    $this->session->setFlashdata('creation_error', 'Username already in use.');
            }catch(Exception $e){
                return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.'.$e]);
            }
        }
        return redirect()->to('user/character/create')->withInput();
    }

    public function delete(){
        $deleteChar = $this->request->getPost();
        try{
            $this->client->delete('character/'.session('userdata')['user']['id']."/".$deleteChar['character_name']);
        }catch(Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.'.$e]);
        }
        return redirect()->to('user/character/list');
    }

}
