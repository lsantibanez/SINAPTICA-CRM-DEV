<?php
    include("../../includes/functions/Functions.php");
    include("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $ToReturn = $tareas->EliminarAsignacionesSinEstrategias();
    echo json_encode($ToReturn);
?>