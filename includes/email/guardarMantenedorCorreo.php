<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

	$ini = trim($_POST["inicio"]);
	$fin = trim($_POST["fin"]);

	$email = new email();
	echo json_encode($email->guardarMantenedorCorreo($ini, $fin));
?>