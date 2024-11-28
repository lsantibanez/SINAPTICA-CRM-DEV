<?php
include_once '../../class/Asignaciones.php';
$asignaciones = new Asignaciones();
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: application/json; charset=utf-8');
echo json_encode($asignaciones->assigData($data['load']));
?>