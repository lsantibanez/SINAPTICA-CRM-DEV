<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/ivr/ivr.php");
    QueryPHP_IncludeClasses("db");

	$ini = trim($_POST["inicio"]);
	$fin = trim($_POST["fin"]);

	$ivr = new ivr();
	echo json_encode($ivr->guardarMantenedor($ini, $fin));
?>