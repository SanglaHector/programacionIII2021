<?php
/*
Pruebas de delete */
include '../clases/usuario.php';
include '../clases/AccesoDatos.php';
$db = AccesoDatos::dameUnObjetoAcceso();
$id = 1;
$mensaje = "";
if(isset($_POST['id']))
{
    $id = $_POST['id'];
    $usuario = Usuario::searchBy('id',$id,Usuario::$tabla,$db,Usuario::name());
    Usuario::delete($usuario,Usuario::$tabla,$db,Usuario::name());
    $mensaje = "Se borro";
}else{
    $mensaje = "Ingrese id";
}
echo $mensaje;