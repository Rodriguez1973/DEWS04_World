<?php
$nombreServidor='localhost';
$usuario='root';
$contraseña='';
$baseDatos='world';
$errorConexion=false; //Flag que controla si existe un error en la conexión a la base de datos.

try{
    $conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);
}catch(Exception $exception){
    $errorConexion= "<p class='mensaje'>No es posible realizar la conexión con la base de datos.<br>".
            $exception->getMessage()."</p>";
}