<?php 
namespace app\Http\lib;

trait Fecha
{
    /**
     * Retorna la fecha actual
     */
    public function FechaActual(string $formato)
    {
        date_default_timezone_set(ZONA_HORARIA);

        return date($formato);
    }
}