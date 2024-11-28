<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

	$email = new email();

	$template_name 		= $_POST["tname"];
	$template 			= $_POST["template"];
	$template_asunto 	= isset($_POST["tasunto"]) ? $_POST["tasunto"] : "";

	echo $email->saveTemplate($template_name, $template_asunto, $template);
?>