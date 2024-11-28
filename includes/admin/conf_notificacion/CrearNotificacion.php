<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_notificacion.php");
    $ConfNotificacion = new ConfNotificacion();
    
    $Cantidad = $_POST["Cantidad"];
    $TipoNotificacion = $_POST["TipoNotificacion"];
    
    $ToReturn = $ConfNotificacion->CrearNotificacion($Cantidad,$TipoNotificacion);
    echo json_encode($ToReturn);
?>