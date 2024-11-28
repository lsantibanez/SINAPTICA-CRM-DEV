<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("inbound");
    QueryPHP_IncludeClasses("db");
    $InboundClass = new Inbound();

    $ToReturn = $InboundClass->insertPersonaInbound($_POST["Rut"],$_POST["Nombre"],$_POST["Telefono"],$_POST["Correo"],$_POST["Direccion"],$_POST["Comuna"],$_POST["Ciudad"]);
    echo json_encode($ToReturn);
?>