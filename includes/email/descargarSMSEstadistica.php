<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
    QueryPHP_IncludeClasses("db");

    $codigo = $_GET['Codigo'];

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
                    p.Rut, p.Nombre_Completo as Nombre, g.correos as Correo, g.estado as Estado
                FROM 
                    Persona p
                INNER JOIN
                    gestion_correo g
                ON
                    p.Rut = g.rut_cliente
                INNER JOIN
                    envio_email e
                ON
                    g.id_envio = e.id
                WHERE
                    e.codigo = '".$codigo."'
                AND
                    (g.estado != 4 AND g.estado != 5)";

    $Envios = $db->select($query);
    if($Envios){
        foreach($Envios as $Envio){
            $Rows .= "\r\n";
            foreach($Columnas as $Columna){
                $Value = $Envio[$Columna];
                if($Columna == 'Estado'){
                    if($Value == '3'){
                        $Value = 'ABIERTO';
                    }else if($Estado == '1'){
                        $Value = 'RECIBIDO';
                    }else if($Estado == '2'){
                        $Value = 'REBOTADO';
                    }
                }
                $Value = utf8_encode($Value);
                $Value = str_replace(";","",$Value);
                $Value = str_replace("\n","",$Value);
                $Value = str_replace("\r","",$Value);
                $Rows .= $Value.";";
            }
        }
    }
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
    header('Cache-Control: max-age=0');
    
    echo $Rows;
?>