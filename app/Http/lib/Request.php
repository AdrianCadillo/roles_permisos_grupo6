<?php
namespace app\Http\lib;

trait Request
{
    /**
     * Método post
     */
    public function post(string $inputName)
    {
        if(isset($_POST[$inputName]))
        {
            return $_POST[$inputName];
        }

        return '';
    }

    /**
     * Método get
     */
    public function get(string $inputName)
    {
        if(isset($_GET[$inputName]))
        {
            return $_GET[$inputName];
        }

        return '';
    }

    /**
     * Método REQUEST => POST and GET
     */
    public function request(string $inputName)
    {
        if(isset($_REQUEST[$inputName]))
        {
            return $_REQUEST[$inputName];
        }

        return '';
    }
}