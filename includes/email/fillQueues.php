<?php 
    include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

    $estrategia 	= $_POST["estrategia"];

    $sms = new sms();
    $queues = $sms->fillQueues($estrategia);

    $ToReturn = "";

    if(is_array($queues)){
        foreach($queues as $queue){
            $ToReturn .= "<option value='" . $queue["id"] . "'>" . $queue["asignacion"] . "</option>";
        }
    }

    echo $ToReturn;
?>