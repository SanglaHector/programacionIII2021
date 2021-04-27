<?php
/*Recibe los datos del producto(código de barra (6 sifras ),nombre ,tipo, stock, precio )por POST
,
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000).
crear un objeto y utilizar sus métodos para poder verificar si es un producto existente,
si ya existe el producto se le suma el stock , de lo contrario se agrega al documento en un
nuevo renglón
Retorna un :
“Ingresado” si es un producto nuevo
“Actualizado” si ya existía y se actualiza el stock.
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesarios en la clase*/
include '../clases/producto.php';
include '../clases/AccesoDatos.php';
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
            $retorno = Producto::addStock($producto,$db,$stock);
        }else{
            $retorno = Producto::Add($db,$producto,Producto::$tabla,Producto::name());
        }
    }else{
        $mensaje = "Por favor complete todos los campos";
    }
    if($retorno != true)
    {
        $mensaje = 'Error';
    }else{
        $mensaje = 'Alta/modificacion correcta';
    }
    echo $mensaje;