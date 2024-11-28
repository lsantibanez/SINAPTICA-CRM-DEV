<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("personal");

    $CalidadClass = new Calidad();
    $PersonalClass = new Personal();

    $idGrabacion = $_POST['idGrabacion'];
    $Username = $_POST["Username"];
    $Objecion = $_POST["Objecion"];
    $Tipo = $_POST["Tipo"];
    $PersonalClass->Username = $Username;
    $idPersonal = $PersonalClass->getPersonalIDFromUsername();

    $ToReturn = $CalidadClass->saveObjecion($idGrabacion,$idPersonal,$Objecion,$Tipo);
    echo json_encode($ToReturn);
?>