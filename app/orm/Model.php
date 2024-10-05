<?php
namespace app\orm;

use app\database\Conexion;

class Model extends Conexion implements Orm 
{
  use Save;
    protected $table='';

    private array $ValuesWhere = [];
    protected $primaryKey = "id";
    protected $Alias ="";
      /**
     * Método para inicializar la consulta
     */
    public function query(){
       if(empty($this->table)){
          $this->table = $this->getNameClass();
       }
      $this->Query = "SELECT * FROM ".$this->table.$this->Alias;
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
       
        if(count($this->ValuesWhere) >= 1){
            for($i = 0;$i<count($this->ValuesWhere);$i++){
                $this->pps->bindValue(($i+1),$this->ValuesWhere[$i]);
            }
        } 

        $this->pps->execute();

        return $this->pps->fetchAll(\PDO::FETCH_OBJ);
      } catch (\Throwable $th) {
        echo "<h1>".$th->getMessage()."</h1>";
        exit;
      }finally{
        $this->closeConection();
        $this->ValuesWhere =[];
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
     * Método save para registrar
     * INSERT INTO tabla(atributo1,atributo2,atributo3)
     * VALUES(:atributo,:atributo2,:atributo3)
     * INSERT INTO tabla(atributo1,atributo2,atributo3)
     * VALUES(?,?,?)
     * 
     */
    public function save()
    {
      if (empty($this->table)) {
          $this->table = $this->getNameClass();
      }
       $this->Query = "INSERT INTO ".$this->table."(";

       foreach($this->getProperties() as $key=>$value):
            $this->Query.="$key,";
       endforeach;

       $this->Query = rtrim($this->Query,",").") VALUES(";

       foreach($this->getProperties() as $key=>$value):
         $this->Query.=":$key,";
       endforeach;

      $this->Query = rtrim($this->Query,",").")";

      return $this->executeQuery($this->Query,$this->getProperties());
    }


    private function executeQuery($Sql,array $datos=[]){
    try {
      $this->pps = $this->getConectionDatabase()->prepare($Sql);

      foreach ($datos as $key => $value):
        $this->pps->bindValue(":$key", $value);
      endforeach;

      return $this->pps->execute();
    } catch (\Throwable $th) {
      echo "<h1>" . $th->getMessage() . "</h1>";
      exit;
    } finally {
      $this->closeConection();
    }
    }

    /**
     * Método para registrar de otra forma
     */
    public function create(array $datos=[]){
    if (empty($this->table)) {
      $this->table = $this->getNameClass();
    }
    $this->Query = "INSERT INTO " . $this->table . "(";

    foreach ($datos as $key => $value):
      $this->Query .= "$key,";
    endforeach;

    $this->Query = rtrim($this->Query, ",") . ") VALUES(";

    foreach ($datos as $key => $value):
      $this->Query .= ":$key,";
    endforeach;

    $this->Query = rtrim($this->Query, ",") . ")";

     return $this->executeQuery($this->Query,$datos);
    }

     /**
     * Método para actualizar datos
     * update tabla set atributo1=:atributo1,atributo2=:atributo2
     * where id_tabla=:id_tabla
     */
    public function update(array $datos)
    {
      if (empty($this->table)) {
        $this->table = $this->getNameClass();
      }
      $this->Query = "UPDATE ".$this->table." SET ";
      foreach ($datos as $key => $value):
       $this->Query .= "$key=:$key,";
      endforeach;
     $this->Query = rtrim($this->Query, ",")." WHERE ".array_key_first($datos)."=:".array_key_first($datos);

     return $this->executeQuery($this->Query,$datos);
    }


      /**
     * Para eliminar
     */
    public function delete($id){
      if (empty($this->table)) {
        $this->table = $this->getNameClass();
      }
      $this->Query = "DELETE FROM ".$this->table." WHERE ".$this->primaryKey."=:".$this->primaryKey;

      try {
         $this->pps = $this->getConectionDatabase()->prepare($this->Query);
         $this->pps->bindValue(":".$this->primaryKey,$id);

         return $this->pps->execute();
      } catch (\Throwable $th) {
        echo "<h1>" . $th->getMessage() . "</h1>";
        exit; 
      }finally{
         $this->closeConection();
      }
    }

    /**
     * Método Join para consultas de dos oh más tablas
     */
    public function Join(string $TablaFk,string $columnaPk,string $operador,string $columnaFK)
    {
      $this->Query.= " INNER JOIN $TablaFk ON $columnaPk $operador $columnaFK";
      return $this;
    }
    /**
     * Para convertir la clase del modelo en una tabla
     */
    private function getNameClass(){
       return  strtolower(explode("\\",get_class($this))[2])."s";
    }
/// procedimiento almacenado para realizar [CRUD COMPLETO]
public function procedure(string $NameProcedure,$evento,array $datos=[])
{
  $this->Query = "CALL $NameProcedure(";

  foreach($datos as $value)
  {
     $this->Query.="?,";
  }

  $this->Query = rtrim($this->Query,",").")";

  try {
     $this->pps = $this->getConectionDatabase()->prepare($this->Query);

     for ($i=0; $i <count($datos) ; $i++) { 
        
        $this->pps->bindValue(($i+1),$datos[$i]);
     }
     if(strtoupper($evento) ==='C')
     {
        $this->pps->execute();

        return $this->pps->fetchAll(\PDO::FETCH_OBJ);
     }

     return $this->pps->execute();

  } catch (\Throwable $th) {
     echo $th->getMessage();
     exit;
  }finally{$this->closeConection();}
}
   
}