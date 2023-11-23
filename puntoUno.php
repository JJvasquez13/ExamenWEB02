<!DOCTYPE html>
<html lang="es">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Clientes Activos en Almacenes de Alquiler de Películas</title>
</head>

<body style="background-color: #FFFFCC; color: #800000">
    <img src="imagenes/encabe.png" alt="">
    <h2>Clientes Activos en Almacenes de Alquiler de Películas</h2>

    <?php
    include_once("codigos/conexion.inc");

    $auxSql = "SELECT
    a.address_id AS warehouse_code,
    a.address AS warehouse_name,
    c.customer_id AS customer_code,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    LOWER(c.email) AS customer_email
        FROM
            customer c
        JOIN
            address a ON c.address_id = a.address_id
        WHERE
            c.active = 1
        ORDER BY
            customer_name";

    $resultadoClientes = mysqli_query($conex, $auxSql) or die(mysqli_error($conex));

    $clientesArray = array();
    while ($filaCliente = mysqli_fetch_array($resultadoClientes)) {
        $clientesArray[] = $filaCliente;
    }

    echo "<table border='1'>";
    echo "<tr><th>Código del Almacén</th><th>Nombre del Almacén</th><th>Código del Cliente</th><th>Nombre del Cliente</th><th>Email del Cliente</th></tr>";

    foreach ($clientesArray as $filaCliente) {
        echo "<tr>";
        echo "<td>" . $filaCliente["warehouse_code"] . "</td>";
        echo "<td>" . $filaCliente["warehouse_name"] . "</td>";
        echo "<td>" . $filaCliente["customer_code"] . "</td>";
        echo "<td>" . $filaCliente["customer_name"] . "</td>";
        echo "<td>" . $filaCliente["customer_email"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    $xml = "<?xml version='1.0' encoding='utf-8' ?>";
    $xml .= "<informacion>";

    foreach ($clientesArray as $filaCliente) {
        $xml .= "<cliente>";
        $xml .= "<codigo_almacen>" . $filaCliente["warehouse_code"] . "</codigo_almacen>";
        $xml .= "<nombre_almacen>" . $filaCliente["warehouse_name"] . "</nombre_almacen>";
        $xml .= "<codigo_cliente>" . $filaCliente["customer_code"] . "</codigo_cliente>";
        $xml .= "<nombre_cliente>" . $filaCliente["customer_name"] . "</nombre_cliente>";
        $xml .= "<email_cliente>" . $filaCliente["customer_email"] . "</email_cliente>";
        $xml .= "</cliente>";
    }

    $xml .= "</informacion>";

    mysqli_free_result($resultadoClientes);
    mysqli_close($conex);

    $ruta = "xmls/clientesActivos.xml";

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