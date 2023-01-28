<!DOCTYPE html>
<?php
    session_start(); //Inicia las variables de sesión para su destrucción.
    session_destroy(); //Destruye las variables de sesion.
    session_start(); //Inicia las variables de sesión para su utilizacion.
    $_SESSION['inicio']=true;//Inicializa  el flag de inicio.
?>
<html>
    <head>
        <title>Gestión de la BD World</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilos.css"/>
    </head>
    <body>
        <!--Muestra el menú de gestión de BD WORLD-->
        <h1>MENÚ DE GESTIÓN DE LA BD WORLD</h1>
        <h2 id="opciones">Opciones:</h2>
        <a href="mostrar.php">Mostrar lenguas por países</a><br>
        <a href="añadir.php">Añadir lengua a un país</a><br>
        <a href="modificar.php">Modificar datos de una lengua en un país</a><br>
    </body>
</html>