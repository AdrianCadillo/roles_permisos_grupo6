<?php
namespace app\orm;

trait Save {

    private array $properties = [];
    public function __set($atributo,$valor){
        if(!in_array($atributo,$this->properties)){
          $this->properties[$atributo] = $valor;
        }
      }
  
      public function __get($atributo){
          return $this->properties[$atributo];
      }


      public function getProperties(){
        return $this->properties;
      }

}