<?php
    include("../../includes/functions/Functions.php");
    include("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Cola = $_POST['Cola'];
    $TipoTelefono = $_POST['TipoTelefono'];
    $Canales = $_POST['Canales'];
    $TlfxRut = $_POST['TlfxRut'];
    $Salida = $_POST['Salida'];
    $TipoCategorias = $_POST['TipoCategorias'];
    $ToReturn = $tareas->crearQueryDiscador($Cola,$TipoTelefono,$Canales,$TlfxRut,$Salida,$TipoCategorias);
    echo json_encode($ToReturn);
?>