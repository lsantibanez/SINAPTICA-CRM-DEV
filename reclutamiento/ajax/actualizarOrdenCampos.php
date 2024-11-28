<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Contenedor = $_POST["Contenedor"];
    $ArrayCampos = $_POST["ArrayCampos"];

    $ReclutamientoClass->deleteOrdenCampos($Contenedor);
    $ReclutamientoClass->agregarOrdenCampos($ArrayCampos);
?>