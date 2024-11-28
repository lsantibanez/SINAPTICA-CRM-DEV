<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/proveedoresdiscado/proveedoresDiscado.php");
    QueryPHP_IncludeClasses("db");
    $idProveedor = $_POST["idProveedor"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $Proveedor = $ProveedoresDiscadoClass->getProveedor($idProveedor);
    //echo $Proveedor;
?>