<?php 
namespace app\Http\lib;

use app\models\Usuario_Role;

class Controller 
{ 
    use Request,Csrf,Fecha;
    /**
     * 
     */
    public function saludar()
    {
        return "HOLA MUNDO!!";
    }


    /**
     * Me traiga los roles de un usuario
     */
    public function UserRoles($id){
        $usuariorole = new Usuario_Role;

        $roles = $usuariorole->query()
                             ->Join("usuarios as u","ur.id_usuario","=","u.id_usuario")
                             ->Join("roles as r","ur.id_rol","=","r.id_rol")
                             ->where("ur.id_usuario","=",$id)
                             ->select("r.id_rol","r.nombre_rol")
                             ->get();

        return $roles;
                                   
    }
}