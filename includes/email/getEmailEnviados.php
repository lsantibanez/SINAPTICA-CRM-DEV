<?php 
    include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

    $start 	= $_POST["start"];
	$end 	= $_POST["end"];

    $email = new email();
    echo json_encode($email->enviados($start, $end));
?>