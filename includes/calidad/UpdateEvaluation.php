<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $CalidadClass->Id_Grabacion = $_POST['RecordId'];
    $CalidadClass->Evaluacion_Final = 0;

    $ErrorCritico = $_POST["ErrorCritico"];
    $idErrorCritico = $_POST["idErrorCritico"];
    $ObservacionEvaluacion = $_POST["ObservacionEvaluacion"];
    
    $Return = $CalidadClass->updateEvaluation($ErrorCritico,$idErrorCritico,$ObservacionEvaluacion);
    echo $Return;
?>