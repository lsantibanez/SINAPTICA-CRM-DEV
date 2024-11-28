<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/proveedoresdiscado/proveedoresDiscado.php");
    QueryPHP_IncludeClasses("db");
    $CodigoFoco = $_POST["codigoFoco"];
    $idProveedor = $_POST["idProveedor"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $ProveedoresDiscadoClass->seleccionarProveedor($CodigoFoco,$idProveedor);
?>