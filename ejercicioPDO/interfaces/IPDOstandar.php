<?php
interface IPDOstandar{
    static function addString();
    static function addBind($sentencia,$objeto);
    static function updateString($id);
    static function updateBind($sentencia,$objeto);
  /*  static function deleteString($id);
    static function deleteBind($sentencia,$objeto);*/ //no hacen falta por el momento

    static function createId($db);
    static function Equal($u1,$u2);

    //static function toClass($obj);//transforma un stdClass a un objeto de clase
    //static function toStandar($obj);//tranforma un objeto de clase a un stdClass
    //static function arrToStd($registro);// es un parche ya que la lectura de csv retorna un array.
}