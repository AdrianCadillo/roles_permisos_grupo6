<?php
namespace app\Http\lib;

trait Csrf{
    use Session;
    private string $NameToken = "_token";
  /**
   * Creamos un método que retorne el token
   */
  public function Csrf(){
    $Token = bin2hex(random_bytes(32));

    if(!$this->existSession($this->NameToken)){
      $this->session($this->NameToken,$Token);
    }

    return $this->getSession($this->NameToken);
  }

  /**
   * Validación del token Csrf
   */

   public function VerifyTokenCsrf(string|null $tokenInput):bool
   {
     if(isset($tokenInput))
     {
         if($this->ExistSession($this->NameToken) and $this->getSession($this->NameToken) === $tokenInput)
         {
           return true;
         }
     
         return false;
     }
     return false;
   }
}