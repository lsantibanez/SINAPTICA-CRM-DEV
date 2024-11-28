<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $ClassTareas = new Tareas();
    $idCola = $_POST["idCola"];
    $haveAsignaciones = $ClassTareas->haveAsignaciones($_SESSION["cedente"],$idCola);
    $ToReturn = array();
    $ToReturn["result"] = false;
    if($haveAsignaciones){
        $ToReturn["result"] = true;
        $ClassTareas->actualizarCola($idCola);
        $ClassTareas->updateAsignaciones("QR_".$_SESSION["cedente"]."_".$idCola);
    }else{
        $ToReturn["message"] = "La Estrategia seleccionada no posee asignaciones, debe construirla en el modulo de Asignación.";
    }
    echo json_encode($ToReturn);
?>