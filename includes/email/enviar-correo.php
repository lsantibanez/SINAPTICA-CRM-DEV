<?php 
	include_once("../functions/Functions.php");
	require("PHPMailer-master/class.phpmailer.php"); 
	require("PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");

    $email = new Email();

	$asignacion = $_POST["est"];
	$cantidad 	= $_POST["cant"];
	$asunto 	= $_POST["asunto"];
	$adjuntar 	= $_POST["adjuntar"];
	$template 	= $_POST["template"];

	echo $email->enviarEmail($asignacion, $cantidad, $asunto, $adjuntar, $template);
?>