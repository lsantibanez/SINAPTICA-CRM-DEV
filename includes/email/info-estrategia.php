<?php  
	include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

	$email = new email();
	$table 	= $_POST["table"];

	echo json_encode($email->getEstrategia($table));
?>