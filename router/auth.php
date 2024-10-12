<?php
if(PHP_SESSION_ACTIVE != session_status()){
    session_start();  
}

/**
 * Definir las rutas
 */
$router->get("/login","AuthController@ViewLogin");
$router->post("/login","AuthController@login");
$router->post("/logout","AuthController@logout");