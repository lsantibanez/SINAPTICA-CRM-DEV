<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $idTipoContacto = $_POST["idTipoContacto"];

    $ToReturn = $CalidadClass->deleteTipoContactoEvaluacionesAutomaticas($idTipoContacto);
    echo json_encode($ToReturn);
?>