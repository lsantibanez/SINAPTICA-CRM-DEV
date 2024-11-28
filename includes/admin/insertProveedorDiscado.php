<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/proveedoresdiscado/proveedoresDiscado.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");
    $CodigoFoco = $_POST["codigoFoco"];
    $CodigoProveedor = $_POST["CodigoProveedor"];
    $NombreProveedor = $_POST["NombreProveedor"];
    $ProviderRules = $_POST["ProviderRules"];
    $DialPlan = $_POST["DialPlan"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $ProveedoresDiscadoClass->insertNewProveedor($CodigoFoco,$CodigoProveedor,$NombreProveedor,$ProviderRules,$DialPlan);
?>