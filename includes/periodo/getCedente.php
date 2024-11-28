<?php
    include("../../class/db/DB.php");
    include_once("../functions/Functions.php"); //incluir clases para la conexión a la bd
    include_once("../../class/periodo/periodoClass.php");
    QueryPHP_IncludeClasses("db");

    $periodo = new Periodo(); 
    
    $getCedente = $periodo->getCedente();
    
    json_encode($getCedente);
?>