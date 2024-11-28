<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idGrabacion = $_POST['idGrabacion'];
    $ToReturn = $CalidadClass->getObjeciones($idGrabacion);
    echo json_encode($ToReturn);
?>