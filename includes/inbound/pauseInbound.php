<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("inbound");
    QueryPHP_IncludeClasses("db");
    $InboundClass = new Inbound();

    $Anexo = $_POST["Anexo"];
    $Pausa = $_POST["Pausa"];

    $ToReturn = $InboundClass->pauseInbound($Anexo,$Pausa);
    echo json_encode($ToReturn);
?>