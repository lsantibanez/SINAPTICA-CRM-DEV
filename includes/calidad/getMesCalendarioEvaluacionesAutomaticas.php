<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $Action = $_POST["Action"];
    $fechaActual = $_POST["fechaActual"];

    $ToReturn = array();
    $ToReturn["result"] = true;

    $Months = array();
    $Months[1] = "Enero";
    $Months[2] = "Febrero";
    $Months[3] = "Marzo";
    $Months[4] = "Abril";
    $Months[5] = "Mayo";
    $Months[6] = "Junio";
    $Months[7] = "Julio";
    $Months[8] = "Agosto";
    $Months[9] = "Septiembre";
    $Months[10] = "Octubre";
    $Months[11] = "Noviembre";
    $Months[12] = "Diciembre";

    switch($Action){
        case "-1":
        case "1":
            switch($Action){
                case "-1":
                    $Fecha = date("Ymd",strtotime($fechaActual." - 1 month"));
                break;
                case "1":
                    $Fecha = date("Ymd",strtotime($fechaActual." + 1 month"));
                break;
            }
            $StrToTimeFechaSeleccionada = strtotime($Fecha);
            $StrToTimeFechaActual = strtotime(date("Ymd"));
            if($StrToTimeFechaSeleccionada > $StrToTimeFechaActual){
                $ToReturn["result"] = false;
            }else{
                $MesActual = (Int) date("m",strtotime($Fecha));
                $MesActualNumero = $MesActual;
                $AnoActual = (Int) date("Y",strtotime($Fecha));
                $MesActual = $Months[$MesActual];
                $ToReturn["Texto"] = $MesActual." ".$AnoActual;
                $ToReturn["Fecha"] = $Fecha;
                $ToReturn["Mes"] = $MesActualNumero;
            }
            
        break;
        default:
            $Fecha = date("Ymd");
            $MesActual = (Int) date("m",strtotime($Fecha));
            $MesActualNumero = $MesActual;
            $AnoActual = (Int) date("Y",strtotime($Fecha));
            $MesActual = $Months[$MesActual];
            $ToReturn["Texto"] = strtoupper($MesActual." ".$AnoActual);
            $ToReturn["Fecha"] = $Fecha;
            $ToReturn["Mes"] = $MesActualNumero;
        break;
    }
    echo json_encode($ToReturn);
?>