<?php
include_once '../../class/Asignaciones.php';
$asignaciones = new Asignaciones();
header('Content-type: application/json; charset=utf-8');
echo json_encode($asignaciones->validatedata());
?>