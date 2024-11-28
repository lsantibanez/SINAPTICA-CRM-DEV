<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Meses = $SpeechClass->getMonthsFromTranscriptions();
    $ToReturn = "";

    $Months = array();
    $Months[1] = "Enero";
    $Months[2] = "Febrero";
    $Months[3] = "Marzo";
    $Months[4] = "Abril";
    $Months[5] = "Mayo";
    $Months[6] = "Junio";
    $Months[7] = "Julio";
    $Months[8] = "Agosto";
    $Months[9] = "Septiembre";
    $Months[10] = "Octubre";
    $Months[11] = "Noviembre";
    $Months[12] = "Diciembre";

    foreach($Meses as $Mes){
        $ToReturn .= "<option value='".$Mes["Year"].$Mes["Month"]."01'>".$Months[$Mes["Month"]]." ".$Mes["Year"]."</option>";
    }
    echo $ToReturn;
?>