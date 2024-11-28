<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/bienvenida/bienvenida.php");

$Bienvenida = new Bienvenida();
$Rut = trim($_POST["Rut"]);


echo json_encode($Bienvenida->accesoDirectoRut($Rut));
?>    