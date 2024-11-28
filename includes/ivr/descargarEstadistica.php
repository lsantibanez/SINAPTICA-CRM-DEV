<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
    QueryPHP_IncludeClasses("db");

    $Estrategia = $_GET['Estrategia'];

    $db = new DB();
    $objPHPExcel = new PHPExcel();
    $fileName = "Reporte Estadistica ".date("d_m_Y H_i_s");
    
    $Rows = "";

    $Columnas = array();
    $Columnas[0] = "Rut";
    $Columnas[1] = "Nombre";
    $Columnas[2] = "Fono";
    $Columnas[3] = "Estado";

    foreach($Columnas as $Columna){
        $Rows .= $Columna.";";
    }

    $query = "	SELECT 
                    g.Rut, p.Nombre_Completo as Nombre, g.Fono, g.estado as Estado
                FROM 
                    gestion_ivr g
                LEFT JOIN
                    Persona p
                ON
                    g.Rut = p.Rut
                WHERE
                    g.cola = 'IVR_".$Estrategia."'";
    $Envios = $db->select($query);
    foreach($Envios as $Envio){
        $Rows .= "\r\n";
        foreach($Columnas as $Columna){
            $Value = $Envio[$Columna];
            if($Columna == 'Estado'){
                if($Value == '0'){
					$Value = 'POSIBLE BUZON DE VOZ';
                }else if($Value == '1'){
					$Value = 'IVR CONTESTADO';
                }else if($Value == '2'){
					$Value = 'IVR NO CONTESTADO';
				}else{
                    $Value = 'PENDIENTE';
                }
            }
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