<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Contenedor = $_POST["Contenedor"];

    $Campos = $ReclutamientoClass->getCamposSinOrden($Contenedor);
    $ToReturn = "";
    foreach($Campos as $Campo){
        $ToReturn .= "<a class='list-group-item Field' id='".$Campo["id"]."' Codigo='".$Campo["Codigo"]."' Tipo='".utf8_encode($Campo["Tipo"])."' style='text-align: center; cursor: pointer;'><strong>".$Campo["Codigo"]."</strong><br>".utf8_encode($Campo["Tipo"])."</a>";
    }
    echo $ToReturn;
?>