<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

	$id   = $_POST["id"];
	$cant = $_POST["cant"];

	$sms = new sms();
	echo json_encode($sms->actualizarCantidad($id, $cant));
?>