<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/bienvenida/bienvenida.php");

$Bienvenida = new Bienvenida();
$cola = trim($_POST["idcola"]);
$estrategia = trim($_POST["estrategia"]);
$asignacion = trim($_POST["asignacion"]);


echo json_encode($Bienvenida->accesoDirectoColas2($cola, $estrategia, $asignacion));
?>    