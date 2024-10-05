<?php
namespace app\Http\controllers;

use app\Http\lib\Controller;
use app\models\Role;
use app\models\Usuario;
use app\models\Usuario_Role;

class UserController extends Controller
{
    private $Errors = [];
    public function index()
    {
       /// mostrar a los usuarios
       $usuario = new Usuario;

       $usuarios = $usuario->query()
                           ->select("id_usuario","name","email","estado")
                           ->get();
       View("users.index",["usuarios"=>$usuarios]);
    }

    /**
     * Método para crear usuarios
     */
    public function create()
    {
        $role = new Role;
        $Roles = $role->query()->get();
        View("users.create",compact("Roles"));
    }

    /**
     * STORE
     */
    public function store()
    { 
        if($this->VerifyTokenCsrf($this->post("token_"))){
           if(empty($this->post("name"))){
             $this->Errors [] = "Complete su nombre de usuario!";
           }else{
            $this->session("name",$this->post("name"));
           }

           if(empty($this->post("email"))){
             $this->Errors [] = "Complete su correo electrónico!";
           }else{
             if(!filter_var($this->post("email"),FILTER_VALIDATE_EMAIL)){
                $this->Errors[] = "El correo ingresado es incorrecto!";
             }
             $this->session("email",$this->post("email"));
           } 

           if($this->post("role") === ''){
            $this->Errors[] = "Seleccione por lo menos un rol!";
           }

           if(count($this->Errors) > 0){
             $this->session("errors",$this->Errors);
             redirect("/user/create");
             exit;
           }else{
            $this->procesoRegistroUsuario();
           }
        }else{
            $this->session("error_token","error_token");
        }

        redirect("users");
    }

    /**
     * Método para eliminar usuarios
     */
    public function delete($id){
        if($this->VerifyTokenCsrf($this->post("token_"))){

            $usuario = new Usuario;

            $response = $usuario->delete($id);

            json(["response" => $response]);
        }
    }


    /**
     * Registrar usuario
     */
    private function procesoRegistroUsuario(){
        /// registrar a los usuarios
        $usuario = new Usuario;  

        $UsuarioExiste = $usuario->query()->where("email", "=", $this->post("email"))
            ->Or("name", "=", $this->post("name"))->get();
        if (count($UsuarioExiste) > 0) {
            $this->session("existe", "El usuario con el correo | name que indicaste ya existe!");
        } else {
            
            $response = $usuario->create([
                "name" => $this->post("name"),
                "email" => $this->post("email"),
                "password" => password_hash($this->post("password"), PASSWORD_BCRYPT),
                "estado" => $this->post("estado")
            ]);

            if ($response) {
                $usuarioRegistrado = $usuario->query()->where("email", "=", $this->post("email"))->get();

                if (count($this->post("role")) > 0) {
                    $usu_role = new Usuario_Role;
                    foreach ($this->post("role") as $role) {
                        $usu_role->id_usuario = $usuarioRegistrado[0]->id_usuario;
                        $usu_role->id_rol = $role;

                        $responseData = $usu_role->save();
                    }

                    if ($responseData) {
                        $this->session("success", "Usuario registrado correctamente!");
                    }
                }
            } else {
                $this->session("error", "error");
            }
        }
    }
 

    /**
     * Método para editar a los usuarios
     */
    public function editar($id)
    {
        $usuariomodel = new Usuario; $modelrole = new Role; $usurolemodel = new Usuario_Role;

        $usuario = $usuariomodel->query()->where("id_usuario","=",$id)->get();
        /// mostramos todos los roles
        $rolesNotDelUsuario = $usuariomodel->procedure("proc_gestion_roles_user","C",[$id,'rna']);

        /// mostrar los roles del usuario que queremos editar
        
        $rolesDelUsuario = $usuariomodel->procedure("proc_gestion_roles_user","C",[$id,'ra']);

        View("users.editar",compact("usuario","rolesNotDelUsuario","rolesDelUsuario"));
    }


    /**
     * Para guardar los cambios del usuario
     */
    public function update($id){
        if($this->VerifyTokenCsrf($this->post("token_"))){
             $this->procesoModificar($id);
         }else{
             $this->session("error_token","error_token");
         }
 
         redirect("users");
    }

    /**
     * Proceso para guardar los cambios
     */
    private function procesoModificar($id)
    {
        $modelUser = new Usuario;  

        $response = $modelUser->update([
            "id_usuario" => $id,
            "name" => $this->post("name"),
            "email" => $this->post("email"),
            "estado" => $this->post("estado")
        ]);

        /// eliminar los roles antiguos asignados al usuairos
        $modelUser->procedure("proc_gestion_roles_user","d",[$id,'dr']);

        /// asignar nuevamente los roles
        if (count($this->post("role")) > 0) {
            $usu_role = new Usuario_Role;
            foreach ($this->post("role") as $role) {
                $usu_role->id_usuario = $id;
                $usu_role->id_rol = $role;

                $responseData = $usu_role->save();
            }

            if ($responseData) {
                $this->session("success", "Usuario modificado correctamente!");
            }
        }
        
        
    }

}