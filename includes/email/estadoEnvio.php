<?php  
	include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

	$email = new email();
	$id_envio = $_POST["id_envio"];

	echo $email->estadoEnvio($id_envio);
?>