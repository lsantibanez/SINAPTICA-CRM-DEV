<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("personal");
    $CalidadClass = new Calidad();
    $PersonalClass = new Personal();

    $PersonalClass->Username = $_POST['PersonalUsername'];
    $CalidadClass->Id_Grabacion = $_POST['RecordId'];
    if($CalidadClass->GrabacionHaveEvaluation($CalidadClass->Id_Grabacion)){
        $CalidadClass->Id_Personal = $CalidadClass->GetIdPersonalFromEvaluacionGrabacion($CalidadClass->Id_Grabacion);
    }else{
        $CalidadClass->Id_Personal = $PersonalClass->getPersonalIDFromUsername();
    }
    $CalidadClass->Evaluacion_Final = 0;
    $ErrorCritico = $_POST["ErrorCritico"];
    $idErrorCritico = $_POST["idErrorCritico"];
    $ObservacionEvaluacion = $_POST["ObservacionEvaluacion"];
    
    $Return = $CalidadClass->AddEvaluation($ErrorCritico,$idErrorCritico,$ObservacionEvaluacion);
    if($Return != false){
        echo $Return;
    }else{
        echo "0";
    }
?>