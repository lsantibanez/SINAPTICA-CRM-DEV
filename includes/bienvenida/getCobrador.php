<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/bienvenida/bienvenida.php");

$Bienvenida = new Bienvenida();
$response = $Bienvenida->getCobrador($_POST["nivel"],$_POST['cobrador']);


?>    