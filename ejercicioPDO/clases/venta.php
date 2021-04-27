<?php
include_once '../clases/PDOstandar.php';
include_once '../interfaces/IPDOstandar.php';
class Venta extends PDOstandar implements IPDOstandar{
    protected $id;
    protected $id_producto;
    protected $id_usuario;
    protected $cantidad;
    protected $fecha_de_venta;
    public static $tabla = 'venta';

    static function createVenta($db,$idProducto,$idUsuario,$cantidad,$fechaDeVenta=null,$id=null)
    {      
        $venta = new Venta();
        $venta->__set('id_producto',$idProducto);
        $venta->__set('id_usuario',$idUsuario);
        $venta->__set('cantidad',$cantidad);
        $fechaDeVenta = date('Y-m-d');
        $venta->__set('fecha_de_venta',$fechaDeVenta);
        $id = Venta::createId($db);
        $venta->__set('id',$id);
        return $venta;
    }
    public function __construct()
    {
    }
    static function Equal($objUno,$objDos)
    {
        $retorno =  false;
        if($objUno->__get('id') == $objDos->__get('id'))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static function createId($db)
    {
        $param = 'id';
        $string = ("SELECT MAX($param) FROM ".Venta::$tabla);
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $id = $sentencia->fetch(PDO::FETCH_ASSOC);
        return $id['MAX(id)'] + 1;
    }
    static function arrToStd($registro)
    {
        $retorno = new stdClass();
        for ($i=0; $i < count($registro); $i++) { 
             $retorno->idProducto = $registro[0];
             $retorno->idUsuario = $registro[1];
             $retorno->cantidad = $registro[2];
             $retorno->fechaDeVenta = $registro[3];
             $retorno->id = $registro[4];
        }
        return $retorno;
    }
    public function __toString()
    {
        $retorno = "";
        $retorno = $retorno."Id producto: ".$this->__get('idProducto').'<br>';
        $retorno = $retorno."Id usuario: ".$this->__get('idUsuario').'<br>';
        $retorno = $retorno."Cantidad: ".$this->__get('cantidad').'<br>';
        $retorno = $retorno."Fecha de venta: ".$this->__get('fechaDeVenta').'<br>';
        $retorno = $retorno."**********************************".'<br>';
        return $retorno;
    }
    static function addString()
    {
        return '(id,id_producto,id_usuario,cantidad,fecha_de_venta) VALUES(
            :id,
            :id_producto,
            :id_usuario,
            :cantidad,
            :fecha_de_venta)';
    }
    static function addBind($sentencia,$objeto)
    {
        $sentencia->bindParam(':id',$objeto->id,PDO::PARAM_INT);
        $sentencia->bindParam(':id_producto',$objeto->id_producto,PDO::PARAM_INT);
        $sentencia->bindParam(':id_usuario',$objeto->id_usuario,PDO::PARAM_INT);
        $sentencia->bindParam(':cantidad',$objeto->cantidad,PDO::PARAM_INT);
        $sentencia->bindParam(':fecha_de_venta',$objeto->fecha_de_venta,PDO::PARAM_STR);
    }
    static function updateString($id)
    {
        return "";
    }
    static function updateBind($sentencia,$objeto)
    {
    }
    static function deleteString($id)
    {
        return "WHERE id = $id";
    }static function betweenString($propiedad)
    {
        return " WHERE ($propiedad BETWEEN :val1 AND :val2);";

    }
    static function betweenBindStr($sentencia,$val1,$val2)
    {
        $sentencia->bindParam(':val1',$val1,PDO::PARAM_STR);
        $sentencia->bindParam(':val2',$val2,PDO::PARAM_STR);
    }
    static function betweenBindInt($sentencia,$val1,$val2)
    {
        $sentencia->bindParam(':val1',$val1,PDO::PARAM_INT);
        $sentencia->bindParam(':val2',$val2,PDO::PARAM_INT);
    }
    
}