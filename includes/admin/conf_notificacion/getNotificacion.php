<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_notificacion.php");
    $ConfNotificacion = new ConfNotificacion();

    $idNotificacion = $_POST['idNotificacion'];

    $Notificacion = $ConfNotificacion->getNotificacion($idNotificacion);
    echo json_encode($Notificacion);
?>