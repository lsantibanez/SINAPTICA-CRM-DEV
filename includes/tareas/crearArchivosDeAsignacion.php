<?php
    //ini_set('memory_limit', '-1');
    include("../../includes/functions/Functions.php");
    //require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
    
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $idCola = $_GET['idCola'];
    $Tabla = $_GET['Tabla'];
    $Tipo = $_GET["Tipo"];
    $File = $tareas->getAsignacionesArchivos($idCola,$Tipo,$Tabla);
    echo $File;
?>