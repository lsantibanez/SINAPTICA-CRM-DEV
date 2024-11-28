<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/ivr/ivr.php");
    QueryPHP_IncludeClasses("db");

	$ivr = new ivr();
	echo json_encode($ivr->getEstadistica($_POST['Estrategia']));
?>