<?php

use Bramus\Router\Router;

$router = new Router;

$router->get("/users",'UserController@index');

$router->post("/users/send",'UserController@store');

$router->get("/user/create",function(){
    echo "CREANDO USUARIOS!"; 
});