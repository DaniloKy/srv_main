<?php

namespace App\Controllers;

use App\Models\LoginModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Main extends BaseController
{

    protected $login_model;
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
            'verify' => false,
            'http_errors' => false,
        ]);
    }

    public function index()
    {
        try{
            $response = $this->client->head('/');
            if($response->getStatusCode() != 200)
                return "API Error (" . $response->getStatusCode() . "): " . $response->getBody();

            $characterId = session()->get('playerInfo')['player']->id;
            $response = $this->client->get('character/'.$characterId.'/'.session('userdata')['user']['id']);
            
            if ($response->getStatusCode() >= 400) {
                return "API Error (" . $response->getStatusCode() . "): " . $response->getBody();
            }

            $playerInfo = json_decode($response->getBody());
            session()->set('playerInfo', ["player" => $playerInfo]);
            $playerInfo->playerLevel = [
                "level" => $playerInfo->level,
                "nextLevel" => $playerInfo->level+1,
                "xpTo" => $playerInfo->xpToLvl - $playerInfo->xp,
                "progress" => ( $playerInfo->xp * 1)/$playerInfo->xpToLvl,
            ];
            return $this->baseGameView('signup/game/lobby',
                ['playerInfo' => $playerInfo], 
                ['title' => 'Lobby', 'cssPath' => 'css/game_lobby.css', 
                    'jsPath' => ['type' => 'module', 'script' => 'js/lobby_script.js']
                ]
            );
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function career()
    {
        try{
            $response = $this->client->head('/');
            if($response->getStatusCode() != 200)
                return "API Error (" . $response->getStatusCode() . "): " . $response->getBody();
            $characterId = session()->get('playerInfo')['player']->id;
            $response = $this->client->get('character/'.$characterId.'/'.session('userdata')['user']['id']);
            
            if ($response->getStatusCode() >= 400) {
                return "API Error (" . $response->getStatusCode() . "): " . $response->getBody();
            }

            $playerInfo = json_decode($response->getBody());
            $playerInfo->wl = $playerInfo->games_lost!=0?number_format(($playerInfo->games_won/$playerInfo->games_lost), 2):0;
            $playerInfo->kd = $playerInfo->deaths!=0?number_format(($playerInfo->kills/$playerInfo->deaths), 2):0;
            return $this->baseGameView('signup/game/career', ['playerInfo' => $playerInfo], ['title' => 'Career', 'cssPath' => 'css/game_career.css']);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function changeClass(){
        session()->remove('playerInfo');
        return redirect()->to('user/character/list');
    }

}
