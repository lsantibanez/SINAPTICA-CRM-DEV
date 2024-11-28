<?php 
	require("PHPMailer-master/class.phpmailer.php"); 
	require("PHPMailer-master/class.smtp.php"); 
	include("../../class/email/email.php");
	include("../../class/email/opciones.php");
	if(isset($_POST['to'])){
		$destinatario 	= $_POST['to'];
		$contenido 		= $_POST['html'];
		$asunto 		= $_POST['asunto'];
		$cedente 		= $_POST['cedente'];
	}else{
		$destinatario 	= $argv[1];
		$contenido 		= $argv[2];
		$asunto 		= $argv[3];
		$cedente 		= $argv[4];
	}

	$n = strpos($destinatario, ',');

	$email = new Email;

	if($n > 0){
		$destinatario = explode(',', $destinatario);
	} 

	$result = $email->SendMail($contenido, $asunto, $destinatario,false,$cedente,0);

	echo $result; 
?>