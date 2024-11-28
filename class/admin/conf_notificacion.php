<?php
    class ConfNotificacion{
        function getNotificacionTableList(){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            control_notificaciones
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'";
            $Notificaciones = $db->select($query);
            return $Notificaciones;
        }
        function getTipoNotificacionesCreate(){
            $db = new Db();
            $ToReturn = array();
            $query = "  SELECT DISTINCT 
                            tipo_notificacion
                        FROM 
                            control_notificaciones
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'";
            $control_notificaciones = $db->select($query);
            $tipos_notificaciones_create = array();
            foreach($control_notificaciones as $notificacion){
                array_push($tipos_notificaciones_create,$notificacion['tipo_notificacion']);
            }
            $tipos_notificaciones = array(1,2,3);
            foreach($tipos_notificaciones as $tipo_notificacion){
                if(!in_array($tipo_notificacion,$tipos_notificaciones_create)){
                    $Array = array();
                    $Array['id'] = $tipo_notificacion;
                    if($tipo_notificacion == 1){
                        $Array['nombre'] = 'Alta';
                    }else if($tipo_notificacion == 2){
                        $Array['nombre'] = 'Media';
                    }else{
                        $Array['nombre'] = 'Baja';
                    }
                    array_push($ToReturn,$Array);
                }
            }
			return $ToReturn;
        }
        function ComprobarPrioridadNotificacionesCreate($Cantidad,$TipoNotificacion){
            $ToReturn["result"] = false;
            $ToReturn["message"] = 'Error al crear el registro';
            $db = new DB();
            if($TipoNotificacion == 1){
                $query = "  SELECT COUNT(*) AS Cantidad FROM control_notificaciones WHERE id_cedente = '".$_SESSION['cedente']."' AND cantidad_horas < '".$Cantidad."'";
                $message = 'El registro no puede ser creado ya que la cantidad de horas debe ser menor a la prioridad media';
            }else if($TipoNotificacion == 2){
                $query = "  SELECT COUNT(*) AS Cantidad FROM control_notificaciones WHERE id_cedente = '".$_SESSION['cedente']."' AND ((tipo_notificacion = 1 AND cantidad_horas > '".$Cantidad."') OR (tipo_notificacion = 3 AND cantidad_horas < '".$Cantidad."'))";
                $message = 'El registro no puede ser creado ya que la cantidad de horas debe ser menor a la prioridad baja y mayor a la prioridad alta';
            }else{
                $query = "  SELECT COUNT(*) AS Cantidad FROM control_notificaciones WHERE id_cedente = '".$_SESSION['cedente']."' AND cantidad_horas > '".$Cantidad."'";
                $message = 'El registro no puede ser creado ya que la cantidad de horas debe ser mayor a la prioridad media';
            }
            $Select = $db->select($query);
            if($Select[0]['Cantidad'] == 0){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["message"] = $message;
            }
            return $ToReturn;
        }
        function CrearNotificacion($Cantidad,$TipoNotificacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $query = "INSERT INTO control_notificaciones (cantidad_horas,tipo_notificacion,id_cedente) VALUES ('".$Cantidad."','".$TipoNotificacion."','".$_SESSION['cedente']."')";
            $Insert = $db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getNotificacion($idNotificacion){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            control_notificaciones
                        WHERE 
                            id = '".$idNotificacion."'";
            $Notificacion = $db->select($query);
            return $Notificacion[0];
        }
        function getTipoNotificacionesUpdate($idNotificacion){
            $db = new Db();
            $ToReturn = array();
            $query = "  SELECT DISTINCT 
                            tipo_notificacion
                        FROM 
                            control_notificaciones
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'";
            $control_notificaciones = $db->select($query);
            $tipos_notificaciones_update = array();
            foreach($control_notificaciones as $notificacion){
                array_push($tipos_notificaciones_update,$notificacion['tipo_notificacion']);
            }
            $tipos_notificaciones = array(1,2,3);
            foreach($tipos_notificaciones as $tipo_notificacion){
                if(!in_array($tipo_notificacion,$tipos_notificaciones_update) || $tipo_notificacion == $idNotificacion){
                    $Array = array();
                    $Array['id'] = $tipo_notificacion;
                    if($tipo_notificacion == 1){
                        $Array['nombre'] = 'Alta';
                    }else if($tipo_notificacion == 2){
                        $Array['nombre'] = 'Media';
                    }else{
                        $Array['nombre'] = 'Baja';
                    }
                    array_push($ToReturn,$Array);
                }
            }
			return $ToReturn;
        }
        function ComprobarPrioridadNotificacionesUpdate($Cantidad,$TipoNotificacion,$idNotificacion){
            $ToReturn["result"] = false;
            $ToReturn["message"] = 'Error al crear el registro';
            $db = new DB();
            if($TipoNotificacion == 1){
                $query = "  SELECT COUNT(*) AS Cantidad FROM control_notificaciones WHERE id_cedente = '".$_SESSION['cedente']."' AND cantidad_horas < '".$Cantidad."' AND id != '".$idNotificacion."'";
                $message = 'El registro no puede ser creado ya que la cantidad de horas debe ser menor a la prioridad media';
            }else if($TipoNotificacion == 2){
                $query = "  SELECT COUNT(*) AS Cantidad FROM control_notificaciones WHERE id_cedente = '".$_SESSION['cedente']."' AND (tipo_notificacion = 1 AND cantidad_horas > '".$Cantidad."') OR (tipo_notificacion = 3 AND cantidad_horas < '".$Cantidad."') AND id != '".$idNotificacion."'";
                $message = 'El registro no puede ser creado ya que la cantidad de horas debe ser menor a la prioridad baja y mayor a la prioridad alta';
            }else{
                $query = "  SELECT COUNT(*) AS Cantidad FROM control_notificaciones WHERE id_cedente = '".$_SESSION['cedente']."' AND cantidad_horas > '".$Cantidad."' AND id != '".$idNotificacion."'";
                $message = 'El registro no puede ser creado ya que la cantidad de horas debe ser mayor a la prioridad media';
            }
            $Select = $db->select($query);
            if($Select[0]['Cantidad'] == 0){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["message"] = $message;
            }
            return $ToReturn;
        }
        function updateNotificacion($Cantidad,$TipoNotificacion,$idNotificacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $query = "UPDATE control_notificaciones SET cantidad_horas = '".$Cantidad."', tipo_notificacion = '".$TipoNotificacion."' WHERE id = '".$idNotificacion."'";
            $Update = $db->query($query);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteNotificacion($idNotificacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "DELETE FROM control_notificaciones WHERE id = '".$idNotificacion."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar notificacion';
            }
            return $ToReturn;
        }
    }
?>