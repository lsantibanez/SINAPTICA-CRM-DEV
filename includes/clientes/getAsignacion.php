<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/clientes/clientes.php");

$clientes = new Clientes();
$response = $clientes->getAsignacion($_POST["idUsuario"]);


?>    