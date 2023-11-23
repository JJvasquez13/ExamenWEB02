<!DOCTYPE html>
<html lang="es">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Películas Más Solicitadas</title>
</head>

<body style="background-color: #FFFFCC; color: #800000">
    <img src="imagenes/encabe.png" alt="">

    <?php
    include_once("codigos/conexion.inc");

    $start_date = '2005-05-24';
    $end_date = '2006-12-31';

    // Obtener datos de las películas
    $auxSql = "SELECT
    f.film_id AS 'codigo_pelicula',
    f.title AS 'nombre_pelicula',
    f.description AS 'descripcion',
    GROUP_CONCAT(DISTINCT c.name ORDER BY c.name ASC SEPARATOR ', ') AS 'categorias',
    f.release_year AS 'anno'
FROM
    film f
    LEFT JOIN film_category fc ON f.film_id = fc.film_id
    LEFT JOIN category c ON fc.category_id = c.category_id
GROUP BY
    f.film_id, f.title, f.description, f.release_year
ORDER BY
    f.film_id";
    $resultadoPeliculas = mysqli_query($conex, $auxSql) or die(mysqli_error($conex));

    $peliculasArray = array();
    while ($filaPelicula = mysqli_fetch_array($resultadoPeliculas)) {
        $peliculasArray[] = $filaPelicula;
    }

    $auxSql = "SELECT
    f.film_id AS 'codigo_pelicula',
    CONCAT(a.first_name, ' ', a.last_name) AS 'nombre_actor'
FROM
    film f
    JOIN film_actor fa ON f.film_id = fa.film_id
    JOIN actor a ON fa.actor_id = a.actor_id
ORDER BY
    f.film_id, nombre_actor";
    $resultadoActores = mysqli_query($conex, $auxSql) or die(mysqli_error($conex));

    $actoresPorPelicula = array();
    while ($filaActor = mysqli_fetch_array($resultadoActores)) {
        $actoresPorPelicula[$filaActor['codigo_pelicula']][] = $filaActor['nombre_actor'];
    }

    echo "<table border='1'>";
    echo "<tr><th>Código de la Película</th><th>Nombre de la Película</th><th>Descripción</th><th>Categorías</th><th>Año de lanzamiento</th><th>Actores</th></tr>";

    foreach ($peliculasArray as $filaPelicula) {
        echo "<tr>";
        echo "<td>" . $filaPelicula["codigo_pelicula"] . "</td>";
        echo "<td>" . $filaPelicula["nombre_pelicula"] . "</td>";
        echo "<td>" . $filaPelicula["descripcion"] . "</td>";
        echo "<td>" . $filaPelicula["categorias"] . "</td>";
        echo "<td>" . $filaPelicula["anno"] . "</td>";

        // Lista de actores
        echo "<td>";
        if (isset($actoresPorPelicula[$filaPelicula['codigo_pelicula']])) {
            echo implode(', ', $actoresPorPelicula[$filaPelicula['codigo_pelicula']]);
        }
        echo "</td>";

        echo "</tr>";
    }

    echo "</table>";

    $xml = "<?xml version='1.0' encoding='utf-8' ?>";
    $xml .= "<?xml-stylesheet type='text/xsl' href='esilos/formatos2.xsl'?>";
    $xml .= "<peliculas_con_actores>";

    foreach ($peliculasArray as $filaPelicula) {
        $codigoPelicula = $filaPelicula['codigo_pelicula'];
        $xml .= "<pelicula>";
        $xml .= "<codigo_pelicula>$codigoPelicula</codigo_pelicula>";
        $xml .= "<nombre_pelicula>{$filaPelicula['nombre_pelicula']}</nombre_pelicula>";
        $xml .= "<descripcion>{$filaPelicula['descripcion']}</descripcion>";
        $xml .= "<categorias>{$filaPelicula['categorias']}</categorias>";
        $xml .= "<anno>{$filaPelicula['anno']}</anno>";

        $xml .= "<actores>";
        if (isset($actoresPorPelicula[$codigoPelicula])) {
            sort($actoresPorPelicula[$codigoPelicula]);
            foreach ($actoresPorPelicula[$codigoPelicula] as $nombreActor) {
                $xml .= "<actor>$nombreActor</actor>";
            }
        }
        $xml .= "</actores>";

        $xml .= "</pelicula>";
    }

    $xml .= "</peliculas_con_actores>";

    mysqli_free_result($resultadoPeliculas);
    mysqli_free_result($resultadoActores);
    mysqli_close($conex);

    $ruta = "xmls/peliculasConActores.xml";

    try {
        $archivo = fopen($ruta, "w+");
        fwrite($archivo, $xml);
        fclose($archivo);
    } catch (Exception $e) {
        echo "Error:.." . $e->getMessage();
    }
    ?>

    <a href="<?php echo $ruta; ?>">XML Generado</a><br />
    <a href="descargaxml.php">Descargar archivo XML</a>

</body>

</html>