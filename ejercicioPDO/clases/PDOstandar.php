<?php
class PDOstandar{
    public static $tabla;

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
    static function Add($db,$objeto,$tabla,$clase)
    {
        $addString  = $clase::addString();
        $string = "INSERT INTO ".$tabla." ".$addString;
        $sentencia = $db->RetornarConsulta($string);
        $clase::addBind($sentencia,$objeto);
        return $sentencia->execute();
    }
    static function Exist($obj,$tabla,$db,$clase)//ver 
    {
        $retorno = false;
        $objs = PDOstandar::getAll($tabla,$db,$clase);
        
        foreach ($objs as $o) {//
            if($clase::Equal($obj,$o))//si dos objetos se consideran iguales en su clase
            {
                $retorno = true;
            }
        }
        return $retorno;
    }
    public static function getAll($tabla,$db,$clase)
    {
        $string = "SELECT * FROM ".$tabla;
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_CLASS,$clase);
        return $datos;
    }
    static function searchBy($propidad,$valor,$tabla,$db,$clase)//retorna un objeto
    {
        $retorno = null;
        $objs = PDOstandar::getAll($tabla,$db,$clase);
        foreach ($objs as $o) {
            if($o->__get($propidad) == $valor)
            {
                $retorno = $o;
            }
        }
        return $retorno;
    }
    static function update($obj,$tabla,$db,$clase)
    {
        $retorno = false;
        $objs = PDOstandar::getAll($tabla,$db,$clase);
        if($clase::Exist($obj,$tabla,$db,$clase))
        {
            for ($i=0; $i < count($objs); $i++)
            { 
                if($clase::Equal($obj,$objs[$i]))
                {
                    $id = $obj->id;
                    $string = "UPDATE ".$tabla." 
                    WHERE id = $id";
                    $sentencia = $db->RetornarConsulta($string);
                    $retorno = $sentencia->execute();
                    break;
                }
            }
        }
        return $retorno;
    }
    static function delete($obj,$tabla,$db,$clase)
    {
        $retorno = false;
        $objs = PDOstandar::getAll($tabla,$db,$clase);
        if($clase::Exist($obj,$tabla,$db,$clase))
        {
            for ($i=0; $i < count($objs); $i++)
            { 
                if($clase::Equal($obj,$objs[$i]))
                {
                    $dltString = $clase::deleteString($obj->id);
                    $string = "DELETE FROM ".$tabla." ".$dltString;
                    $sentencia = $db->RetornarConsulta($string);
                    $clase::deleteBind($sentencia,$obj);
                    $retorno = $sentencia->execute();
                    break;
                }
            }
        }
        return $retorno;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //////ORDENAMIENTO, FILTROS, ETC. //////////////////////////////////////////////////////////////////
    public static function orderBy($db,$tabla,$clase,$orden,$propiedad)
    {
        if($orden == 'DESC')
        {
            $orden = "DESC";
        }else
        {
            $orden = "ASC";
        }
        $string = "SELECT * FROM ".$tabla." 
        ORDER BY $propiedad $orden;";
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_CLASS,$clase);
        return $datos;
    }
    public static function betweenStr($db,$tabla,$clase,$val1,$val2,$propiedad)
    {
        $stringBetween = $clase::betweenString($propiedad);
        $string = "SELECT * FROM ".$tabla.$stringBetween;
        $sentencia = $db->RetornarConsulta($string);
        $clase::betweenBindStr($sentencia,$val1,$val2);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_CLASS,$clase);
        return $datos;
    }
    public static function betweenInt($db,$tabla,$clase,$val1,$val2,$propiedad)
    {
        $stringBetween = $clase::betweenString($propiedad);
        $string = "SELECT * FROM ".$tabla.$stringBetween;
        $sentencia = $db->RetornarConsulta($string);
        $clase::betweenBindInt($sentencia,$val1,$val2);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_CLASS,$clase);
        return $datos;
    }
    public static function sumBetween($db,$tabla,$clase,$val1,$val2,$propiedad,$sum)
    {
        $stringSumBetWeen = $clase::betweenString($propiedad);
        $string = "SELECT SUM($sum) FROM ".$tabla.$stringSumBetWeen;
        $sentencia = $db->RetornarConsulta($string);
        $clase::betweenBindInt($sentencia,$val1,$val2);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_CLASS,$clase);
        $ret = "SUM($sum)";
        return $datos[0]->$ret;
    }
    public static function selectFirst($db,$tabla,$clase,$propiedad,$cant)
    {
        /*$string = "SELECT DISTINCT P.`id` 
        FROM `producto` P,`venta` V 
        WHERE P.`id` = V.`id_producto` 
        ORDER BY V.`fecha_de_venta` ASC 
        LIMIT 3";*/
        $string = "SELECT DISTINCT P.`id` 
        FROM `producto` P,`venta` V 
        WHERE P.`id` = V.`id_producto` 
        ORDER BY V.$propiedad ASC 
        LIMIT $cant";
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_CLASS,$clase);
        return $datos;
    }
    public static function allData($db,$tabla,$clase,$propiedad)
    {
        $string = "SELECT V.id, U.apellido, U.mail, P.nombre
        FROM venta V, usuario U, producto P
        WHERE V.id_usuario = U.id 
        AND   V.id_producto = P.id";
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $datos;
    }
    public static function selectMultiply($db,$tabla,$clase)
    {
        $string = "SELECT V.id , P.precio, V.cantidad, (V.cantidad * P.precio)
        FROM venta V,producto P
        WHERE V.id_producto = P.id";
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $datos;
    }
    public static function countByUser($db,$tabla,$clase)
    {
        $string = "SELECT COUNT(id_producto)
        FROM venta
        WHERE id_usuario = 104
        AND   id_producto = 1003";
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $datos;
    }
    public static function todosIdProductosVendidosUsuarioFiltradoPorLocalidad($db)
    {
        $string = "SELECT DISTINCT P.id 
        FROM venta V, usuario U, producto P
        WHERE V.id_usuario = U.id
        AND   V.id_producto = P.id
        AND   U.localidad = 'Avellaneda' ";
    }//sin probar
    public static function buscarPorLetra($db)
    {
        $string = "SELECT * 
        FROM `usuario` 
        WHERE `nombre` LIKE '%m' 
        OR `nombre` LIKE 'm%' 
        OR `nombre` LIKE '%m%'";
    }

}