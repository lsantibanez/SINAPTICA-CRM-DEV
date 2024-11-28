<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("proveedoresdiscado");
    $CodigoFoco = $_POST["codigoFoco"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $ToReturn = $ProveedoresDiscadoClass->GetServerStatus($CodigoFoco);    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($ToReturn);
?>