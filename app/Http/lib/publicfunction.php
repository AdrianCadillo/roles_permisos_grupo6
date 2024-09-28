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

 /**
  * Método asset
  */

  function assets(string $recursos){
    
    return URL_BASE.DIRECTORIO_RECURSOS.$recursos;
  }

  function env(string $NameVariableEntorno,mixed $ValueVariableDefault= null){
     if(isset($_ENV[$NameVariableEntorno])):
         return $_ENV[$NameVariableEntorno];
     endif;

     return $ValueVariableDefault;
  }

  /**
   * Método route
 */
  function route(string $endPoint){
      return URL_BASE.$endPoint;
  }

  function layouts(string $layout){
     return "../".str_replace(".","/",DIRECTORIO_LAYOUTS.$layout).".blade.php";
  }