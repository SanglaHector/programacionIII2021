<?php
/*Recibe los datos del usuario(clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado,
Retorna un :
“Verificado” si el usuario existe y coincide la clave también.
“Error en los datos” si esta mal la clave.
“Usuario no registrado si no coincide el mail“
Hacer los métodos necesarios en la clase usuario*/
include '../clases/usuario.php';
include '../clases/AccesoDatos.php';
$clave ="";
$mail = "";
$mensaje = "";
$db = AccesoDatos::dameUnObjetoAcceso();
//$usuario = Usuario::createUsuario('Hector','Sangla','123456','sanglahector@gmail.com','hoy','burzaco',$db);

if(isset($_POST['clave']) && isset($_POST['mail']))
{
    $mail = $_POST['mail'];
    $clave = $_POST['clave'];
    $usuario = Usuario::createUsuario('','',$clave,$mail,'',$db);

    if(Usuario::Exist($usuario,Usuario::$tabla,$db,Usuario::name()))
    {
        if(Usuario::LogIn($usuario,Usuario::$tabla,$db,Usuario::name()))
        {
            $mensaje = "Verificado";
        }else{
            $mensaje = "Error en los datos";
        }
    }else{
        $mensaje = "Usuario no registrado si no coincide el mail";
    }
    
}else{
    $menaje = 'Faltan datos';
}

echo $mensaje;