<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/trabajador/trabajador.php");
    QueryPHP_IncludeClasses("db");
    $trabajador = new Trabajador(); 
    echo $trabajador->modificaTrabajador($_POST);
?>