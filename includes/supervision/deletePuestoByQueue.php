<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
include_once("../../discador/AGI/phpagi-asmanager.php");
include_once("../../includes/functions/Functions.php");
include_once("../../class/discador/discador.php");

    $Supervision = new Supervision();
    return $Supervision->deletePuestoByQueue(trim($_POST['puesto']), trim($_POST['queue']));
?>