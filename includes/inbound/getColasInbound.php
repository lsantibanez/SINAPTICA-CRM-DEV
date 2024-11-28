<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("inbound");
    QueryPHP_IncludeClasses("db");
    $InboundClass = new Inbound();

    $idMandante = $_POST["idMandante"];

    $Colas = $InboundClass->getColasInbound($idMandante);
    $ToReturn = "";
    foreach($Colas as $Cola){
        $Queue = $Cola["Queue"];
        $Descripcion = utf8_encode($Cola["Descripcion"]);
        $ToReturn .= "<option value='".$Queue."'>".$Descripcion."</option>";
    }
    echo $ToReturn;
?>