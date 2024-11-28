<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("personal");
    QueryPHP_IncludeClasses("grupos");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Cola = $tareas->verColaDiscador($_POST['id']);
    echo json_encode($Cola);
?>