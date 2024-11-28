<?php  
	include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");
	
    $sms = new sms();
	$queue = trim($_POST["queue"]);
	$colores = ($_POST["colores"] != "") ? implode(",", $_POST["colores"]) : "";
	
    echo $sms->infoEstrategia($colores, $queue);
?>