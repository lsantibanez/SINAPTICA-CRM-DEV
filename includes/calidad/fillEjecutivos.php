<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idPauta = $_POST['idPauta'];
    $Ejecutivos = $CalidadClass->getPersonalEjecutivosPauta($idPauta);
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Ejecutivos as $Ejecutivo){
        $ToReturn .= "<option value='".$Ejecutivo['Id_Personal']."'>".$Ejecutivo['Nombre']."</option>";
    }
    echo $ToReturn;
?>