<!DOCTYPE html>

<html>
    <head>
        <title>Gestión de la BD World</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilos.css"/>
    </head>
    <body>
        <h1>MODIFICACIÓN DE DATOS DE UNA LENGUA EN UN PAÍS</h1>
        <?php
        $mensaje = "";
        require_once './conexionBD.php';
        //Si se ha establecido la conexión con la base de datos.
        if (isset($conexionBD)) {
        
        ?>
            <form id="formulario" name="formulario" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <h2>Datos del país - lengua</h2>
                <!--Código del país-->
                <label for="codigoPais">Código del país</label>
                <select name="codigoPais" id="codigoPais">
                    <?php
                    //Si se ha establecido la conexión con la base de datos.
                    if (isset($conexionBD)) {
                        $consulta = "select code, name from country order by code;";
                        $resultado = $conexionBD->query($consulta);
                        $registro = $resultado->fetch_object();
                        while ($registro != null) {
                            echo '<option value="' . $registro->code . '">' . $registro->code . ' - ' . $registro->name . '</option>';
                            $registro = $resultado->fetch_object();
                        }
                    }
                    ?>
                </select>
                <br>
                <!--Lengua-->
                <label for="lengua">Lengua</label>
                <select name="lengua" id="lengua">
                    <?php
                    //Si se ha establecido la conexión con la base de datos.
                    if (isset($conexionBD)) {
                        $consulta = "select distinct language from countrylanguage order by language;";
                        $resultado = $conexionBD->query($consulta);
                        $registro = $resultado->fetch_object();
                        while ($registro != null) {
                            echo '<option value="' . $registro->language . '">' . $registro->language . '</option>';
                            $registro = $resultado->fetch_object();
                        }
                        $conexionBD->close(); //Cierra la conexión.
                    }
                    ?>
                </select>
                <br>
                <!--¿El idioma es oficial?-->
                <label for="oficial">Es oficial</label>
                <input id="oficial" type="radio" name="oficial" value="T" checked>Si
                <input type="radio" name="oficial" value="F">No
                <br>
                <!--Porcentaje-->
                <label for="porcentaje">Porcentaje</label>
                <input type="number" id="porcentaje" name="porcentaje" min="0.0" max="100.0" step="0.1" required value="0s">
                <br>
                <!--Botón añadir-->
                <button type="submit" name="aniadir" id="aniadir">Añadir idioma al país</button>
            </form>
        <?php echo $mensaje; 
        } else {
            echo $errorConexion;
        }      
        ?>
        <!--Volver a inicio-->
        <a class="centrado" href="index.php">Volver a inicio</a>
    </body>
</html>

