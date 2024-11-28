<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

	$pwd = trim($_POST["pwd"]);
	$user = trim($_POST["user"]);
	$saldo = trim($_POST["saldo"]);
	$envio = trim($_POST["envio"]);
	$consulta = trim($_POST["consult"]);
	
	$sms = new sms();
	echo json_encode($sms->guardarURLSMS($pwd, $user, $envio, $consulta, $saldo));
?>