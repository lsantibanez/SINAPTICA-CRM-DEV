<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idGrabacion = $_POST["idGrabacion"];
    
    $idCedente = $CalidadClass->getCedenteFromGrabacion($idGrabacion);

    $Contacto = $CalidadClass->getIdContactoFromGrabacion($idGrabacion);
    $idPauta = "";
    if($Contacto["result"]){
        $idContacto = $Contacto["value"];
        $Pauta = $CalidadClass->getPautaFromTipoContacto($idCedente,$idContacto);
        if($Pauta["result"]){
            $idPauta = $Pauta["value"];
        }
    }

    $Evaluations = $CalidadClass->getEvaluationTemplate($_SESSION["mandante"],$idCedente,$idPauta);
    echo utf8_encode(json_encode($Evaluations));
?>