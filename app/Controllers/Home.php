<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Home extends BaseController
{

    public function index()
    {
        return $this->baseHomeView('index', [], ['title' => 'Survive Utopia']);
    }
}
