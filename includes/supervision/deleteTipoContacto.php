<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
include_once("../../discador/AGI/phpagi-asmanager.php");
include_once("../../includes/functions/Functions.php");
include_once("../../class/discador/discador.php");

    $Supervision = new Supervision();

    $contacto = trim($_POST['tipoContacto']);
    $ratio = trim($_POST['ratio']);

    echo json_encode($Supervision->deleteTipoContacto($contacto, $ratio));
?>