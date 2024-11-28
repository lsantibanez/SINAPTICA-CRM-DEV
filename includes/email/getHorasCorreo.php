<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

	 $email = new email();
	 echo json_encode($email->getHorasCorreo());
?>