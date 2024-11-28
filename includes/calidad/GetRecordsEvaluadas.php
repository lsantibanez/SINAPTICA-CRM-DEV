<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("personal");
    $CalidadClass = new Calidad();
    $CalidadClass->User = $_POST['Ejecutivo'];
    $CalidadClass->Id_Cedente = $_POST['Cartera'];
    $CalidadClass->Id_Mandante = $_POST['Mandante'];
    $Periodo = $_POST['Periodo'];
    $CalidadClass->Tipificacion = $_POST['Tipificacion'];
    echo json_encode($CalidadClass->getRecordListEvaluadosAjax($Periodo));
?>