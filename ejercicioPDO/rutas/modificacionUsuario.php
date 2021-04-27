<?php
/*Recibe los datos del usuario(nombre, clavenueva, clavevieja,mail )por POST ,
crear un objeto y utilizar sus métodos para poder hacer la modificación,
guardando los datos la base de datos
retorna si se pudo agregar o no.*/
include '../clases/usuario.php';
include '../clases/AccesoDatos.php';
$db = AccesoDatos::dameUnObjetoAcceso();
$mensaje = "";
$nombre = "";
$claveNueva = "";
$claveVieja = "";
$mail = "";
if( isset($_POST['nombre'])&&
    isset($_POST['claveVieja'])&&
    isset($_POST['claveNueva'])&&
    isset($_POST['mail']))
    {
        $mail = $_POST['mail'];
        $clave = $_POST['claveVieja'];
        $claveNueva = $_POST['claveNueva'];
        $usuario = Usuario::createUsuario('','',$clave,$mail,'',$db);
        
        if(Usuario::Exist($usuario,Usuario::$tabla,$db,Usuario::name()))
        {
            if(Usuario::LogIn($usuario,Usuario::$tabla,$db,Usuario::name()))
            {
                $usuario = Usuario::searchBy('mail',$usuario->__get('mail'),Usuario::$tabla,$db,Usuario::name());
                actualizarUsuario($usuario,$claveNueva,$db);
                $mensaje =  'Cambio de contraseña efectuado';
            }else
            {
                $mensaje = "contraseña incorrecta";
            }
        }else{
            $mensaje = "Usuario inexistente";
        }
    }else
    {
        $mensaje = "Ingrese los datos correctamente";
    }
    echo $mensaje;
    function actualizarUsuario($usuario,$nuevaClave,$db)
    {
        $usuario->__set('clave',$nuevaClave);
        Usuario::update($usuario,Usuario::$tabla,$db,Usuario::name());
    }