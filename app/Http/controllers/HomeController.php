<?php 
namespace app\Http\controllers;

use app\Http\lib\Controller;

class HomeController extends Controller
{
    /**
     * Método que visualiza la parte principal del sistema
     */
    public function index(){
        $this->noAuth();

        View("home");
    }
}