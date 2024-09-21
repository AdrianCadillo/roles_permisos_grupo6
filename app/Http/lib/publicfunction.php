<?php

use Windwalker\Edge\Edge;
use Windwalker\Edge\Loader\EdgeFileLoader;

/**
 * Método para visualizar las vistas
 */
 function View(string $vista,array $data = [])
 {
    $DirectorioVistas = "../".str_replace(".","/",DIRECTORIO_VISTA.$vista).".blade.php";

    /// verificar la existencia del archivo de la vista

    if(file_exists($DirectorioVistas))
    {
        $bladeView = new Edge(new EdgeFileLoader());

        echo $bladeView->render($DirectorioVistas,$data);
    }else{
        echo "Error 404";
    }
 }