<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Cedentes = $CalidadClass->getCedentesMandante();
    echo json_encode($Cedentes);
?>