<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_notificacion.php");
    $ConfNotificacion = new ConfNotificacion();

    $TipoNotificaciones = $ConfNotificacion->getTipoNotificacionesCreate();
    echo json_encode($TipoNotificaciones);
?>