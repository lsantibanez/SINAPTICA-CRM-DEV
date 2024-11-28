<?php 
    include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

    $start 	= $_POST["start"];
	$end 	= $_POST["end"];

    $sms = new sms();
    echo json_encode($sms->getSMSEnviados($start, $end));
?>