<!DOCTYPE html>

<html>
    <head>
        <title>Listado de lenguas en el mundo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilos.css"/>
    </head>
    <body>
        <h1>LISTADO DE LENGUAS EN EL MUNDO</h1>
        <?php
        require_once './conexionBD.php';
        //Si se ha establecido la conexión con la base de datos.
        if (isset($conexionBD)) {
            //Consulta: Obriene el codigo de país, nombre, idioma, si es oficial y calcula el número
            //de personas que lo hablan por cada país.
            $consulta = "select p.code codigoPais, p.name nombrePais, i.language idioma, i.isOfficial 
        oficial, round((p.population*i.percentage)/100) poblacion from country p inner join countrylanguage i 
        on p.code=i.countrycode order by p.code;";
            $resultado = $conexionBD->query($consulta);
            $registro = $resultado->fetch_object();
            if ($registro != null) {
                ?>
                <!--Genera la tabla de datos.-->
                <table>
                    <thead>
                        <tr>
                            <th>CountryTienda</th>
                            <th>Country</th>
                            <th>Language</th>
                            <th>IsOfficial</th>
                            <th>Population * Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //Genera cada una de las filas de la tabla.
                        while ($registro != null) {
                            echo "<tr><td>" . $registro->codigoPais . "</td>" .
                            "<td>" . $registro->nombrePais . "</td>" .
                            "<td>" . $registro->idioma . "</td>" .
                            "<td>" . $registro->oficial . "</td>" .
                            "<td>" . $registro->poblacion . "</td></tr>";
                            $registro = $resultado->fetch_object();
                        }
                    } else {
                        echo "<p class='mensaje'>No hay registros que mostrar.</p>";
                    }
                    $conexionBD->close();
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            //Mensaje de error de conexión.
            echo $errorConexion;
        }
        ?>
        <a class="centrado" href="index.php">Volver a inicio</a>
    </body>
</html>