<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

	$id   = $_POST["id"];
	$cant = $_POST["cant"];

	$email = new email();
	echo json_encode($email->actualizarCantidadCorreos($id, $cant));
?>