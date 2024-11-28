<?php 
    include_once("../functions/Functions.php");
	include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");

    $estrategia 	= $_POST["estrategia"];

    $sms = new sms();
    $fechas = $sms->fillFechas($estrategia);

    $ToReturn = "";

    if(is_array($fechas)){
        foreach($fechas as $fecha){
            $ToReturn .= "<option value='" . $fecha['id'] . "'>" . $fecha['fechahora'] . "</option>";
        }
    }

    echo $ToReturn;
?>