<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $mandante   = isset($_POST['mandante']) ? trim($_POST['mandante']) : "";
    $cedente    = isset($_POST['cedente']) ? trim($_POST['cedente']) : "";
    $estrategia = isset($_POST['estrategia']) ? trim($_POST['estrategia']) : "";
    $cola       = isset($_POST['queue']) ? trim($_POST['queue']) : "";
    $asignacion = isset($_POST['asignacion']) ? trim($_POST['asignacion']) : "";

    echo json_encode($Supervision->getPuestos($mandante, $cedente, $estrategia, $cola, $asignacion));
?>    