<?php
$nombreServidor='localhost';
$usuario='root';
$contraseña='';
$baseDatos='world';

try{
    $conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);
}catch(Exception $exception){
    $errorConexion= "<p class='mensaje'>No es posible realizar la conexión con la base de datos.<br>".
            $exception->getMessage()."</p>";
}