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
        $this->client = \Config\Services::curlrequest();
    }

    public function index()
    {
        try{
            $response = $this->client->head(env('SERVER_URL'));
            if($response->getStatusCode() != 200)
                return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);

            $characterId = session()->get('playerInfo')['player']->id;
            $response = $this->client->get(env('SERVER_URL').'character/'.$characterId.'/'.session('userdata')['user']['id']);
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
            return $e;#view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
    }

    public function career()
    {
        try{
            $response = $this->client->head(env('SERVER_URL'));
            if($response->getStatusCode() != 200)
                return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
            $characterId = session()->get('playerInfo')['player']->id;
            $response = $this->client->get(env('SERVER_URL').'character/'.$characterId.'/'.session('userdata')['user']['id']);
            $playerInfo = json_decode($response->getBody());
            $playerInfo->wl = $playerInfo->games_lost!=0?number_format(($playerInfo->games_won/$playerInfo->games_lost), 2):0;
            $playerInfo->kd = $playerInfo->deaths!=0?number_format(($playerInfo->kills/$playerInfo->deaths), 2):0;
            return $this->baseGameView('signup/game/career', ['playerInfo' => $playerInfo], ['title' => 'Career', 'cssPath' => 'css/game_career.css']);
        }catch(\Exception $e){
            return view("errors/html/error_503", ['message' => 'Server temporarily busy, overloaded, or down for maintenance.']);
        }
    }

    public function changeClass(){
        session()->remove('playerInfo');
        return redirect()->to('user/character/list');
    }

}
