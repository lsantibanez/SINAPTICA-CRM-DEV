<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_notificacion.php");
    $ConfNotificacion = new ConfNotificacion();
    
    $Cantidad = $_POST["Cantidad"];
    $TipoNotificacion = $_POST["TipoNotificacion"];
    $idNotificacion = $_POST["idNotificacion"];
    
    $ToReturn = $ConfNotificacion->ComprobarPrioridadNotificacionesUpdate($Cantidad,$TipoNotificacion,$idNotificacion);
    echo json_encode($ToReturn);
?>