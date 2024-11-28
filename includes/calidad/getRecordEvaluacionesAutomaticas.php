<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $NombreEjecutivo = $_POST["NombreEjecutivo"];
    $ConfTipoContacto = $_POST["ConfTipoContacto"];
    if(isset($_POST["idGrabaciones"])){
       $idGrabaciones = $_POST["idGrabaciones"]; 
    }else{
        $idGrabaciones = array();
    }
    $Mes = $_POST["Mes"];
    $diasSemana = $_POST["diasSemana"];
    $diasSemana = implode(",",$diasSemana);
    
   $Record = $CalidadClass->getRecordAutomaticas($NombreEjecutivo,$ConfTipoContacto,$Mes,$diasSemana,$idGrabaciones);
    echo json_encode($Record);
?>