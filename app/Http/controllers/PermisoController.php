<?php
namespace app\Http\controllers;

use app\Http\lib\Controller;

class PermisoController extends Controller{

    /**
     * Método que muestra la vista de permisos
     */
    public function index()
    {
       $this->noAuth();
       View("permission.index"); 
    }

    /**
     * Método que visualiza la página de crear nuevos permisos
     */
    public function create(){
        $this->noAuth();
        View("permission.create");
    }

}