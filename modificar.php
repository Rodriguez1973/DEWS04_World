<!DOCTYPE html>

<html>
    <head>
        <title>Modificación de datos de una lengua en un país</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilos.css"/>
    </head>
    <body>
        <h1>MODIFICACIÓN DE DATOS DE UNA LENGUA EN UN PAÍS</h1>
        <?php
        session_start();
        //Si es el inicio, inicializa las variables de sesión.
        if ($_SESSION['inicio']) {
            $_SESSION['codigoPais'] = null; //Inicializa la variable de sesión código del país.
            $_SESSION['lengua'] = null; //Inicializa la variable de sesión lengua.
            $_SESSION['oficial'] = null; //Inicializa la variable de sesión oficial.
            $_SESSION['porcentaje'] = null; //Iniciliza la variable de sesión porcentaje.
            $_SESSION['rellenar'] = false; //Inicializa el flag de rellenado.
            $_SESSION['buscar'] = false; //Inicializa el flag de busqueda.
            $_SESSION['inicio'] = false; //Cambia el estado de la variable de sesión de inicio.
        }

        include './conexionBD.php';
        $mensaje = "";  //Mensaje a enviar a la interfaz.
        //Si se ha pulsado en rellenar, establece la variable de sesión del código del país y rellenar.
        if (isset($_POST['rellenar'])) {
            $_SESSION['codigoPais'] = $_POST['codigoPais'];
            $_SESSION['rellenar'] = true;
            $_SESSION['lengua'] = null;
            $_SESSION['oficial'] = null;
            $_SESSION['porcentaje'] = null;

            //Si se ha pulsado en buscar.
        } else if (isset($_POST['buscar'])) {
            //Si está establecida la lengua.
            if (isset($_POST['lengua'])) {
                //No ha cambiado el país.
                if ($_SESSION['codigoPais'] == $_POST['codigoPais']) {
                    $_SESSION['lengua'] = $_POST['lengua'];
                    $_SESSION['buscar'] = true;
                    $_SESSION['oficial'] = null;
                    $_SESSION['porcentaje'] = null;
                    //Ha cambiado el país.
                } else {
                    $_SESSION['buscar'] = false;
                    $_SESSION['rellenar'] = false;
                    $_SESSION['lengua'] = null;
                    $_SESSION['oficial'] = null;
                    $_SESSION['porcentaje'] = null;
                    $mensaje = "<p class='mensaje'>El código de país ha cambiado.</p>";
                }
            } else {
                $mensaje = "<p class='mensaje'>No se han rellenado las lenguas.</p>";
            }
            //Si se ha pulsado en aceptar y está establecida la lengua.
        } else if (isset($_POST['aceptar'])) {
            //Si está establecida la lengua.
            if (isset($_POST['lengua'])) {
                //No ha cambiado el país y la lengua.
                if ($_SESSION['codigoPais'] == $_POST['codigoPais']) {
                    if (!$_SESSION['buscar'] || $_SESSION['lengua'] != $_POST['lengua']) {
                        $mensaje = "<p class='mensaje'>No se ha realizado la busqueda de datos.</p>";
                    } else if ($_SESSION['lengua'] == $_POST['lengua'] && isset($_POST['oficial']) && isset($_POST['porcentaje'])) {
                        //No existen cambios en oficial y porcentaje.
                        if ($_SESSION['oficial'] == $_POST['oficial'] && $_SESSION['porcentaje'] == $_POST['porcentaje']) {
                            $mensaje = "<p class='mensaje'>No existen cambios en los datos. Actualización no realizada.</p>";
                            //Existen cambios, se procede a la actualización.
                        } else {
                            //Si se ha establecido la conexión con la base de datos.
                            if (isset($conexionBD)) {
                                //Actualiza el porcentaje y oficial.
                                $consulta = "update countrylanguage set isofficial='{$_POST['oficial']}', "
                                        . "percentage='{$_POST['porcentaje']}' where countrycode='{$_SESSION['codigoPais']}'"
                                        . " and language='{$_SESSION['lengua']}';";
                                $resultado = $conexionBD->query($consulta);
                                //La consulta se ha realizado correctamente.
                                if ($resultado) {
                                    $mensaje = "<p class='mensaje'>Los cambios han sido actualizados.</p>";
                                    $_SESSION['oficial'] = $_POST['oficial'];
                                    $_SESSION['porcentaje'] = $_POST['porcentaje'];
                                } else {
                                    $mensaje = "<p class='mensaje'>Los cambios no han sido actualizados.</p>";
                                }
                            }
                        }
                    } 
                    //Ha cambiado el país.
                } else {
                    $_SESSION['buscar'] = false;
                    $_SESSION['rellenar'] = false;
                    $_SESSION['lengua'] = null;
                    $_SESSION['oficial'] = null;
                    $_SESSION['porcentaje'] = null;
                    $mensaje = "<p class='mensaje'>El código de país ha cambiado.</p>";
                }
            } else {
                $mensaje = "<p class='mensaje'>No se han rellenado las lenguas.</p>";
            }
        }
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
                        //Consulta: Devuelve el codigo de país y su nombre ordenados por codigo de país.
                        $consulta = "select code, name from country order by code;";
                        $resultado = $conexionBD->query($consulta);
                        $registro = $resultado->fetch_object();
                        while ($registro != null) {
                            $seleccionado = '';
                            //Si código del pais coincide con el de la consulta se selecciona el option.
                            if ($_POST['codigoPais'] == $registro->code) {
                                $seleccionado = 'selected';
                            }
                            echo '<option value="' . $registro->code . '" ' . $seleccionado . '>' .
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
                    //Si rellenar.
                    if ($_SESSION['rellenar']) {
                        //Si se ha establecido la conexión con la base de datos.
                        if (isset($conexionBD)) {
                            $consulta = "select distinct language from countrylanguage where countrycode='" .
                                    $_POST['codigoPais'] . "' order by language;";
                            echo $consulta;
                            $resultado = $conexionBD->query($consulta);
                            $registro = $resultado->fetch_object();
                            while ($registro != null) {
                                $seleccionado = '';
                                //Si código del pais coincide con el de la consulta se selecciona el option.
                                if ($_POST['lengua'] == $registro->language) {
                                    $seleccionado = 'selected';
                                }
                                echo '<option value="' . $registro->language . '"' . $seleccionado . '>' . $registro->language
                                . '</option>';
                                $registro = $resultado->fetch_object();
                            }
                        }
                    }
                    ?>
                </select>


                <!--Botón rellenar-->
                <button type="submit" name="rellenar" id="rellenar" value="rellenar">Rellenar lenguas</button>
                <br>

                <!--Comprueba si se ha pulsado buscar-->
                <?php
                //Si se ha pulsado buscar, está establecida una lengua y el codigo del país no ha cambiado, se realiza la busqueda de los datos.
                if ($_SESSION['buscar']) {
                    //Consulta: Devuelve si es oficial y el porcentaje de personas que hablan un lenguaje en un determinado país.
                    $consulta = "select isofficial oficial, percentage porcentaje from countrylanguage "
                            . "where countrycode='{$_SESSION['codigoPais']}' and language='{$_SESSION['lengua']}';";
                    $resultado = $conexionBD->query($consulta);
                    $registro = $resultado->fetch_object();
                    //Obtiene los datos de la consulta.
                    if ($registro) {
                        $_SESSION['oficial'] = $registro->oficial;
                        $_SESSION['porcentaje'] = $registro->porcentaje;
                        if(!isset($_POST['aceptar'])){
                            $mensaje = "<p class='mensaje'>Datos buscados.</p>";
                        }
                    }
                }
                ?>

                <!--¿El idioma es oficial?-->
                <label for="oficial">Es oficial</label>
                <input id="oficial" type="radio" name="oficial" value="T" <?php
                //Chequa si el campo es el recibido en la consulta.
                if ($_SESSION['buscar'] && "T" == $_SESSION['oficial']) {
                    echo "checked";
                }
                ?> <?php
                //Establece el atributo disabled.
                if (!$_SESSION['buscar'] || !isset($_SESSION['lengua'])) {
                    echo 'disabled';
                }
                ?>>Si
                <input type="radio" name="oficial" value="F" <?php
                //Chequa si el campo es el recibido en la consulta.
                if ($_SESSION['buscar'] && "F" == $_SESSION['oficial']) {
                    echo "checked";
                }
                ?> <?php
                //Establece el atributo disabled.
                if (!$_SESSION['buscar'] || !isset($_SESSION['lengua'])) {
                    echo 'disabled';
                }
                ?>>No
                <br>

                <!--Porcentaje-->
                <label for="porcentaje">Porcentaje</label>
                <input type="number" id="porcentaje" name="porcentaje" min="0.0" max="100.0" step="0.1" value="<?php
                //Chequa si se ha establecido 'buscar' e incorpora el valor recibido de la consulta.
                if ($_SESSION['buscar']) {
                    echo $_SESSION['porcentaje'];
                }
                ?>" <?php
                       if (!$_SESSION['buscar']) {
                           echo 'readonly';
                       }
                       ?>>
                <br>

                <!--Botones-->
                <div id='cont_botones'>
                    <!--Botón buscar-->
                    <button type="submit" name="buscar" id="buscar" value="buscar">Buscar datos</button>
                    <!--Botón añadir-->
                    <button type="submit" name="aceptar" id="aceptar" value="aceptar">Aceptar cambios</button>
                </div>
            </form>
            <?php
            echo $mensaje;
            $conexionBD->close(); //Cierra la conexión con la base de datos.
        } else {
            echo $errorConexion;
        }
        ?>
        <!--Volver a inicio-->
        <a class="centrado" href="index.php">Volver a inicio</a>
    </body>
</html>