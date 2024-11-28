<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = array();
    $Templates = $CargaClass->getTemplates();
    if(count($Templates) > 0){
        foreach($Templates as $Template){
            $id = $Template["id"];
            $ArrayTmp["id"] = $id;
            $ArrayTmp["NombreTemplate"] = $Template["NombreTemplate"];
            switch ($Template["Tipo_Archivo"]) {
                case 'xlsx':
                    $TipoArchivo = "Archivo Excel (XLSX)";
                    break;
                case 'xls':
                    $TipoArchivo = "Archivo Excel (XLS)";
                    break;
                case 'csv':
                    $TipoArchivo = "Archivo CSV";
                    break;
                case 'txt':
                    $TipoArchivo = "Archivo de Texto";
                    break;
            }
            $ArrayTmp["TipoArchivo"] = $TipoArchivo;
            $ArrayTmp["Separador"] = $Template["Separador_Cabecero"];
            if($Template["haveHeader"] == 1){
                $haveHeader = 'Si';
            }else{
                $haveHeader = 'No';
            }
            $ArrayTmp["Cabecero"] = $haveHeader;
            $Sheets = $CargaClass->getSheets($id);
            $ArrayTmp["Sheets"] = count($Sheets);
            $ArrayTmp["FileType"] = $Template["Tipo_Archivo"];
            array_push($ToReturn,$ArrayTmp);
        }
    }
    echo json_encode($ToReturn);
?>