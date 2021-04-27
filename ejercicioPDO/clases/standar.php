<?php
class Standar{
    public static $archivo;
    public static $formato;

    static function Name(){
        return get_called_class();
    } 
    public function __get($name) {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        return  $this->$name = $value;
    }
    static function Add($path,$objeto, $formato,$clase)
    {
        $retorno = false;
        switch ($formato) {
            case 'json':
                $retorno = Standar::AddJson($path,$objeto,$clase);
                break;
            case 'csv':
                $retorno = Standar::AddCSV($objeto,$path,$clase);
                break;
            default:
                //string
                break;
        }
        return $retorno;
    }
    private static function AddCSV($objeto,$path,$clase)
    {
        $retorno= false;
        if($clase::toCSV($objeto) != null && !Standar::Exist($objeto,$clase,'csv',$path))
        {
            $csv = $clase::toCSV($objeto);
            Archivo::Guardar($path,$csv,'csv');
            $retorno = true;
        }
        return $retorno;
    }
    private static function AddJson($path,$objeto,$clase)
    {
        $retorno = false;
        $standar = $clase::toStandar($objeto);
        if(!$clase::Exist($objeto,$clase,'json',$path))
        {
            Archivo::Guardar($path,$standar,'json');
            $retorno = true;
        }
        return $retorno;
    }
    static function Exist($obj,$clase,$formato,$path)//ver 
    {
        $retorno = false;
        $objs = Standar::getAll($clase,$formato,$path);
        foreach ($objs as $o) {//
            if($clase::Equal($obj,$o))//si dos objetos se consideran iguales en su clase
            {
                $retorno = true;
            }
        }
        return $retorno;
    }
    public static function getAll($clase,$formato,$path)
    {
        $datos = Archivo::Leer($path,$formato);//array de clase
        $objs = array();
        if(count($datos) != 0)
        {
            foreach ($datos as $registro) {
                $u = $clase::toClass($registro);
                if($u != null)
                {
                    array_push($objs,$u);
                }
            }
        }
        return $objs;
    }
    static function searchBy($propidad,$valor,$clase,$formato,$path)
    {
        $retorno = null;
        $objs = Standar::getAll($clase,$formato,$path);
        foreach ($objs as $o) {//
            if($o->__get($propidad) == $valor)
            {
                $retorno = $o;
            }
        }
        return $retorno;
    }
    static function update($obj,$clase,$formato,$path)
    {
        $retorno = false;
        switch ($formato) 
        {
            case 'json':
                $retorno = Standar::UpdateJson($path,$obj,$clase);
                break;
            case 'csv':
                $retorno = Standar::UpdateCSV($path,$obj,$clase);
            default:
                //string
                break;
        }
        return $retorno;
    }
    private static function UpdateJson($path,$obj,$clase)
    {
        $retorno = false;
        $objs = Standar::getAll($path,$clase,'json');
        $nuevaLista = $objs;
        if($clase::Exist($obj,$clase,'json'))
        {
            for ($i=0; $i < count($objs); $i++)
             { 
                if($clase::Equal($obj,$objs[$i]))
                {
                    $nuevaLista[$i]=$obj;
                    break;
                }
            }
            for ($j=0; $j < count($nuevaLista); $j++) { 
                $nuevaLista[$j] = $clase::toStandar($nuevaLista[$j]);
            }
            $retorno = Archivo::Update($path,$nuevaLista,'json');
        }
        return $retorno;
    }
    private static function UpdateCSV($path,$obj,$clase)
    {
        $retorno = false;
        $objs = Standar::getAll($clase,'csv',$path);
        $nuevaLista = $objs;
        $csv = $clase::toCSV($obj);
        if($clase::Exist($obj,$clase,'csv',$path) && $csv != null)
        {
            for ($i=0; $i < count($objs); $i++)
             { 
                if($clase::Equal($obj,$objs[$i]))
                {
                    $nuevaLista[$i]=$obj;
                    break;
                }
            }
            for ($j=0; $j < count($nuevaLista); $j++) { 
                $nuevaLista[$j] = $clase::toCSV($nuevaLista[$j]);
            }
            $retorno = Archivo::Update($path,$nuevaLista,'csv');
        }
        return $retorno;
    }
}