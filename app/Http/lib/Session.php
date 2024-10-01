<?php
namespace app\Http\lib;

trait Session{

    /**
     * Asignar a una variable de session un valor
     */
    public function session(string $NameSession,$Value){
        $_SESSION[$NameSession] = $Value;
    }

    /**
     * REtORNAR LA VARIABLE DE SESSION
     */
    public function getSession(string $NameSession){
        return isset($_SESSION[$NameSession]) ? $_SESSION[$NameSession]:'';
    }

    /**
     * Verificar la existencia de una variable de session
     */
    public function existSession(string $NameSession):bool{
      return isset($_SESSION[$NameSession]);
    }

    /**
     * Eliminar una variable de session en especifico
     */
    public function destroySession(String $NameSession){
       unset($_SESSION[$NameSession]);
    }
}