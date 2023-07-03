<?php

namespace App\Controllers;

use App\Models\LoginModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Game extends BaseController
{

    protected $login_model;
    private $client;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->client = \Config\Services::curlrequest();
    }

    public function play()
    {
        try{
            $response = $this->client->head(env('SERVER_URL'));
            if($response->getStatusCode() != 200)
                return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);

            $playerInfo = session()->get('playerInfo')['player'];
            return view("signup/game/play", ['playerInfo' => $playerInfo]);
        }catch(\Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
    }
}
