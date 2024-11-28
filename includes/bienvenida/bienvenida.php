<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/bienvenida/bienvenida.php");

$Bienvenida = new Bienvenida();
$ToReturn = $Bienvenida->CalendarioAgenda($_POST['id_cola']);
echo json_encode($ToReturn);
?>    