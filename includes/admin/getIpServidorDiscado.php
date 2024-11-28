<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("proveedoresdiscado");
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $toReturn = $ProveedoresDiscadoClass->GetIpServidorDiscado();
    echo json_encode($toReturn);
?>