<?php 
namespace app\Http\lib;

use app\models\Usuario_Role;

trait MiddlewareAuth{

    private String $RedirectNoAuthenticate = "login";

    private String $RedirectAuthenticate = "home";
    use Session;

 /**
  * Método que redirige al login si el usuario no está authenticado
  */

  public function noAuth(){
    if(count($this->user()) > 0){
      if(!$this->existSession($this->SessionUser) && !isset($_COOKIE[$this->SessionUser])){
        redirect($this->RedirectNoAuthenticate);
        exit;
      }
    }else{
    if(!$this->existSession($this->SessionUser))
    {
      setcookie($this->SessionUser,"",time()-10,"/");
      setcookie($this->SessionProfile,"",time()-10,"/");
    }
     
    $this->destroySession($this->SessionUser);
    $this->destroySession($this->SessionProfile);
      redirect($this->RedirectNoAuthenticate);
      exit;
    }
  }

  /**
   * Método redirige al Dashboard cuándo el usuario está authenticado
   */
  public function Auth(){
   if($this->existSession($this->SessionUser) || isset($_COOKIE[$this->SessionUser]))
    {
     redirect($this->RedirectAuthenticate);
     exit;
    }
  }

  /// Mostrar al usuario authenticado
  public function user(){
    $credenciales = [
      "user" => null,
      "role" => null
    ];

     if($this->existSession($this->SessionUser)){
      $credenciales["user"] = $this->getSession($this->SessionUser);
      $credenciales["role"] = $this->getSession($this->SessionProfile);
     }else{
      $credenciales["user"] = openssl_decrypt($_COOKIE[$this->SessionUser],"aes-128-cbc","curso");
      $credenciales["role"] = openssl_decrypt($_COOKIE[$this->SessionProfile],"aes-128-cbc","curso");
     }

      /// realizamos la consulta del usuarios con las credenciales
      $modeluser = new Usuario_Role;
      /// obtenemos al usuario
      return $modeluser->query()->Join("usuarios as u","ur.id_usuario","=","u.id_usuario")
                           ->Join("roles as r","ur.id_rol","=","r.id_rol")
                           ->where("ur.id_rol","=",$credenciales["role"])
                           ->And("u.id_usuario","=",$credenciales["user"])
                           ->get();
  }


}