<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Cierres = $CalidadClass->getCierresByMonthsAndYears();
    $ToReturn = "";
    foreach($Cierres as $Cierre){
        $Month = strlen($Cierre["Month"]) == 1 ? "0".$Cierre["Month"] : $Cierre["Month"];
        $ToReturn .= "<option value='".$Cierre["Year"].$Month."01'>".$Cierre["MonthText"]." ".$Cierre["Year"]."</option>";
    }
    echo $ToReturn;
?>