<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idOpcionAfirmacion = $_POST['idOpcionAfirmacion'];
    $Nombre = $_POST['Nombre'];
    $Valor = $_POST['Valor'];
    $DescripcionCaracteristica = $_POST['DescripcionCaracteristica'];
    $ToReturn = $CalidadClass->UpdateOpcionAfirmacion($idOpcionAfirmacion,$Nombre,$Valor,$DescripcionCaracteristica);
    echo utf8_encode(json_encode($ToReturn));
?>