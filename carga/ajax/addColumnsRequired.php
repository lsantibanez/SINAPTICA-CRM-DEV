<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $Tabla = $_POST["Tabla"];
    $idSheet = $_POST["idSheet"];
    $id_template = $_POST["id_template"];

    $ToReturn = array();
    $ToReturn = $CargaClass->addColumnsRequired($Tabla,$idSheet,$id_template);

    echo json_encode($ToReturn);

?>