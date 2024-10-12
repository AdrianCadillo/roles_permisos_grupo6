<?php 
namespace app\Http\lib;

use app\models\Usuario_Role;

trait Auth{
   use Session;
   
   /** Método que realiza la authenticación */
   public function attemp(array $credenciales=[],bool $remember = false)
   {
    /// realizamos la consulta del usuarios con las credenciales
    $modeluser = new Usuario_Role;
    /// obtenemos al usuario
    $usuario = $modeluser->query()->Join("usuarios as u","ur.id_usuario","=","u.id_usuario")
                         ->Join("roles as r","ur.id_rol","=","r.id_rol")
                         ->where("ur.id_rol","=",$credenciales["rol"])
                         ->And("u.name","=",$credenciales["login"])
                         ->Or("u.email","=",$credenciales["login"])
                         ->get();
    /// Verficar si el usuario existe y a la ves si conciden lo que escribe el usuario 

    if($usuario && ($credenciales["login"] === $usuario[0]->name || $credenciales["login"] === $usuario[0]->email)){
        /// Verificar la constraseña que escribe el usuario
        if(password_verify($credenciales["password"],$usuario[0]->password)){
            /// vamos a verificar si el usuario dio recordar la sesion oh no
            $this->RememberMe($remember,$usuario[0]->id_usuario,$usuario[0]->id_rol);
            /// redirigir
            redirect("users");
            exit;
        }else{
            $this->session("error","La contraseña ingresado es incorrecto!");   
        }
    }else{
        $this->session("error","El perfíl seleccionado oh nombre de usuario son incrrectos!");
    }
    redirect("login");
   }

   /// Método va realizar el proceso de recordar la sesión
   private function RememberMe(bool $remember,$DataUser,$DataPerfil){
      if($remember)
      {
        setcookie($this->SessionUser,
                  openssl_encrypt($DataUser,"aes-128-cbc","curso"),
                  time()+40,
                  "/"
                 );

         setcookie($this->SessionProfile,
         openssl_encrypt($DataPerfil,"aes-128-cbc","curso"),
         time()+40,
         "/"
        );    
      }else{
        $this->session($this->SessionUser,$DataUser);
        $this->session($this->SessionProfile,$DataPerfil);
      }
   }

   /**
   * Realizar el proceso de cerrar la sesión
   */
  public function CerrarSesion(){
    if(!$this->existSession($this->SessionUser))
    {
      setcookie($this->SessionUser,"",time()-10,"/");
      setcookie($this->SessionProfile,"",time()-10,"/");
    }
     
    $this->destroySession($this->SessionUser);
    $this->destroySession($this->SessionProfile);
    $this->session("close_success","Acabas de cerrar la sesión!");
    redirect("login");

  }
}