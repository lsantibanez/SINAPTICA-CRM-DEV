<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

	$ini = trim($_POST["inicio"]);
	$fin = trim($_POST["fin"]);
	$cost = trim($_POST["cost"]);

	$sms = new sms();
	echo json_encode($sms->guardarMantenedor($ini, $fin, $cost));
?>