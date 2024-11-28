<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    echo json_encode($CargaClass->FindFinishedJavaProcess());
?>