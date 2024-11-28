<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("global");
    $Mandante = $_POST["Mandante"];
    $CedenteClass = new Cedente();
    $Cedentes = $CedenteClass->getCedentesMandante($Mandante);
    $ToReturn = "<option value='' selected>Todos</option>";
    foreach($Cedentes as $Cedente){
        $ToReturn .= "<option value='".$Cedente["idCedente"]."'>".utf8_encode($Cedente["NombreCedente"])."</option>";
    }
    echo $ToReturn;
?>    