<?php
/*Recibe los datos del usuario(nombre, clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder hacer el alta,
guardando los datos en usuarios.csv.
retorna si se pudo agregar o no.
Cada usuario se agrega en un renglón diferente al anterior.
Hacer los métodos necesarios en la clase usuario*///ejercicio23
include '../clases/usuario.php';
include '../clases/AccesoDatos.php';
$nombre ="";
$clave ="";
$mail = "";
$mensaje = "";
$db = AccesoDatos::dameUnObjetoAcceso();
if(isset($_POST['nombre']) && isset($_POST['clave']) && isset($_POST['mail']) && isset($_POST['localidad']))//
{
    $nombre = $_POST['nombre'];
    $mail = $_POST['mail'];
    $clave = $_POST['clave'];
    $localidad = $_POST['localidad'];
    $apellido = $_POST['apellido'];
    $usuario = Usuario::createUsuario($nombre,$apellido,$clave,$mail,$localidad,$db);
    if(isset($_FILES['imagen']['name']))
    {
        $destino = "../archivos/imagenes/";
        if($usuario->guardoImagen($_FILES,$destino))
        {
            $mensaje = Usuario::Add($db,$usuario,Usuario::$tabla,Usuario::name()); 
            if($mensaje == true)
            {
                $mensaje = 'Alta correcta';
            }else{
                $mensaje = 'Alta incorrecta';
            }
        }else{
            $mensaje = 'Error al subir imagen';
        }
    }else{
        $mensaje = "por favor cargue una imagen";
    }
}else{
    $menaje = 'Faltan datos';
}

echo $mensaje;