<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/estrategia/config_tablas.php");
    QueryPHP_IncludeClasses("db");
    $ConfigTablas = new ConfigTablas();
    $campos = $ConfigTablas->getFiltrar_campos(2);
    $ToReturn = "";
    foreach($campos as $campo){
        if($campo["columna"] != ""){
            $ToReturn .= "<option value='".$campo["columna"]."'>".$campo["columna"]."</option>";
        }
    }
    echo $ToReturn;
?>