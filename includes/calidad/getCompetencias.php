<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Competencias = $CalidadClass->getCompetencias();
    $ToReturn = "";
    foreach($Competencias as $Competencia){
        $ToReturn .= "<option value='".$Competencia["id"]."'>".utf8_encode($Competencia["Resumen"])."</option>";
    }
    echo $ToReturn;
?>