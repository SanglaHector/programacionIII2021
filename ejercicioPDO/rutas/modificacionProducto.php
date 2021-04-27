<?php
/*Recibe los datos del producto(código de barra (6 sifras ),nombre ,tipo, stock, precio )por POST
,
crear un objeto y utilizar sus métodos para poder verificar si es un producto existente,
si ya existe el producto el stock se sobrescribe y se cambian todos los datos excepto:
el código de barras .
Retorna un :
“Actualizado” si ya existía y se actualiza
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesarios en la clase*/
include '../clases/AccesoDatos.php';
include '../clases/producto.php';
$mensaje = "";
$nombre = "";
$codigo ="";
$tipo = "";
$stock = "";
$precio = "";
$retorno = false;
$db = AccesoDatos::dameUnObjetoAcceso();
if( isset($_POST['codigo'])&&
    isset($_POST['nombre'])&&
    isset($_POST['tipo'])&&
    isset($_POST['stock'])&&
    isset($_POST['precio']))
    {
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];
        $producto = Producto::createProducto($db,$codigo,$nombre,$tipo,$stock,$precio);
        if(Producto::Exist($producto,Producto::$tabla,$db,Producto::name()))
        {
            $producto = Producto::searchBy('codigo_de_barra',$codigo,Producto::$tabla,$db,Producto::name());
            actualizarProducto($producto,$nombre,$tipo,$stock,$precio,$db);
            $mensaje = "Producto actualizado";
        }else{
           $mensaje = "no se pudo hacer";
        }
    }else{
        $mensaje = "Por favor complete todos los campos";
    }


    // if($retorno != true)
    // {
    //     $mensaje = 'Error';
    // }else{
    //     $mensaje = 'Alta/modificacion correcta';
    // }
    echo $mensaje;

    function actualizarProducto($producto,$nombre,$tipo,$stock,$precio,$db)
    {
        $producto->__set('nombre',$nombre);
        $producto->__set('tipo',$tipo);
        $producto->__set('stock',$stock);
        $producto->__set('precio',$precio);
        Producto::update($producto,Producto::$tabla,$db,Producto::name());
    }