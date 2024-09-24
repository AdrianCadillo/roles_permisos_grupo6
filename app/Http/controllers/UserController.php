<?php
namespace app\Http\controllers;

use app\Http\lib\Controller;
use app\models\Role;
use app\models\Usuario;
use app\models\Usuario_Role;

class UserController extends Controller
{
    public function index()
    {
        
       View("users.index");
    }

    /**
     * STORE
     */
    public function store()
    {
        echo $this->post("profesion");
    }
}