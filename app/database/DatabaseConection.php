<?php
namespace app\database;

use PDO;

trait DatabaseConection
{
    public $Query;
    public $pps;
    private $conection = null;
    /**
     * Método para conectar a la base de datos
     */

   public function getConectionDatabase(){
     try {
         $this->conection = new PDO(
            "mysql:host=localhost;dbname=roles_permisos",
            "root",
            ""
         );

         $this->conection->exec("set names utf8");

         return $this->conection;
     } catch (\Throwable $th) {
        echo "<h2 style='color:red'>".$th->getMessage()."</h2>";
        exit;
     }
   }  

   /**
    * Cerramos la conexión abierta 
    */
    public function closeConection(){
        if($this->conection != null)
        {
            $this->conection = null;
        }

        if($this->pps != null)
        {
            $this->pps = null;
        }
    }
}