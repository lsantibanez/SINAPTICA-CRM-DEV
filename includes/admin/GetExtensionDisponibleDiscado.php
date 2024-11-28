<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("proveedoresdiscado");
    QueryPHP_IncludeClasses("usuarios");
    $CodigoFoco = $_POST["codigoFoco"];
    $Username = $_POST["Username"];
    $UsuariosClass = new Usuarios();
    $idUsuario = $UsuariosClass->getUserIdByUsername($Username);
    $ProveedoresDiscadoClass = new proveedoresDiscado();
    $ToReturn = $ProveedoresDiscadoClass->GetExtensionDisponibleDiscado($CodigoFoco,$Username,$idUsuario);
    $ToReturn = json_decode($ToReturn);
    //print_r($ToReturn);
    $Bool = false;
    $Extension = "";
    foreach($ToReturn as $key => $Return){
        switch($key){
            case "result":
                $Bool = $Return;
            break;
            case "Extension":
                $Extension = $Return;
            break;
        }
    }
    if($Bool){
        $ToReturn = $UsuariosClass->updateExtensionFoco($idUsuario,$Extension);
    }
    echo json_encode($ToReturn);
?>