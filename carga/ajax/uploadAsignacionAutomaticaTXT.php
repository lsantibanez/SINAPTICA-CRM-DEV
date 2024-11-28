<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = array();
    $ToReturn["result"] = true;
    $db = new Db;
    $mandante = $_SESSION['mandante'];
    $cedente = $_SESSION['cedente'];
    $id_template = $_POST['id_template'];
    $TipoCarga = $_POST['TipoCarga'];
    if($TipoCarga != 'cargagestiones'){
        $id_origen = '-';
    }else{
        $id_origen = $_POST['id_origen'];
    }
    $query = "SELECT carga_personalizada FROM Cedente WHERE Id_Cedente = '".$cedente."'";
    $CedenteDB = $db->select($query);
    $CedenteDB = $CedenteDB[0];
    if($CedenteDB['carga_personalizada'] == 0){
        $Result = $CargaClass->HaveMandatoryFieldsNoConfigured($id_template);
        if($Result){
            $Result = $CargaClass->HaveCargaAutomaticaEnCurso();
            if($Result["result"]){
                $ToReturn["comment"] = $Result["comment"];
                $ToReturn["filename"] = $Result["filename"];
                $ToReturn["usuario"] = $Result["usuario"];
            }else{
                $FechaInicioPeriodoArray = explode("_",$_POST["FechaInicioPeriodo"]);
                $FechaInicioPeriodo = $FechaInicioPeriodoArray[0] == "1" ? $FechaInicioPeriodoArray[0]."_".date("Ymd", strtotime($FechaInicioPeriodoArray[1])) : $_POST["FechaInicioPeriodo"];
                $FechaFinPeriodoArray = explode("_",$_POST["FechaFinPeriodo"]);
                $FechaFinPeriodo = $FechaFinPeriodoArray[0] == "1" ? $FechaFinPeriodoArray[0]."_".date("Ymd", strtotime($FechaFinPeriodoArray[1])) : $_POST["FechaFinPeriodo"];
                $cantFiles = count($_FILES['file']['tmp_name']);
                //$dir_subida = '../../task/CargaAsignaciones/Asignaciones/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/';
                if(!$db->isLocalhost()){
                    if (!file_exists($_SERVER['DOCUMENT_ROOT']."/task/CargaAsignaciones/Asignaciones")){
                        echo $_SERVER['DOCUMENT_ROOT']."/task/CargaAsignaciones/Asignaciones";
                    }
                    if (!file_exists("../../task/CargaAsignaciones/Asignaciones/".$mandante."/".$cedente)){
                        mkdir("../../task/CargaAsignaciones/Asignaciones/".$mandante."/".$cedente, 0777, true);
                    }
                    $dir_subida = '../../task/CargaAsignaciones/Asignaciones/'.$mandante.'/'.$cedente.'/';
                    $files = scandir($dir_subida); // Devuelve un vector con todos los archivos y directorios
                    $ficherosEliminados = 0;
                    foreach($files as $file){
                        if (is_file($dir_subida.$file)) {
                            if (unlink($dir_subida.$file) ){
                                $ficherosEliminados++;
                            }
                        }
                    }
                    $Cargados = 0;
                    for($i=0;$i<=$cantFiles - 1;$i++){
                        $fichero_subido = $dir_subida . basename($_FILES['file']['name'][$i]);
                        if(move_uploaded_file($_FILES['file']['tmp_name'][$i], $fichero_subido)){
                            shell_exec("chmod 777 ".$fichero_subido);
                            $Cargados++;
                        }
                    }
                }else{
                    $Cargados = $cantFiles;
                }
                if($Cargados == $cantFiles){
                    $upload = true;
                    
                    $ToReturn["comment"] = "Procesando";
                    $ToReturn["filename"] = "Archivos";
                    $ToReturn["usuario"] = $_SESSION['nombreUsuario'];
                    
                    $query = 'java -jar -Xms1g -Xmx10g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 /var/www/html/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "'.$TipoCarga.'" "/var/www/html/" "'.$mandante.'/'.$cedente.'" "'.$_SESSION['id_usuario'].'" "'.$_POST['MarcaData'].'" "'.$_POST['FilesTXT'].'" "'.$FechaInicioPeriodo.'" "'.$id_template.'" "'.$id_origen.'" "'.$_POST['CargaAdicional'].'" "'.$FechaFinPeriodo.'" > /dev/null 2>&1 &';
                    shell_exec($query);
                    
                }
            }
        }else{
            $ToReturn["result"] = false;
            $ToReturn["Message"] = "Existen Columnas mandatorias no configuradas. Configurelas e intente nuevamente.";
        }
    }else{
        if (!file_exists($_SERVER['DOCUMENT_ROOT']."/task/CargaAsignaciones/Asignaciones")){
            echo $_SERVER['DOCUMENT_ROOT']."/task/CargaAsignaciones/Asignaciones";
        }
        if (!file_exists("../../task/CargaAsignaciones/Asignaciones/".$mandante."/".$cedente."/custom")){
            mkdir("../../task/CargaAsignaciones/Asignaciones/".$mandante."/".$cedente."/custom", 0777, true);
        }
        $dir_subida = '../../task/CargaAsignaciones/Asignaciones/'.$mandante.'/'.$cedente.'/custom/';
        $files = scandir($dir_subida); // Devuelve un vector con todos los archivos y directorios
        $ficherosEliminados = 0;
        foreach($files as $file){
            if (is_file($dir_subida.$file)) {
                if (unlink($dir_subida.$file) ){
                    $ficherosEliminados++;
                }
            }
        }
        $cantFiles = count($_FILES['file']['tmp_name']);
        $Cargados = 0;
        for($i=0;$i<=$cantFiles - 1;$i++){
            $fichero_subido = $dir_subida . basename($_FILES['file']['name'][$i]);
            if(move_uploaded_file($_FILES['file']['tmp_name'][$i], $fichero_subido)){
                shell_exec("chmod 777 ".$fichero_subido);
                $Cargados++;
            }
        }
        if($Cargados != $cantFiles){
            $ToReturn["result"] = false;
            $ToReturn["Message"] = "Hubo un error en el proceso de carga personalizada.";
        }
    }
    echo json_encode($ToReturn);
 ?>
