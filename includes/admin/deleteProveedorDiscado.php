<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/proveedoresdiscado/proveedoresDiscado.php");
    include ("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");
    $CodigoFoco = $_POST["codigoFoco"];
    $idProveedor = $_POST["idProveedor"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $asm = new AGI_AsteriskManager();
    $ProveedoresDiscadoClass->deleteProveedor($idProveedor,$CodigoFoco);
?>