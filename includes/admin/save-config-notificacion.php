<?php
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

	$host 		= trim($_POST["host"]);
	$port 		= trim($_POST["port"]);
	$pass 		= trim($_POST["pass"]);
	$from 		= trim($_POST["from"]);
	$email 		= trim($_POST["email"]);
	$secure 	= trim($_POST["secure"]);
	$fromname 	= trim($_POST["fromname"]);
	$protocolo 	= trim($_POST["prot"]);

	$sms = new sms();
	echo json_encode($sms->configuracionNotificacion($protocolo, $secure, $host, $port, $email, $pass, $from, $fromname));
?>