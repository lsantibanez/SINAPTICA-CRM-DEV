<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

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

    $Periodos = $CalidadClass->getPeriodosEvaluacionesSemanales();
    
    $ToReturn = "";
    foreach($Periodos as $Periodo){
        $Mes = $Periodo["Mes"];
        $MesDate = $Mes < 10 ? "0".$Mes : $Mes;
        $Ano = $Periodo["Ano"];
        $ToReturn .= "<option value='2019".$MesDate."01'>".$Months[$Mes]." ".$Ano."</option>";
    }
    echo $ToReturn;
?>