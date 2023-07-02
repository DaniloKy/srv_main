<?php

namespace App\Controllers;

use App\Models\LoginModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Main extends BaseController
{

    protected $login_model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        $playerInfo = session()->get('playerInfo')['player'];
        return $this->baseGameView('signup/game/lobby', ['playerInfo' => $playerInfo], ['title' => 'Lobby', 'cssPath' => 'css/game_lobby.css']);
    }

    public function career()
    {
        $playerInfo = session()->get('playerInfo')['player'];
        $playerInfo->wl = $playerInfo->games_lost!=0?number_format(($playerInfo->games_won/$playerInfo->games_lost), 2):0;
        $playerInfo->kd = $playerInfo->deaths!=0?number_format(($playerInfo->kills/$playerInfo->deaths), 2):0;
        return $this->baseGameView('signup/game/career', ['playerInfo' => $playerInfo], ['title' => 'Career', 'cssPath' => 'css/game_career.css']);
    }

    public function changeClass(){
        session()->remove('playerInfo');
        return redirect()->to('user/character/list');
    }

}
