<?php
namespace app\Http\controllers;

use app\Http\lib\Auth;
use app\Http\lib\Controller;
use app\models\Role;

class AuthController extends Controller
{
    use Auth;
    /***Método para a mostrar la vista del login */
    public function ViewLogin(){

      $this->Auth();
      $modelrole = new Role;

      /// consultamos los roles existentes
      $roles = $modelrole->query()->get();
      View("auth.login",compact("roles"));
    }

    /**
     * Método se va encargar de realizar la authenticación de usuarios
     */
    public function login(){

         if($this->VerifyTokenCsrf($this->post("token_"))){
            if(!empty($this->post("login"))){
                $this->session("login",$this->post("login"));
             }
            $RememberMe = !empty($this->post("remember")) ? true:false;
             
            $this->attemp([
                "rol" => $this->post("rol"),
                "login" => $this->post("login"),
                "password" => $this->post("password")
            ],$RememberMe);
         }
    }

    /**
     * Método realiza el proceso de cerrar la sesión
     */
    public function logout()
    {
        if($this->VerifyTokenCsrf($this->post("token_"))){
            $this->CerrarSesion();
        }  
    }

}