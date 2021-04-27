<?php
/*Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,...etc),por ahora solo tenemos
usuarios).
En el caso de usuarios carga los datos del archivo usuarios.json.
se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista*/
include '../clases/usuario.php';
include '../clases/producto.php';
include '../clases/venta.php';
include '../clases/AccesoDatos.php';
$mensaje = "";
$db = AccesoDatos::dameUnObjetoAcceso();
if(isset($_GET['listado']))
{
    $listado = $_GET['listado'];
    switch ($listado) {
        case 'usuarios':
            $usuarios = Usuario::getAll(Usuario::$tabla,$db,Usuario::name());
            if(count($usuarios)== 0)
            {
                $mensaje = "no hay usuarios";
            }
            foreach ($usuarios as $usuario) {
                $mensaje = $mensaje.$usuario->__toString();
            }
            break;
        case 'productos':
            $productos = Producto::getAll(Producto::$tabla,$db,Producto::name());
            if(count($productos)== 0)
            {
                $mensaje = "no hay productos";
            }
            foreach ($productos as $producto) {
                $mensaje = $mensaje.$producto->__toString();
            }
            break;
            case 'ventas':
            $ventas = Venta::getAll(Venta::$tabla,$db,Venta::name());
            if(count($ventas)== 0)
            {
                $mensaje = "no hay ventas";
            }
            foreach ($ventas as $venta) {
                $mensaje = $mensaje.$venta->__toString();
                //aca
            }
            break;    
        default:
            $mensaje = "Listado ingresado inexistente(por ahora solo tenemos 'usuarios','productos' y 'vantas')";
            break;
    }

}else{
    $mensaje = "ingrese un listado (por ahora solo tenemos 'usuarios'";
}
echo $mensaje;
function mostrarVenta($usuario,$producto,$venta)
{
    
}