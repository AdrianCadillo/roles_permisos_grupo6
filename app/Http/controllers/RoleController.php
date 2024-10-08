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

       $roles = $modelrole->query()
       ->where("deleted_at","is",null)
       ->get();
       
        
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

    /**RETORNE LOS PERMISOS QUE TIENE ASIGNADO UN ROL */
    public function permisosDelRol($id)
    {
      $model_role_permiso = new Role_Permiso;

      $data = $model_role_permiso->query()->Join("roles as r","rp.id_rol","=","r.id_rol")
                                 ->Join("permisos as p","rp.id_permiso","=","p.id_permiso")
                                 ->where("rp.id_rol","=",$id)
                                 ->select("rp.id_permiso","p.nombre_permiso")
                                 ->get();

      json(["permisosrol" => $data]);                           
    }

    /**RETORNA LOS PERMISOS QUE AUN NO AN SIDO ASIGNADO A UN ROL */
    public function permisos_no_asignados_al_rol($id)
    {
     $modelrole = new Role_Permiso;

     $data = $modelrole->procedure("show_not_permisos_role","C",[$id]);

     json(["response" => $data]);
    }

    /**
     * Guardar nuevos roles
     */
    public function store()
    {
      if($this->VerifyTokenCsrf($this->post("token_"))){
         $modelRole = new Role;

         $response = $modelRole->create([
          "nombre_rol" => $this->post("namerol")
         ]);

         json(["response" => $response ? 'ok':'error']);
      }else{
        json(["response" => "token-invalid"]);
      }
    }

    /**Método para asignar permisos a los roles */
    public function asignRolePermission()
    {
      if($this->VerifyTokenCsrf($this->post("token_"))){
        $modelRolePermission = new Role_Permiso; $modelrole = new Role;

        /// consultamos al rol que estamos creando
        $rol = $modelrole->query()->where("nombre_rol","=",$this->post("namerol"))->get();

        $response = $modelRolePermission->create([
          "id_rol" => $rol[0]->id_rol,
          "id_permiso" => $this->post("permiso")
        ]);

        json(["response" => $response ? 'ok':'error']);
      }  
    }

    /**
     * Guardar los cambios
     */
    public function update($id){
      if($this->VerifyTokenCsrf($this->post("token_"))){
        $modelrole = new Role;

        $response = $modelrole->update([
          "id_rol" => $id,
          "nombre_rol" => $this->post("namerol")
        ]);

        if($response){
          $modelrole->procedure("proc_gestion_roles_user","C",[$id,"drp"]);
        }
      }
    }


    /**
     * Método para eliminar un rol
     */
    public function eliminar($id){
      if($this->VerifyTokenCsrf($this->post("token_"))){
        $modelrole = new Role;

        $response = $modelrole->update([
          "id_rol" => $id,
          "deleted_at" => $this->FechaActual("Y-m-d H:i:s")
        ]);

        json(["response" => $response ? 'ok':'error']);
      }  
    }
}