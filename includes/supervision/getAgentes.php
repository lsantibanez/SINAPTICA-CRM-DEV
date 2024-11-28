<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
    $mandante = trim($_POST['mandante']);
    $cedente = trim($_POST['cedente']);
    $estrategia = trim($_POST['estrategia']);
    $cola = trim($_POST['queue']);
    $asignacion = trim($_POST['asignacion']);

    echo json_encode($Supervision->getAgentes($mandante, $cedente, $estrategia, $cola, $asignacion));
?>    