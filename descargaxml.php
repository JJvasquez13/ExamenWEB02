<?php
header('Content-disposition: attachment; filename=clientesActivos.xml');
header('Content-type: application/octet-stream .xml; charset=utf-8');

//obtiene raiz del sitio
$ruta = $_SERVER["DOCUMENT_ROOT"] . "C:/xampp/htdocs/Examen02/xmls/clientesActivos.xml";

readfile($ruta);
