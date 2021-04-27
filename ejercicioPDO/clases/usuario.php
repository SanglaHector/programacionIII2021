<?php
include_once '../clases/PDOstandar.php';
include_once '../clases/archivo.php';
include_once '../interfaces/IPDOstandar.php';
class Usuario extends PDOstandar implements IPDOstandar{
    protected $id;
    protected $nombre;
    protected $apellido;
    protected $clave;
    protected $mail;
    protected $fecha_de_registro;
    protected $locadlidad;
    public static $tabla = 'usuario';

    static function createUsuario($nombre,$apellido,$clave,$mail,$localidad,$db)
    {
        $usuario = new Usuario();
        $usuario->__set('nombre',$nombre);
        $usuario->__set('apellido',$apellido);
        $usuario->__set('clave',$clave);
        $usuario->__set('mail',$mail);
        $usuario->__set('fecha_de_registro',date('Y-m-d'));
        $usuario->__set('localidad',$localidad);
        $usuario->__set('id',Usuario::createId($db));
        return $usuario;    
    }
    static function instanciarUsuario($id,$nombre,$apellido,$clave,$mail,$fecha_de_registro,$localidad)
    {
        $usuario = new Usuario();
        $usuario->__set('nombre',$nombre);
        $usuario->__set('apellido',$apellido);
        $usuario->__set('clave',$clave);
        $usuario->__set('mail',$mail);
        $usuario->__set('fecha_de_registro',$fecha_de_registro);
        $usuario->__set('localidad',$localidad);
        $usuario->__set('id',$id);
        return $usuario;
    }
    public function __construct(){

    }
    static function createId($db)
    {
        $param = 'id';
        $string = ("SELECT MAX($param) FROM ".Usuario::$tabla);
        $sentencia = $db->RetornarConsulta($string);
        $sentencia->execute();
        $id = $sentencia->fetch(PDO::FETCH_ASSOC);
        return $id['MAX(id)'] + 1;
    }
    static function addString()
    {
        return '(id,nombre,apellido,clave,mail,fecha_de_registro,localidad) VALUES(
            :id,
            :nombre,
            :apellido,
            :clave,
            :mail,
            :fechaRegistro,
            :localidad)';
    }
    static function addBind($sentencia,$objeto)
    {
        $sentencia->bindParam(':nombre',$objeto->nombre,PDO::PARAM_STR);
        $sentencia->bindParam(':apellido',$objeto->apellido,PDO::PARAM_STR);
        $sentencia->bindParam(':clave',$objeto->clave,PDO::PARAM_INT);
        $sentencia->bindParam(':mail',$objeto->mail,PDO::PARAM_STR);
        $sentencia->bindParam(':fechaRegistro',$objeto->fecha_de_registro,PDO::PARAM_STR);
        $sentencia->bindParam(':localidad',$objeto->localidad,PDO::PARAM_STR);
        $sentencia->bindParam(':id',$objeto->id,PDO::PARAM_STR);
    }
    public static function LogIn($usuario,$tabla,$db,$clase)
    {
        if(Usuario::Exist($usuario,$tabla,$db,$clase))
        {
            $retorno = false;
            $usuarios = Usuario::getAll($tabla,$db,$clase);
            foreach ($usuarios as $u) {
                if($usuario->mail == $u->mail && $usuario->clave == $u->clave)
                {
                    $retorno = true;
                }else if($usuario->mail == $u->mail)
                {   
                    if(strcmp($usuario->clave,$u->clave)== 0)
                    {
                        $retorno = true;
                    }
                }
            }
            return $retorno;
        }
    }
    static function Equal($u1,$u2)
    {
        $retorno =  false;
        if($u1->__get('mail') == $u2->__get('mail'))
        {
            $retorno = true;
        }
        return $retorno;
    }
    public function guardoImagen($file,$destino)
    {
        $retorno = false;
        $nombre = explode('@',$this->__get('mail'))[0];
        if(Archivo::guardarImagen($_FILES['imagen'],$destino,$nombre))
        {
            $retorno = true;
        }
        return $retorno;
    }
    static function updateString($id)
    {
        return "SET nombre = :nombre,
        apellido = :apellido,
        clave = :clave,
        mail = :mail,
        localidad = :localidad
        WHERE id = $id";
    }
    static function updateBind($sentencia,$objeto)
    {
        $sentencia->bindParam(':nombre',$objeto->nombre);
        $sentencia->bindParam(':apellido',$objeto->apellido);
        $sentencia->bindParam(':clave',$objeto->clave);
        $sentencia->bindParam(':mail',$objeto->mail);
        $sentencia->bindParam(':localidad',$objeto->localidad);
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