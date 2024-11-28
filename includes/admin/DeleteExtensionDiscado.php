<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("proveedoresdiscado");
    QueryPHP_IncludeClasses("usuarios");
    $CodigoFoco = $_POST["codigoFoco"];
    $idUsuario = $_POST["idUsuario"];
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $ToReturn = $ProveedoresDiscadoClass->DeleteExtensionDiscado($CodigoFoco,$idUsuario);
    $UsuariosClass = new Usuarios();
    $ToReturn = $UsuariosClass->updateExtensionFoco($idUsuario,"0");
    //print_r($ToReturn);
    echo $ToReturn;
?>