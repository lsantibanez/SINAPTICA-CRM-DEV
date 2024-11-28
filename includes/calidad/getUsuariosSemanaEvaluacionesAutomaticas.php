<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $FechaInicio = $_POST["FechaInicio"];
    $FechaFin = $_POST["FechaFin"];

    $ToReturn = $CalidadClass->getUsuariosSemanaEvaluacionesAutomaticas($FechaInicio,$FechaFin);
    /* $ToReturn = "";
    foreach($Usuarios as $Usuario){
        $idEjecutivo = $Usuario["idEjecutivo"];
        $nombreEjecutivo = $Usuario["nombreEjecutivo"];
        $cantidadEvaluaciones = $Usuario["cantidadEvaluaciones"];
        $ToReturn .= "<option value='".$idEjecutivo."'>".$nombreEjecutivo." - Evaluaciones: ".$cantidadEvaluaciones."</option>";
    }
    echo $ToReturn; */
    echo json_encode($ToReturn);
?>