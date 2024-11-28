<?php
    //include("../../class/global/global.php");
    include("../../class/db/DB.php");

    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['cedente'])){
        $Cedente = $_SESSION['cedente'];
    }else{
        $Cedente = 0;
    }
    $db = new DB();
    $query = "  SELECT
                    *
                FROM
                    control_notificaciones
                WHERE
                    id_cedente = '".$Cedente."'";
    $Notificaciones = $db->select($query);
    $NotificacionesArray = array();
    if($Notificaciones){
        $IdUsuario = $_SESSION['id_usuario'];
        $fecha = new DateTime('America/Santiago');
        $fecha = $fecha->format('Y-m-d');
        $fecha_inicio = $fecha . " 00:00:00";
        $fecha_final = $fecha . " 23:59:59";

        $prioridad_alta = 0;
        $prioridad_media = 0;
        $prioridad_baja = 0;
        foreach($Notificaciones as $Notificacion){
            $tipo_notificacion = $Notificacion['tipo_notificacion'];
            $cantidad_horas = $Notificacion['cantidad_horas'];
            if($tipo_notificacion == 1){
                $prioridad_alta = $cantidad_horas;
            }else if($tipo_notificacion == 2){
                $prioridad_media = $cantidad_horas;
            }else{
                $prioridad_baja = $cantidad_horas;
            }
        }
        $QueryCautiva = "SELECT query FROM SIS_Querys_Estrategias WHERE cautiva = 1 AND terminal = 1 AND Id_Cedente = '".$Cedente."' AND IdUserCautiva = '".$IdUsuario."' ORDER BY prioridad";
        $Cautivos = $db->select($QueryCautiva);
        $Query = '';
        if($Cautivos){
            foreach($Cautivos as $Cautivo){
                $Query = $Cautivo['query'];
            }
            $Cont = 0;
            $SqlAgenda = "SELECT FechaAgenda, Agenda, Rut FROM Agendamiento WHERE Rut IN ($Query) AND Id_Cedente = '".$Cedente."' AND FechaAgenda BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'";
            $Agendas = $db->select($SqlAgenda);
            if($Agendas){
                foreach($Agendas as $Agenda){
                    $AgendaArray = array();
                    $hoy = new DateTime('America/Santiago');
                    $FechaAgenda = new DateTime($Agenda['FechaAgenda'] . ' ' . 'America/Santiago');
                    if($hoy < $FechaAgenda){
                        $diff = $hoy->diff($FechaAgenda);
                        $diff = $diff->h;
                        if($diff > $prioridad_media){
                            $Tipo_Notificacion = 3;
                        }else if($diff > $prioridad_alta){
                            $Tipo_Notificacion = 2;
                        }else{
                            $Tipo_Notificacion = 1;
                        }
                    }else{
                        $Tipo_Notificacion = 1;
                    }
                    $AgendaArray['Tipo_Notificacion'] = $Tipo_Notificacion;
                    $AgendaArray['Titulo'] = 'Agendamiento';
                    $AgendaArray['Rut'] = $Agenda['Rut'];
                    $AgendaArray['Fecha'] = $Agenda['FechaAgenda'];
                    $NotificacionesArray['Notificaciones'][$Cont] = $AgendaArray;
                    $Cont++;
                }
            }
            
            $SqlComp = "SELECT FechaCompromiso, Compromiso, Rut FROM Agendamiento_Compromiso WHERE Rut IN ($Query) AND Id_Cedente = '".$Cedente."' AND FechaCompromiso BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'";
            $Compromisos = $db->select($SqlComp);
            if($Compromisos){
                foreach($Compromisos as $Compromiso){
                    $CompromisoArray = array();
                    $hoy = new DateTime('America/Santiago');
                    $FechaCompromiso = new DateTime($Compromiso['FechaCompromiso'] . ' ' . 'America/Santiago');
                    if($hoy < $FechaCompromiso){
                        $diff = $hoy->diff($FechaCompromiso);
                        $diff = $diff->h;
                        if($diff > $prioridad_media){
                            $Tipo_Notificacion = 3;
                        }else if($diff > $prioridad_alta){
                            $Tipo_Notificacion = 2;
                        }else{
                            $Tipo_Notificacion = 1;
                        }
                    }else{
                        $Tipo_Notificacion = 1;
                    }
                    $CompromisoArray['Tipo_Notificacion'] = $Tipo_Notificacion;
                    $CompromisoArray['Titulo'] = 'Compromiso';
                    $CompromisoArray['Rut'] = $Compromiso['Rut'];
                    $CompromisoArray['Fecha'] = $Compromiso['FechaCompromiso'];
                    $NotificacionesArray['Notificaciones'][$Cont] = $CompromisoArray;
                    $Cont++;
                }
            }
        }
    }

    echo json_encode($NotificacionesArray);
?>