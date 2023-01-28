<!DOCTYPE html>

<html>
    <head>
        <title>Añadir lengua a país</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilos.css"/>
    </head>
    <body>
        <h1>AÑADIR LENGUA A PAÍS</h1>
        <?php
        $mensaje = "";
        require_once './conexionBD.php';
        //Si se ha establecido la conexión con la base de datos.
        if (isset($conexionBD)) {
            //Añadir.
            if (isset($_POST['aniadir'])) {
                $codigoPais = $_POST['codigoPais'];
                $lengua = $_POST['lengua'];
                $oficial = $_POST['oficial'];
                $porcentaje = $_POST['porcentaje'];
                try {
                    $consulta = "insert into countrylanguage values ('$codigoPais', '$lengua', '$oficial', $porcentaje);";
                    $resultado = $conexionBD->query($consulta);
                    $mensaje = "<p class='mensaje'>El registro ha sido añadido correctamente.</p>";
                    //Se ha producido una exception al realizar la consulta.
                } catch (Exception $ex) {
                    $mensaje = "<p class='mensaje'>El registro no se ha añadido.</p>"
                            . "<p class='mensaje'>" . $ex->getMessage() . "</p>";
                }
            }
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
                            //Crea la cadena a concatenar en función de si es el codigoPais añadido o no con el 
                            //fin de no perder lo introducido en la interfaz.
                            $seleccionado = '';
                            if (isset($_POST['aniadir']) && $registro->code == $codigoPais) {
                                $seleccionado = "selected";
                            }
                            echo '<option value="' . $registro->code . '"' . $seleccionado . '>' .
                            $registro->code . ' - ' . $registro->name . '</option>';
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
                            //Crea la cadena a concatenar en función de si es el idioma añadido o no con el 
                            //fin de no perder lo introducido en la interfaz.
                            $seleccionado = '';
                            if (isset($_POST['aniadir']) && $registro->language == $lengua) {
                                $seleccionado = "selected";
                            }
                            echo '<option value="' . $registro->language . '"' . $seleccionado . '>' .
                            $registro->language . '</option>';
                            $registro = $resultado->fetch_object();
                        }
                        $conexionBD->close(); //Cierra la conexión.
                    }
                    ?>
                </select>
                <br>
                <!--¿El idioma es oficial?-->
                <label for="oficial">Es oficial</label>
                <input id="oficial" type="radio" name="oficial" value="T" <?php
                //Chequa el campo si es lo introducido tras añadir.
                if (isset($_POST['aniadir']) && "T" == $_POST['oficial']) {
                    echo "checked";
                } else if (!isset($_POST['aniadir'])) {
                    echo "checked";
                }
                ?>>Si
                <input type="radio" name="oficial" value="F" <?php
                //Chequa el campo si es lo introducido tras añadir.
                if (isset($_POST['aniadir']) && "F" == $_POST['oficial']) {
                    echo "checked";
                }
                ?>>No
                <br>
                <!--Porcentaje-->
                <label for="porcentaje">Porcentaje</label>
                <input type="number" id="porcentaje" name="porcentaje" min="0.0" max="100.0" step="0.1" required value="<?php
                //Rellena el campo tras añadir para mantener el dato en la insterfaz.
                if (isset($_POST['aniadir'])) {
                    echo (float)$porcentaje;
                } else {
                    echo (float)"0.0";
                }
                ?>">                
                <br>
                <!--Botón añadir-->
                <button type="submit" name="aniadir" id="aniadir">Añadir idioma al país</button>
            </form>
            <?php
            echo $mensaje;
        } else {
            echo $errorConexion;
        }
        ?>
        <!--Volver a inicio-->
        <a class="centrado" href="index.php">Volver a inicio</a>
    </body>
</html>