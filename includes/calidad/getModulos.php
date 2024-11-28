<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Competencia = $_POST['Competencia'];
    $Modulos = $CalidadClass->getModulos($Competencia);
    $ToReturn = "";
    foreach($Modulos as $Modulo){
        $ToReturn .= "<option value='".$Modulo["id"]."'>".utf8_encode($Modulo["Nombre"])."</option>";
    }
    echo $ToReturn;
?>