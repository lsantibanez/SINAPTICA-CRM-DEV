<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
    QueryPHP_IncludeClasses("db");

    $Estrategia = $_GET['Estrategia'];

    $dbDiscador = new DB('discador');
    $objPHPExcel = new PHPExcel();
    $fileName = "Reporte Estadistica ".date("d_m_Y H_i_s");
    
    $Rows = "";

    $Columnas = array();
    $Columnas[0] = "Rut";
    $Columnas[1] = "Fono";
    $Columnas[2] = "Fecha";
    $Columnas[3] = "Hora";
    $Columnas[4] = "Gestion";
    $Columnas[5] = "Respuesta";

    foreach($Columnas as $Columna){
        $Rows .= $Columna.";";
    }

    $query = "	SELECT 
                    rut as Rut, fono as Fono, fecha as Fecha, hora as Hora, gestion as Gestion, respuesta_cliente as Respuesta
                FROM 
                    RP_resultado_bot
                WHERE
                    tabla = 'BOT_".$Estrategia."'
                AND
                    gestion != 'EN PROCESO'";
    $Envios = $dbDiscador->select($query);
    foreach($Envios as $Envio){
        $Rows .= "\r\n";
        foreach($Columnas as $Columna){
            $Value = $Envio[$Columna];
            $Value = utf8_encode($Value);
            $Value = str_replace(";","",$Value);
            $Value = str_replace("\n","",$Value);
            $Value = str_replace("\r","",$Value);
            $Rows .= $Value.";";
        }
    }
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
    header('Cache-Control: max-age=0');
    
    echo $Rows;
?>