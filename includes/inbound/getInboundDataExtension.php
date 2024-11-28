<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("inbound");
    QueryPHP_IncludeClasses("db");
    $InboundClass = new Inbound();

    $Anexo = $_POST["Anexo"];

    $ToReturn = $InboundClass->getInboundDataExtension($Anexo);
    echo json_encode($ToReturn);
?>