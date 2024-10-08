<?php

use Bramus\Router\Router;

if(PHP_SESSION_ACTIVE != session_status()){
  session_start();  
}

$router = new Router;

$router->get("/users",'UserController@index');

$router->post("/user/store",'UserController@store');

$router->get("/user/create",'UserController@create');

$router->post("/user/{id}/delete",'UserController@delete');

$router->get("/user/{id}/editar",'UserController@editar');

$router->post("/user/{id}/update",'UserController@update');

$router->get("/roles","RoleController@index");

$router->get("/role/permisos","RoleController@mostrarrolesPermisos");

$router->get("/permisos-existentes","RoleController@mostrarPermisos");

$router->post("/role/save","RoleController@store");

$router->post("/role/asignar/permisos","RoleController@asignRolePermission");

$router->get("/role/permissions/{id}","RoleController@permisosDelRol");

$router->get("/role/permisos-no-asignados/{id}","RoleController@permisos_no_asignados_al_rol");

$router->post("/role/permissions/update/{id}","RoleController@update");

$router->post("/role/{id}/delete","RoleController@eliminar");
 
$router->get("/",function(){
  echo "LA PÁGINA DE INCIO";
});