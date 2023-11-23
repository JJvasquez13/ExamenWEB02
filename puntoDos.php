<!DOCTYPE html>
<html lang="es">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Películas Más Solicitadas</title>
</head>

<body style="background-color: #FFFFCC; color: #800000">
    <img src="imagenes/encabe.png" alt="">
    <h2>Películas Más Solicitadas</h2>

    <?php
    include_once("codigos/conexion.inc");

    $start_date = '2005-05-24';
    $end_date = '2006-12-31';

    $auxSql = "SELECT
        f.film_id AS 'codigo_pelicula',
        f.title AS 'nombre_pelicula',
        COUNT(r.rental_id) AS 'veces_alquilada',
        SUM(p.amount) AS 'total_generado'
    FROM
        rental r
        JOIN payment p ON r.rental_id = p.rental_id
        JOIN inventory i ON r.inventory_id = i.inventory_id
        JOIN film f ON i.film_id = f.film_id
    WHERE
        r.rental_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY
        f.film_id, f.title
    ORDER BY
        total_generado DESC
    LIMIT 10";

    $resultadoPelis = mysqli_query($conex, $auxSql) or die(mysqli_error($conex));

    $resultadosArray = array();
    while ($filaPeli = mysqli_fetch_array($resultadoPelis)) {
        $resultadosArray[] = $filaPeli;
    }

    echo "<table border='1'>";
    echo "<tr><th>Código de la Película</th><th>Nombre de la Película</th><th>Veces Alquilada</th><th>Total Generado</th></tr>";

    foreach ($resultadosArray as $filaPeli) {
        echo "<tr>";
        echo "<td>" . $filaPeli["codigo_pelicula"] . "</td>";
        echo "<td>" . $filaPeli["nombre_pelicula"] . "</td>";
        echo "<td>" . $filaPeli["veces_alquilada"] . "</td>";
        echo "<td>" . $filaPeli["total_generado"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Genera el XML
    $xml = "<?xml version='1.0' encoding='utf-8' ?>";
    $xml .= "<?xml-stylesheet type='text/xsl' href='esilos/formatos.xsl'?>";
    $xml .= "<peliculas_solicitadas>";

    $granTotalGenerado = 0;

    foreach ($resultadosArray as $filaPeli) {
        $xml .= "<pelicula>";
        $xml .= "<codigo_pelicula>" . $filaPeli["codigo_pelicula"] . "</codigo_pelicula>";
        $xml .= "<nombre_pelicula>" . $filaPeli["nombre_pelicula"] . "</nombre_pelicula>";
        $xml .= "<veces_alquilada>" . $filaPeli["veces_alquilada"] . "</veces_alquilada>";
        $xml .= "<total_generado>" . $filaPeli["total_generado"] . "</total_generado>";
        $xml .= "</pelicula>";

        $granTotalGenerado += $filaPeli["total_generado"];
    }

    $xml .= "<gran_total_generado>" . $granTotalGenerado . "</gran_total_generado>";
    $xml .= "</peliculas_solicitadas>";

    mysqli_free_result($resultadoPelis);
    mysqli_close($conex);

    $ruta = "xmls/peliculasSolicitadas.xml";

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