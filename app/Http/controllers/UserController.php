<?php
namespace app\Http\controllers;

use app\Http\lib\Controller;
use app\models\Role;
use app\models\Usuario;

class UserController extends Controller
{
    public function index()
    {
       $userc = new Usuario;
       $rolemodel = new Role;

       //echo $userc->query();
       print_r($rolemodel->query()->select("nombre_rol")
       ->where("id_rol","=",2)
       ->And("nombre_rol","=","Vendedores")
       ->Or("id_rol","=",3) /// V
       ->get());
       return;
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