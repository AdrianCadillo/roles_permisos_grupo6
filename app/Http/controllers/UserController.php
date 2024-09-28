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
     * Método para crear usuarios
     */
    public function create()
    {
        $role = new Role;
        $Roles = $role->procedure("proc_users","c",[1]);
        View("users.create",compact("Roles"));
    }

    /**
     * STORE
     */
    public function store()
    { 
        if($this->VerifyTokenCsrf($this->post("token_"))){
            echo $this->post("apellidos");
        }else{
            echo "error en el token csrf";
        }
    }
}