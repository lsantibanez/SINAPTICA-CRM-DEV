<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    
    $Contenedor = $_POST["Contenedor"];
    $Codigo = $_POST["Codigo"];
    $Titulo = $_POST["Titulo"];
    $ValorEjemplo = $_POST["ValorEjemplo"];
    $ValorPredeterminado = $_POST["ValorPredeterminado"];
    $Tipo = $_POST["Tipo"];
    $Mandatorio = $_POST["Mandatorio"];
    $Deshabilitado = $_POST["Deshabilitado"];
    $ArrayOpciones = $_POST["ArrayOpciones"];
    
    $ReclutamientoClass = new Reclutamiento();    
    $ToReturn = $ReclutamientoClass->CrearCampo($Contenedor,$Codigo,$Titulo,$ValorEjemplo,$ValorPredeterminado,$Tipo,$Mandatorio,$Deshabilitado,$ArrayOpciones);
    echo json_encode($ToReturn);
?>