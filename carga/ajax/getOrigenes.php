<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $Origenes = $CargaClass->getOrigenes();
    $ToReturn = '';
    foreach($Origenes as $Origen){
        $ToReturn .= "<option value='".$Origen["codigo"]."'>".$Origen["nombre"]."</option>";
    }
    echo $ToReturn;
?>