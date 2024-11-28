<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
	require("../../includes/email/PHPMailer-master/class.phpmailer.php");
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");

	$mensaje	= $_POST["sms"];    
	$cantidad 	= $_POST["cant"];
	$colores 	= $_POST["colores"];
	$template 	= $_POST["template"];
	$queue 		= trim($_POST["queue"]);
	
	if(!isset($_POST["telefonos"])){
		$rut = '';
		$telefonos = '';
	}else{
		$rut 	= $_POST["rut"];
		$telefonos 	= $_POST["telefonos"];
	}

	$sms = new sms();
	echo $sms->enviarSMS($mensaje, $cantidad, $colores, $queue, $template, $rut, $telefonos);
?>