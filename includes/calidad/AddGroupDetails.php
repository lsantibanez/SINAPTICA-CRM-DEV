<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $CalidadClass->Id_Group = $_POST['Id_Group'];
    $Records = $_POST['Records'];
    $CalidadClass->deleteGroupDetails();
    $CalidadClass->addGroupDetails($Records);