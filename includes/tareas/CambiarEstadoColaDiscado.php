<?php
    ini_set('memory_limit','-1');
    include("../../includes/functions/Functions.php");
    include_once("../../class/discador/discador.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Cola = $_POST['Cola'];
    $Value = $_POST['Value'];
    $TipoDiscado = $_POST['TipoDiscado'];
    $PAbandono = $_POST['PAbandono'];
    $Provider = $_POST['Provider'];
    $ToReturn = $tareas->CambiarEstadoColaDiscado($Cola,$Value,$Provider,$TipoDiscado,$PAbandono);
    echo json_encode($ToReturn);
?>