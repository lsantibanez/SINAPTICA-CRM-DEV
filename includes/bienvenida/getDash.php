<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/bienvenida/bienvenida.php");

$Bienvenida = new Bienvenida();
$toReturn = $Bienvenida->getDash($_POST["idUser"],$_POST["cedente"]);
?>    