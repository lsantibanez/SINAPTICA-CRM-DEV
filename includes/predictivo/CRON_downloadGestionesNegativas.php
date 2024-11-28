<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/discador/discador.php");
    QueryPHP_IncludeClasses("db");
    //QueryPHP_IncludeClasses("discador");
    if(isset($_POST["Fecha"])){
        $Fecha = date("Ymd",strtotime($_POST["Fecha"]));
    }else{
        $Fecha = date("Ymd");
    }
    $DiscadorClass = new Discador();
    $Gestiones = $DiscadorClass->descargarGestionesNegativas($Fecha);
    $ToReturn = array();
    /*if($Gestiones){
        $ToReturn["result"] = true;
        $ToReturn["message"] = "Gestiones guardadas satisfactoriamente";
    }else{
        $ToReturn["result"] = false;
        $ToReturn["message"] = "Hubo un problema al descargar las gestiones, comuniquese con soporte tecnico.";
    }*/
    
    if(count($Gestiones) > 0){
        $ToReturn = $DiscadorClass->insertGestionesNegativasFoco($Gestiones);
    }else{
        $ToReturn["result"] = false;
        $ToReturn["message"] = "No se encontraron gestiones para descargar";
    }
    echo json_encode($ToReturn);
?>    