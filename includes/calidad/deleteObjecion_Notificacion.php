<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idObjecion = $_POST["idObjecion"];
    $ToReturn = $CalidadClass->marcarNoVisibleObjecion($idObjecion);
    echo json_encode($ToReturn);
?>