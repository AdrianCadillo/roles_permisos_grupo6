<?php
namespace app\Http\controllers;

use app\Http\lib\Auth;
use app\Http\lib\Controller;
use app\Http\lib\Upload;
use app\models\Role;
use app\models\Usuario;
use app\models\Usuario_Role;

class UserController extends Controller
{
    use Auth,Upload;
    private $Errors = [];
    public function index()
    {
        $this->noAuth();
       if($this->can("usuario.index")){
            /// mostrar a los usuarios
            $usuario = new Usuario;

            $usuarios = $usuario->query()
                ->select("id_usuario", "name", "email", "estado","foto")
                ->get();
            View("users.index", ["usuarios" => $usuarios]);
       }else{
        View("pageserrors.no_authorizado"); 
       }
    }

    /**
     * Método para crear usuarios
     */
    public function create()
    {
        if($this->can("usuario.index") && $this->can("usuario.create")){
            $this->noAuth();
            $role = new Role;
            $Roles = $role->query()->get();
            View("users.create", compact("Roles"));
        }else{
            View("pageserrors.no_authorizado");
        }
    }

    /**
     * STORE
     */
    public function store()
    { 
         $this->noAuth();
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

            /// Vamos a consultar al usuario
            $UserData = $usuario->query()->Where("id_usuario","=",$id)->get();

            /// Comprobamos si el usuario que deseamos eliminar tiene una foto registrada

            if($UserData[0]->foto != null){
                /// obtenemos la foto
                $FotoDelete = "fotos/".$UserData[0]->foto;
                unlink($FotoDelete);
            }

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
            $UploadFoto = $this->uploadFile("foto");
             if($UploadFoto === 'ok' || $UploadFoto === 'vacio'){
                $response = $usuario->create([
                    "name" => $this->post("name"),
                    "email" => $this->post("email"),
                    "password" => password_hash($this->post("password"), PASSWORD_BCRYPT),
                    "estado" => $this->post("estado"),
                    "foto" => $this->getNameArchivo()
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
             }else{
                $this->session("error_upload","Error_upload");
                redirect("user/create");
                exit;
             }
        }
    }
 

    /**
     * Método para editar a los usuarios
     */
    public function editar($id)
    {
        $this->noAuth();
        if($this->can("usuario.editar") && $this->can("usuario.index"))
        {
            $usuariomodel = new Usuario;
            $modelrole = new Role;
            $usurolemodel = new Usuario_Role;

            $usuario = $usuariomodel->query()->where("id_usuario", "=", $id)->get();
            /// mostramos todos los roles
            $rolesNotDelUsuario = $usuariomodel->procedure("proc_gestion_roles_user", "C", [$id, 'rna']);

            /// mostrar los roles del usuario que queremos editar

            $rolesDelUsuario = $usuariomodel->procedure("proc_gestion_roles_user", "C", [$id, 'ra']);

            View("users.editar", compact("usuario", "rolesNotDelUsuario", "rolesDelUsuario"));
        }else{
            View("pageserrors.no_authorizado"); 
        }
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

        $UploadFoto = $this->uploadFile("foto");

        /// consultamos al usuario
        $usuario = $modelUser->query()->where("id_usuario","=",$id)->get();

        if($UploadFoto === 'ok'){
           if($usuario[0]->foto != null){
            $Directorio = "fotos/".$usuario[0]->foto;
            unlink($Directorio);
           }
        }

        $response = $modelUser->update([
            "id_usuario" => $id,
            "name" => $this->post("name"),
            "email" => $this->post("email"),
            "estado" => $this->post("estado"),
            "foto" => $this->getNameArchivo()
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

    /**
     * Método visualizar la vista de crear nueva cuenta al usuario tipo cliente
     */
    public function ViewCreateAccount()
    {
       $this->Auth(); 

       View("users.create_account");
    }

    /**
     * Método de registro del usuario al sistema
     */
    public function savecreateAccount(){
        $this->Auth();

        if($this->VerifyTokenCsrf($this->post("token_"))){
            $this->procesoSaveCreateAccount();
        }else{
            $this->session("error","Token incorrecto!");
        }

        redirect("user/create-account");
    }

    /**
     * Proceso de registro del usuario tipo cliente
     */
    private function procesoSaveCreateAccount(){
       $modelUser = new Usuario;

        $Token = GenerateTokenOrCode();
        $CodeVerication = GenerateTokenOrCode("0123456789",1,6,"code");
        $TiempoExpired = time() + 60*5;/// 45 segundo
 
       $respuesta = $modelUser->create([
        "name" => $this->post("name"),
        "email" => $this->post("email"),
        "password" => password_hash($this->post("password"),PASSWORD_BCRYPT),
        "token_verified_email"=> $Token,
        "tiempo_expired" => $TiempoExpired,
        "codigo_verified" => $CodeVerication,
        "estado" => "i"
       ]);

       /// Verificamos si el usuario se registró correctamente.
       if($respuesta){
        /// obtenemos el id del usuario y id del rol
        $modelRole = new Role; $modelRolUser = new Usuario_Role;
        $usuario = $modelUser->query()->where("email","=",$this->post("email"))->get();
        $role = $modelRole->query()->where("nombre_rol","=","cliente")->get();

        $respuestaRoleUser = $modelRolUser->create([
            "id_usuario" => $usuario[0]->id_usuario,
            "id_rol" => $role[0]->id_rol
        ]);

        if($respuestaRoleUser){
                /// vamos enviarle un codigo de verificación al correo del usuario registrado
                $SendEmailUser = $this->sendEmail($usuario,"Activación de la cuenta","Su código de activación  es : <b>".$usuario[0]->codigo_verified."</b>");
                if($SendEmailUser){
                    $this->session("success_send_email",
                                   "Registro exitoso, le hemos enviado un correo electrónico con el código de activación!"
                                  );
                    /// redirigir a un formulario para ingresar el código de activación
                    redirect("user/activate/account?id=".$usuario[0]->id_usuario."&&token=".$usuario[0]->token_verified_email);
                    exit;
                }else{
                    $this->session("error",
                    "Error al registrar al usuario!"
                    );
                  /// eliminar al usuario
                  $modelUser->delete($usuario[0]->id_usuario);   
                }
        }else{
            $this->session("error",
                    "Error al registrar al usuario!"
            );
           /// eliminar al usuario
           $modelUser->delete($usuario[0]->id_usuario); 
        }
       }
    }

    /// Método para mostrar la vista de activación de la cuenta del usuario
    public function viewActiveAccount(){
        $this->Auth();

        if(!empty($this->get("id")) && !empty($this->get("token"))){
           $modelUser = new Usuario;
           $usuario = $modelUser->query()->where("id_usuario","=",$this->get("id"))
                     ->And("token_verified_email","=",$this->get("token"))
                     ->get();
            if($usuario && $usuario[0]->tiempo_expired > time()){
                View("users.activate_account"); 
            }else{
                $modelUser->delete($usuario[0]->id_usuario);
                redirect("login");  
            }
        }else{
            redirect("login");
        }
    }


    /// Activar la cuenta del usuario, cuándo ingresa el código
    public function ActivarCuentaUserCode($id){
        $this->Auth();
        if($this->VerifyTokenCsrf($this->post("token_"))){
            $modelUser = new Usuario;

            $usuario = $modelUser->query()->where("id_usuario","=",$id)
            ->get();

            /// verificamos si el usuario existe con ese código de activación
            if($usuario[0]->codigo_verified === $this->post("codigo")){
                $modelRole = new Role;
                $role = $modelRole->query()->where("nombre_rol","=","cliente")->get();
              /// actualizamos algunos datos del usuarios
              $respuesta = $modelUser->update([
                "token_verified_email" => null,
                "email_verified" => $this->FechaActual("Y-m-d H:i:s"),
                "tiempo_expired" => null,
                "codigo_verified" => null,
                "estado" => "h"
              ]);  

              // Hacer login
              $this->login([
                "rol" => $role[0]->id_rol,
                "login" => $usuario[0]->email
              ]);
            }else{
                $this->session("error_code","El código ingresado es incorrecto!");
                echo "<script>history.back()</script>";
            }
        }
    }

    /// Creamos un método para mostrar la vista de perfil del usuario
    public function profileView(){

        $this->noAuth();/// redirige al login
        View("users.profile");
    }

    /// Mostrar la vista de editar perfíl
    public function editarProfile(){
        $this->noAuth();

        View("users.profile_editar");
    }

    /// Actualizar perfil del usuario
    public function updateProfile()
    {
        $this->noAuth();

        if($this->VerifyTokenCsrf($this->post("token_"))){
            $modelUser = new Usuario;

            $UploadFoto = $this->uploadFile("foto");
            $usuario = $modelUser->query()->where("id_usuario","=",$this->getSession("user"))->get();
    
    
            if($UploadFoto === 'ok')
            {
                if($usuario[0]->foto != null){
                    $Directorio = "fotos/".$usuario[0]->foto;
                    unlink($Directorio);
                }
            }
    
            $response = $modelUser->update([
                "id_usuario" => $this->getSession("user"),
                "name" => $this->post("name"),
                "email" => $this->post("email"),
                "foto" => $this->getNameArchivo()
            ]);

            if($response){
                $this->session("success","Tus datos han sido modificados correcatamente!");
            }else{
                $this->session("error","Error al actualizar tus datos!");
            }
     
        }else{
            $this->session("error","token-invalid!");
        }
        redirect("profile/editar");
    }

    /**
     * Método valida la contraseña actual
     */
    public function validatePasswordActual($password_actual){
           $this->noAuth();

            $modelUser = new Usuario;
 
            /** Obtenemos al id de l usuario acode sea necesario */
            if($this->existSession("user")){
               $userId = $this->getSession("user");
            }else{
                $userId = openssl_decrypt($_COOKIE["user"],"aes-128-cbc","curso"); 
            }

            $usuario = $modelUser->query()->where("id_usuario","=",$userId)->get();
            if($usuario && password_verify($password_actual,$usuario[0]->password)){
                json(["response" => "ok"]);
            }else{
                json(["response" => "no"]);
            }
    }

    /**Método para guardar la contraseña nueva del usuario */
    public function updatePasswordUser(){
        $this->noAuth();

        if($this->VerifyTokenCsrf($this->post("token_"))){
            $modelUser = new Usuario;
            /** Obtenemos al id de l usuario acode sea necesario */
            if($this->existSession("user")){
                $userId = $this->getSession("user");
             }else{
                $userId = openssl_decrypt($_COOKIE["user"],"aes-128-cbc","curso"); 
            }

            $response = $modelUser->update([
                "id_usuario" => $userId,
                "password" => password_hash($this->post("password_nuevo"),PASSWORD_BCRYPT)
            ]);

            json(["response" => $response ? 'ok' :'error']);
        }else{
            json(["response" => "error-token"]);
        }
    }

}