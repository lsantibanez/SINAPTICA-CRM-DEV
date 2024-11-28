<?php 
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();

    $canal = trim($_POST['canal']);
    $tlfxrut = trim($_POST['tlfxrut']);
    $queue = trim($_POST['queue']);

    $ToReturn = $tareas->actualizarCanales($canal, $tlfxrut, $queue);
    echo json_encode($ToReturn);
?>