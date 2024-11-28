<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $Sheet = $_POST["Sheet"];
    $Tabla = $_POST["Tabla"];
    $Campo = $_POST["Campo"];
    $PatronFecha = $_POST["PatronFecha"];
    $ColumnaExcel = $_POST["ColumnaExcel"];
    $PosicionInicio = $_POST["PosicionInicio"];
    $CantCaracteres = $_POST["CantCaracteres"];
    $Funcion = $_POST["Funcion"];
    $Parametro = $_POST["Parametro"];
    $PrioridadFono = $_POST["PrioridadFono"];
    $id_template = $_POST["id_template"];

    $ToReturn = array();
    $ToReturn = $CargaClass->addColumnCarga($id_template,$Sheet,$Tabla,$Campo,$PatronFecha,$ColumnaExcel,$PosicionInicio,$CantCaracteres,$Funcion,$Parametro,$PrioridadFono);

    echo json_encode($ToReturn);

?>