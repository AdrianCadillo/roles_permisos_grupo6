<?php
namespace app\orm;

use app\database\Conexion;

class Model extends Conexion implements Orm 
{
    protected $table='';

    private array $ValuesWhere = [];
      /**
     * Método para inicializar la consulta
     */
    public function query(){
       if(empty($this->table)){
          $this->table = $this->getNameClass();
       }
      $this->Query = "SELECT * FROM ".$this->table;
      return $this;
    }

    /**
     * Para seleccionar  ciertas columnas de la tabla
     */
    public function select(){
        $columnas = func_get_args(); //[a,a,b]
        $columnas = implode(",",$columnas); /// a,a,b

        $this->Query = str_replace("*",$columnas,$this->Query);

        return $this;
    }
     /**
     * Método get para mostrar los datos
     */
    public function get(){
      try {
        $this->pps = $this->getConectionDatabase()->prepare($this->Query);
       
        if(count($this->ValuesWhere) > 1){
            for($i = 0;$i<count($this->ValuesWhere);$i++){
                $this->pps->bindValue(($i+1),$this->ValuesWhere[$i]);
            }
        }else{
            $this->pps->bindValue(1,$this->ValuesWhere[0]); 
        }

        $this->pps->execute();

        return $this->pps->fetchAll(\PDO::FETCH_OBJ);
      } catch (\Throwable $th) {
        echo "<h1>".$th->getMessage()."</h1>";
      }finally{
        $this->closeConection();
      }
    }

     /**
     * Condicion where
     */
    public function where(string $columna,string $operador,mixed $value)
    {
        $this->Query.= " WHERE $columna $operador ?";

        $this->ValuesWhere[] = $value;

        return $this;
    }

    /**
     * Condicion where and
     */
    public function And(string $columna,string $operador,mixed $value)
    {
        $this->Query.= " AND $columna $operador ?";

        $this->ValuesWhere[] = $value;

        return $this;
    }

    /**
     * Condicion where or
     */
    public function Or(string $columna,string $operador,mixed $value)
    {
        $this->Query.= " OR $columna $operador ?";

        $this->ValuesWhere[] = $value;

        return $this;
    }
    /**
     * Para convertir la clase del modelo en una tabla
     */
    private function getNameClass(){
       return  strtolower(explode("\\",get_class($this))[2])."s";
    }

   
}