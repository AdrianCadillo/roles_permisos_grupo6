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


    /**
     * Método save para registrar
     */
    public function save();

    /**
     * Método para registrar de otra forma
     */
    public function create(array $datos=[]);

    /**
     * Método para actualizar datos
     */
    public function update(array $datos);

    /**
     * Para eliminar
     */
    public function delete($id);


    /**
     * Método Join para consultas de dos oh más tablas
     */
    public function Join(string $TablaFk,string $columnaPk,string $operador,string $columnaFK);


    public function procedure(string $NameProcedure,$evento,array $datos=[]);
}