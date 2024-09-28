<?php

use Bramus\Router\Router;

if(PHP_SESSION_ACTIVE != session_status()){
  session_start();  
}

$router = new Router;

$router->get("/users",'UserController@index');

$router->post("/user/store",'UserController@store');

$router->get("/user/create",'UserController@create');

$router->get("/",function(){
  echo "LA PÁGINA DE INCIO";
});