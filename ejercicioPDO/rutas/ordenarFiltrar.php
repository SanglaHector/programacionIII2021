<?php
include '../clases/usuario.php';
include '../clases/producto.php';
include '../clases/venta.php';
include '../clases/AccesoDatos.php';

$db = AccesoDatos::dameUnObjetoAcceso();
$datos = 'nada';
if(isset($_GET['filtro']))
{
    $filtro = $_GET['filtro'];
    switch ($filtro) {
        case 'string':
            $datos = Usuario::orderBy($db,Usuario::$tabla,Usuario::name(),'ASC','apellido');
            break;
        case 'betweenStr':
            $val1 = "2020-07-01";
            $val2 = "2020-12-01";
            $propiedad = "fecha_de_registro";
            $datos = Usuario::betweenStr($db,Usuario::$tabla,Usuario::name(),$val1,$val2,$propiedad);
            break;
        case 'betweenInt':
            $val1 = 30;
            $val2 = 60;
            $propiedad = 'stock';
            $datos = Producto::betweenInt($db,Producto::$tabla,Producto::name(),$val1,$val2,$propiedad);
            break;
        case 'sumBetween':
            $val1 = "2020-07-01";
            $val2 = "2020-12-01";
            $propiedad = 'fecha_de_venta';
            $sum = 'cantidad';
            $datos = Venta::sumBetween($db,Venta::$tabla,Venta::name(),$val1,$val2,$propiedad,$sum); 
            break;
        case 'selectFirst':
            $propiedad = 'fecha_de_venta';
            $cant = 3;
            $datos = Venta::selectFirst($db,Venta::$tabla,Venta::name(),$propiedad,$cant);
            break;
        case 'allData':
            $propiedad = "";
            $datos = Venta::allData($db,Venta::$tabla,Venta::name(),$propiedad);
            break;
        case 'sellectMultiply':
            $datos = Venta::selectMultiply($db,Venta::$tabla,Venta::name());
            break;
        default:
            $datos = array();
            break;
    }
}
var_dump($datos);