<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idCedente = $_POST["idCedente"];
    $Pautas = $CalidadClass->getPautasWhereNotInCedente($idCedente);
    $ToReturn = "";
    foreach($Pautas as $Pauta){
        $ToReturn .= "<option value='".$Pauta["id"]."'>".utf8_encode($Pauta["nombreContenedor"])."</option>";
    }
    echo $ToReturn;
?>