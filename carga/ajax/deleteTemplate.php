<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $id = $_POST["id"];

    $ToReturn = array();
    $ToReturn = $CargaClass->deleteTemplate($id);

    echo json_encode($ToReturn);

?>