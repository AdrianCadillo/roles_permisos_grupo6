<?php
namespace app\Http\controllers;

use app\Http\lib\Controller;
use app\models\Permiso;
use app\models\Role;
use app\models\Role_Permiso;

class RoleController extends Controller
{
    /**
     * Método para mostrar la vista de roles
     */
    public function index()
    {
        View("role.index");
    }

    /**
     * mostrar los roles con sus permisos
     */

    public function mostrarrolesPermisos(){

       /// mostrar todos los roles
       $json = '';
       $modelrole = new Role;$modelrolepermission = new Role_Permiso;

       $roles = $modelrole->query()->get();
       
        
       foreach($roles as $role)
       {
          $PermisosRole = $modelrolepermission->query()->Join("roles as r","rp.id_rol","=","r.id_rol")
                                              ->Join("permisos as p","rp.id_permiso","=","p.id_permiso")
                                              ->select("p.id_permiso","p.nombre_permiso")
                                              ->where("rp.id_rol","=",$role->id_rol)
                                              ->get();
          $json.='{
            "id_rol":"'.$role->id_rol.'",
            "nombre_rol":"'.$role->nombre_rol.'",
            "permissions":[';
              foreach($PermisosRole as $permiso){
                $json.='{
                   "id_permiso":"'.$permiso->id_permiso.'",
                   "nombre_permiso":"'.$permiso->nombre_permiso.'"
                },';
              }
          $json = rtrim($json,",");
          $json.=']},';  
       }

       $json = rtrim($json,",");
       echo '{"roles":['.$json.']}';
    }

    /**
     * Mostramos todos los permisos existentes
     */
    public function mostrarPermisos()
    {
        $modelPermiso = new Permiso;

        $permisos = $modelPermiso->query()->get();

        json(["permisos"=>$permisos]);
    }
}