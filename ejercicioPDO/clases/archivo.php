<?php
/**
 * Los JSON se tratan guardan siempre como array para tratar de mantener el formato
 * Los CSV se tratan por linea ya que los guardo como string. 
 * SIEMPRE que leo retorno un array de objetos stdClass para poder tratar todo ok. 
 */
class Archivo
{
    static public function Guardar($path,$objeto,$formato)//agrega UN objeto
    {
        $retorno = false;
        switch ($formato) {
            case 'json':
                $array = Archivo::Leer($path,'json');
                array_push($array,$objeto);
                $archivo = fopen($path,"w");
                fwrite($archivo,json_encode($array));
                fclose($archivo);
                break;
            case 'csv':
                $archivo = fopen($path,'a+');
                fwrite($archivo,$objeto);
                fclose($archivo);
                $retorno = true;
                break;
            default:
                break;
        }
        return $retorno;
    }
    static public function Leer($path,$formato)
    {
        $array = array();
        $elemento = "";
        if(file_exists($path))
        {
            switch ($formato) {
                case 'csv':
                    $archivo = fopen($path,'r');
                    while(!feof($archivo))
                    {
                        $elemento = fgetcsv($archivo);//esto ya es un array
                        if($elemento != false)
                        {
                            array_push($array,$elemento);//retorna una array. hay parche en clase
                        }
                    }
                    fclose($archivo);
                    break;

                case 'json':
                    $archivo = fopen($path,'r');
                    while(!feof($archivo))
                    {
                        $array = json_decode(fgets($archivo));
                    }
                    if(is_null($array))
                    {
                        $array = array();
                    }

                default:
                    break;
            }
        }
        return $array;
    }
    static function guardarImagen($file,$destino,$nombre)
    {
        $retorno = false;
        $extencion = explode('.',$file['name'])[1];
        $destino = $destino.$nombre.'.'.$extencion;
        if(!file_exists($destino))
            {
                $tipoArchivo = pathinfo(PATHINFO_EXTENSION);
                if($tipoArchivo == "jpg" || $tipoArchivo == "jpeg" || $tipoArchivo != "png") 
                {
                    if(getimagesize($file['tmp_name']))
                    {
                        if (move_uploaded_file($file["tmp_name"], $destino)) {
                            $retorno = true;
                        } 
                    }else{
                        echo 'es grande';
                    }
                }else{
                    echo 'no es imagen';
                }
            }else{
                echo 'existe destino';
            }
            return $retorno;
    }
    static function Update($path,$objetos,$formato)//pisa toda la lista con el array nuevo.
    {
        $retorno = false;
        switch ($formato) {
            case 'json':
                $archivo = fopen($path,"w");
                fwrite($archivo,json_encode($objetos));
                fclose($archivo);
                $retorno = true;
                break;
            case 'csv':
                $archivo = fopen($path,'w');
                foreach ($objetos as $obj ) {
                    fwrite($archivo,$obj);
                }
                fclose($archivo);
                $retorno = true;
                break;
            default:
                break;
        }
        return $retorno;
    }
}