<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Main extends BaseController
{

    public function index()
    {        
        return $this->baseMainView('signup/main');
    }
}
