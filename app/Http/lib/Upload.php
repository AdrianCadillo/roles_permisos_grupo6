<?php
namespace app\Http\lib;

trait Upload {

    use Request,Fecha;
    private string|null $NameArchivo=null;
    private string $DirectorioDestino =  "fotos/";
    private array $Tipo = ["jpg","png"];

    public function uploadFile(String $NameArchivo_):String{
     
        /// Validar si tenemos archivo seleccionado oh no
        if($this->getSizeFile($NameArchivo_) > 0){
            /// obtener el nombre del archivo
            $NameArchivoSeleccionado = $this->getNameFile($NameArchivo_);

            $TipoArchivo = explode(".",$NameArchivoSeleccionado)[1];

            /// verificamos el tipo de archivo que va aceptar la aplicación
            if(in_array($TipoArchivo,$this->Tipo)){
                /// obtener el nombre nuevo de la imagen
                $this->NameArchivo = $this->FechaActual("YmdHis").".".$TipoArchivo;
                /// verificamos si existe el directorio fotos
                if(!file_exists($this->DirectorioDestino)){
                    mkdir($this->DirectorioDestino);
                }

                /// vamos a juntar la carpeta foto con el el nuevo nombre de la imagen
                $this->DirectorioDestino.=$this->NameArchivo;

                /// Contenido del archivo
                $ContentFile = $this->getContentFile($NameArchivo_);

                /// subir el archivo al servidor
                if(move_uploaded_file($ContentFile,$this->DirectorioDestino)){
                    return 'ok';
                }else{
                    return 'error';
                }

            }
            else{
                return "no-accept";
            }
        }else{
            return 'vacio';
        }
    }

    /// retornamos el nombre del archivo
    public function getNameArchivo(){
        return $this->NameArchivo;
    }

}