<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $mandante = trim($_POST['mandante']);
    $estatus = trim($_POST['estatus']);
    $cedente = trim($_POST['cedente']);
    $estrategia = trim($_POST['estrategia']);
    $queue = trim($_POST['queue']);
    $asignacion = trim($_POST['asignacion']);

    echo json_encode($Supervision->getPuestosTrabajo($mandante, $estatus, $cedente, $estrategia, $queue, $asignacion));
?>