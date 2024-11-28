<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Contenedores = $ReclutamientoClass->getTiposCampos();
    $ToReturn = "";
    foreach($Contenedores as $Contenedor){
        $ToReturn .= "<option value='".$Contenedor["id"]."'>".utf8_encode($Contenedor["Nombre"])."</option>";
    }
    echo $ToReturn;
?>