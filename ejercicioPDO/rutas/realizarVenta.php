<?php
/*Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems ,por
POST .
Verificar que el usuario y el producto exista y tenga stock.
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000).
carga los datos necesarios para guardar la venta en un nuevo renglón.
Retorna un :
“venta realizada”Se hizo una venta
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesarios en las clases*/
include '../clases/producto.php';
include '../clases/venta.php';
include '../clases/usuario.php';
include '../clases/AccesoDatos.php';
$mensaje = "no se puede hacer la venta";
$codigo ="";
$id = "";
$cantidad = "";
$db = AccesoDatos::dameUnObjetoAcceso();
try{

    if( isset($_POST['codigoDeBarra'])&&
        isset($_POST['id'])&&
        isset($_POST['cantidad']))
        {
            $codigo = $_POST['codigoDeBarra'];
            $id = $_POST['id'];
            $cantidad = $_POST['cantidad'];
            $producto = Producto::searchBy('codigo_de_barra',$codigo,Producto::$tabla,$db,Producto::name());
            $usuario = Usuario::searchBy('id',$id,Usuario::$tabla,$db,Usuario::name());
            if($producto != null && $usuario != null )
            {
                if(vender($producto,$usuario,$cantidad,$db))
                {
                    $mensaje = "Venta existosa";
                }else
                {
                    $mensaje = "Error en venta";
                }
            }else if($producto == null)
            {
                $mensaje = "Producto inexistente";
            }else if($usuario == null)
            {
                $mensaje = "Usuario inexistente";
            }
        }else
        {
            $mensaje = "Ingrese los datos correctamente";
        }
    }catch(Exception $e)
    {
        $mensaje = $e->getMessage();
    }
    echo $mensaje;
    function vender($producto,$usuario,$cantidad,$db)
    {
        $retorno = false;
        if($producto->__get('stock') >= $cantidad)
        {
            $venta = Venta::createVenta($db,$producto->__get('id'),$usuario->__get('id'),$cantidad);
            Producto::restStock($producto,$db,$cantidad);
            Venta::Add($db,$venta,Venta::$tabla,Venta::name());
            $retorno = true;
        }else
        {
            $retorno =false;
        }  
        return $retorno;
    }