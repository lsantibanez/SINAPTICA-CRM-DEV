<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/gestiones/cerrar.php");
    QueryPHP_IncludeClasses("db");
    $GestionClass= new Cerrar();
    echo json_encode($GestionClass->search($_SESSION['cedente']));
?>