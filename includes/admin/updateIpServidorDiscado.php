<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("proveedoresdiscado");
    $IpServidorDiscado = $_POST["IpServidorDiscado"];
    $IpServidorDiscadoAux = $_POST["IpServidorDiscadoAux"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $ToReturn = $ProveedoresDiscadoClass->updateIpServidorDiscado($IpServidorDiscado,$IpServidorDiscadoAux);
    echo json_encode($ToReturn);
?>