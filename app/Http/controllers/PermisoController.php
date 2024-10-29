<?php
namespace app\Http\controllers;

use app\Http\lib\Controller;
use app\models\Permiso;

class PermisoController extends Controller{

    private array $Errors = [];
    /**
     * Método que muestra la vista de permisos
     */
    public function index()
    {
       $this->noAuth();
       if($this->can("permiso.index"))
       {
        // mostramos todos los permisos
        $modelPermiso = new Permiso;

        $permisos = $modelPermiso->query()->get();

        View("permission.index",compact("permisos")); 
       }else{
        View("pageserrors.no_authorizado"); 
       }
    }

    /**
     * Método que visualiza la página de crear nuevos permisos
     */
    public function create(){
        $this->noAuth();
        
            View("permission.create");
         
    }

    /**
     * Método para registrar un nuevo permiso
     */
    public function store()
    {
        if($this->VerifyTokenCsrf($this->post("token_")))
        {
          if(empty($this->post("name_permiso"))){
            $this->Errors [] = "Ingrese el nombre del permiso!";
          }

          if(empty($this->post("alias_permiso"))){
            $this->Errors [] = "Ingrese su alias del permiso!";
          }

          if(count($this->Errors) > 0){
            $this->session("errors",$this->Errors);
            redirect("permiso/create");
          }else{
            $this->procesoRegistroPermiso();
          }

        }else{
            $this->session("error_token","Token invalidate!");
            redirect("permiso/create");
        }
    }

    private function procesoRegistroPermiso()
    {
        $modelpermiso = new Permiso;

        $modelpermiso->nombre_permiso = $this->post("name_permiso");

        $modelpermiso->alias_permiso = $this->post("alias_permiso");

        $response = $modelpermiso->save();

        if ($response) {
            $this->session("success", "Permiso creado correctamente!");
            redirect("permisos");
        } else {
            $this->session("error", "Error al registrar permiso!");
            redirect("permiso/create");
        }
    }

}