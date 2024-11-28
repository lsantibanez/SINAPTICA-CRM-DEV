<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_notificacion.php");
    $ConfNotificacion = new ConfNotificacion();

    $Notificaciones = $ConfNotificacion->getNotificacionTableList();
    $Array = array();
    if($Notificaciones){
        foreach($Notificaciones as $Notificacion){
            $ArrayTmp = array();
            if($Notificacion['tipo_notificacion'] == 1){
                $TipoNotificacion = 'Alta';
            }else if($Notificacion['tipo_notificacion'] == 2){
                $TipoNotificacion = 'Media';
            }else{
                $TipoNotificacion = 'Baja';
            }
            $ArrayTmp["Cantidad"] = $Notificacion["cantidad_horas"];
            $ArrayTmp["TipoNotificacion"] = $TipoNotificacion;
            $ArrayTmp["Accion"] = $Notificacion["id"];
            array_push($Array,$ArrayTmp);
        }
    }
    echo json_encode($Array);
?>