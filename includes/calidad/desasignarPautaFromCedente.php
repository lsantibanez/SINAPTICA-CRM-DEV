<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idContenedorCedente = $_POST["idContenedorCedente"];
    $Pautas = $CalidadClass->desasignarPautaFromCedente($idContenedorCedente);
    echo json_encode($Pautas);
?>