<?php
//include_once("../../includes/functions/Functions.php");
include_once("../../class/estrategia/estrategia.php");
//QueryPHP_IncludeClasses("db");
$objeto = new Estrategia();
$respuesta = $objeto->eliminarEstrategia($_POST['idEstrategia']);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($respuesta);
?>