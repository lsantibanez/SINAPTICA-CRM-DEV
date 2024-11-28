<?php
require __DIR__.'/../../class/admin/Tablas.php';
$tabla = new Tablas();
$listaTablas = $tabla->getTablas();
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($listaTablas);
?>