<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $NombreTemplate = $_POST['NombreTemplate'];
    $TipoArchivo = $_POST['TipoArchivo'];
    $Separador = $_POST['Separador'];
    $Cabecero = $_POST['Cabecero'];
    if(isset($_POST['id'])){
        $id = $_POST['id'];
    }else{
        $id = '';
    }

    $ToReturn = array();
    $ToReturn = $CargaClass->addTemplateCarga($NombreTemplate,$TipoArchivo,$Separador,$Cabecero,$id);

    echo json_encode($ToReturn);

?>