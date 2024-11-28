<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    
    $ToReturn = array();
    $dir_subida = '../../facturas/Tmp/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/';
    if (!file_exists($dir_subida)){
        mkdir($dir_subida, 0777, true);
    }
    $cantFiles = count($_FILES['file']['tmp_name']);
    for($i=0;$i<=$cantFiles - 1;$i++){
        $fichero_subido = $dir_subida . basename($_FILES['file']['name'][$i]);
        $PosEspace = strrpos(basename($_FILES['file']['name'][$i])," ");
        if($PosEspace === false){
            if(move_uploaded_file($_FILES['file']['tmp_name'][$i], $fichero_subido)){
                shell_exec("chmod 777 ".$fichero_subido);
            }else{
            }
        }        
    }
    $ficheros = scandir($dir_subida);
    foreach($ficheros as $fichero){
        switch($fichero){
            case ".":
            case "..":
            break;
            default:
                $Factura = str_replace(".pdf","",$fichero);
                $Result = $CargaClass->ExisteFactura($Factura);
                if($Result["result"]){
                    $Rut = $Result["Rut"];
                    $dir_Move = '../../facturas/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/'.$Rut;
                    if(!file_exists($dir_Move)){
                        mkdir($dir_Move, 0777, true);
                    }
                    rename($dir_subida."/".$fichero, $dir_Move."/".$fichero);
                }else{
                    $CargaClass->InsertFacturaInubicable($Factura);
                }
            break;
        }
    }
 ?>
