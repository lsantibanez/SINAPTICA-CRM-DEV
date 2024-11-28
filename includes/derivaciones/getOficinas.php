<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $idMandante = $_POST["idMandante"];

    $Oficinas = $DerivacionesClass->getOficinas($idMandante);
    echo json_encode($Oficinas);
?>