<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
	require("../../includes/email/PHPMailer-master/class.phpmailer.php");
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
	
	if(isset($_POST["fono"])){
        $fono 	= $_POST["fono"];
        $mensaje	= $_POST["sms"];    
	}else{
		$fono = $argv[1];
        $mensaje = $argv[2];
	}

	$sms = new sms();
	echo $sms->envioSMS($fono, $mensaje);
?>