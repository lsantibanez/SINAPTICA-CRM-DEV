<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = array();
    $ToReturn = $CargaClass->getSheets($_POST["id"]);

    echo json_encode($ToReturn);

?>