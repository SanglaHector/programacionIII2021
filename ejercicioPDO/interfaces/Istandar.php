<?php
interface iStandar{
    
    static function toClass($obj);//transforma un stdClass a un objeto de clase
    static function toStandar($obj);//tranforma un objeto de clase a un stdClass
    static function toCSV($obj);//tranforma el objeto en un string separado por comas
    static function Equal($objUno,$objDos);//en base a las condiciones estabecidas por la clase, determina dos objetos son iguales
    static function createId($path);// crea el id a gusto de la clase
    static function arrToStd($registro);// es un parche ya que la lectura de csv retorna un array.
}