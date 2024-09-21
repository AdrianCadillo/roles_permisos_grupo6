<?php
namespace app\orm;

interface Orm{
   
    /**
     * Método para inicializar la consulta
     */
    public function query();

    /**
     * Para seleccionar  ciertas columnas de la tabla
     */
    public function select();

    /**
     * Método get para mostrar los datos
     */
    public function get();

    /**
     * Condicion where
     */
    public function where(string $columna,string $operador,mixed $value);

    /**
     * Condicion And
     */
    public function And(string $columna,string $operador,mixed $value);

    /**
     * Condicion Or
     */
    public function Or(string $columna,string $operador,mixed $value);

}