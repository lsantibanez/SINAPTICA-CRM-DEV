<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $Campo = $_POST["Campo"];
    $reporte = new Reporte();
    $Valores = $reporte->getValoresCampo($Campo);
    $ToReturn = '';
    foreach($Valores as $Valor){
        if($Valor != ""){
            $ToReturn .= "<option value='".$Valor."'>".$Valor."</option>";
        }
    }
    echo $ToReturn;
?>