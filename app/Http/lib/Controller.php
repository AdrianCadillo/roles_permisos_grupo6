<?php 
namespace app\Http\lib;

class Controller 
{ 
    use Request,Csrf;
    /**
     * 
     */
    public function saludar()
    {
        return "HOLA MUNDO!!";
    }
}