<?php
include_once '../clases/PDOstandar.php';
include_once '../interfaces/IPDOstandar.php';
class Producto extends PDOstandar implements IPDOstandar{
    protected $id;
    protected $codigo_de_barra;
    protected $nombre;
    protected $tipo;
    protected $stock;
    protected $precio;
    protected $fecha_de_creacion;
    protected $fecha_de_modificacion;
    public static $tabla = 'producto';
    
    static function createProducto($db,$codigo,$nombre,$tipo,$stock,$precio)
    {
        //validaciones
        $producto = new Producto();
        $producto->__set('id',Producto::createId($db));
        $producto->__set('codigo_de_barra',$codigo);
        $producto->__set('nombre',$nombre);
        $producto->__set('tipo',$tipo);
        $producto->__set('stock',$stock);
        $producto->__set('precio',$precio);
        $producto->__set('fecha_de_creacion',date('Y-m-d'));
        return $producto;
    }
    public function __construct()
    {
    }
    static function Equal($pUno,$pDos)
    {
        $retorno =  false;
        if($pUno->__get('codigo_de_barra') == $pDos->__get('codigo_de_barra'))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static function createId($db)
    {
        $param = 'id';
        $string = ("SELECT MAX($param) FROM ".Producto::$tabla);
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $id = $sentencia->fetch(PDO::FETCH_ASSOC);
        return $id['MAX(id)'] + 1;
    }
    public static function addStock($producto,$db,$cantidad)
    {
        $nuevoStock = $producto->__get('stock') + $cantidad;
        $producto->__set('stock',$nuevoStock);
        $retorno = Producto::update($producto,Producto::$tabla,$db,Producto::name());
        return $retorno;
    }
    public static function restStock($producto,$db,$cantidad)
    {
        $nuevoStock = $producto->__get('stock') - $cantidad;
        $producto->__set('stock',$nuevoStock);
        $retorno = Producto::update($producto,Producto::$tabla,$db,Producto::name());
        return $retorno;
    }
    public function __toString()
    {
        $retorno = "";
        $retorno = $retorno."Nombre: ".$this->__get('nombre').'<br>';
        $retorno = $retorno."Codigo de barras: ".$this->__get('codigo_de_barra').'<br>';
        $retorno = $retorno."Tipo: ".$this->__get('tipo').'<br>';
        $retorno = $retorno."Stock: ".$this->__get('stock').'<br>';
        $retorno = $retorno."Precio: ".$this->__get('precio').'<br>';
        $retorno = $retorno."**********************************".'<br>';
        return $retorno;
    }
    static function addString()
    {
        return '(id,codigo_de_barra,nombre,tipo,stock,precio,fecha_de_creacion) VALUES(
            :id,
            :codigo_de_barra,
            :nombre,
            :tipo,
            :stock,
            :precio,
            :fecha_de_creacion)';
    }
    static function addBind($sentencia,$objeto)
    {
        $sentencia->bindParam(':id',$objeto->id,PDO::PARAM_INT);
        $sentencia->bindParam(':codigo_de_barra',$objeto->codigo_de_barra,PDO::PARAM_STR);
        $sentencia->bindParam(':nombre',$objeto->nombre,PDO::PARAM_STR);
        $sentencia->bindParam(':tipo',$objeto->tipo,PDO::PARAM_STR);
        $sentencia->bindParam(':stock',$objeto->stock,PDO::PARAM_INT);
        $sentencia->bindParam(':precio',$objeto->precio,PDO::PARAM_INT);
        $sentencia->bindParam(':fecha_de_creacion',$objeto->fecha_de_creacion,PDO::PARAM_STR);
    }
    static function updateString($id)
    {
        return "SET codigo_de_barra = :codigo_de_barra,
        nombre = :nombre,
        tipo = :tipo,
        stock = :stock,
        precio = :precio,
        fecha_de_modificacion = :fecha_de_modificacion
        WHERE id = $id";
    }
    static function updateBind($sentencia,$objeto)
    {
        $sentencia->bindParam(':codigo_de_barra',$objeto->codigo_de_barra);
        $sentencia->bindParam(':nombre',$objeto->nombre);
        $sentencia->bindParam(':tipo',$objeto->tipo);
        $sentencia->bindParam(':stock',$objeto->stock);
        $sentencia->bindParam(':precio',$objeto->precio);
        $fecha = date('Y-m-d');
        $sentencia->bindParam(':fecha_de_modificacion',$fecha);
    }
    static function deleteString($id)
    {
        return "WHERE id = $id";
    }
    static function betweenString($propiedad)
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