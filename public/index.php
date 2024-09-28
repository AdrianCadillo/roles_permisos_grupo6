<?php

$path = explode("\\",__DIR__);

$path = $path[0]."\\".$path[1]."\\".$path[2]."\\".$path[3];
use Dotenv\Dotenv;
 
require '../vendor/autoload.php';
require 'autoload.php';


$dotnen = Dotenv::createImmutable($path);
$dotnen->load();
require '../app/Http/lib/publicfunction.php';
require '../log/log.php';
require '../config/app.php';
 
require '../router/web.php';
$router->run();