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

    /**
     * Mètodo para obtener el nombre del archivo
     */
    public function getNameFile(string $nameFile)
    {
         return $_FILES[$nameFile]["name"];
    }

    /**
     * Método para obtener el tipo de archivo
     */
    public function getTypeFile(string $nameFile)
    {
        return $_FILES[$nameFile]["type"];
    }

    /**
     * Método para obtener el tamaño del archivo
     */
    public function getSizeFile(string $nameFile)
    {
        return $_FILES[$nameFile]["size"];
    }

    /**
     * Método para obtener el contenido del archivo
     */
    public function getContentFile(string $nameFile){
        return $_FILES[$nameFile]["tmp_name"];
    }
}