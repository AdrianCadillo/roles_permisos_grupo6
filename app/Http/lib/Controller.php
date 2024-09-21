<?php 
namespace app\Http\lib;

class Controller 
{ 
    use Request;
    /**
     * 
     */
    public function saludar()
    {
        return "HOLA MUNDO!!";
    }
}