<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("global");
    $CedenteClass = new Cedente();
    $Mandantes = $CedenteClass->getMandantes();
    $ToReturn = "<option value='' selected>Todos</option>";
    foreach($Mandantes as $Mandante){
        $ToReturn .= "<option value='".$Mandante["id"]."'>".utf8_encode($Mandante["nombre"])."</option>";
    }
    echo $ToReturn;
?>    