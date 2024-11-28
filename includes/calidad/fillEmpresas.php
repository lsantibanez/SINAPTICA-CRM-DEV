<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("global");
    $CedenteClass = new Cedente();
    $Mandantes = $CedenteClass->getMandantes();
    $ToReturn = '';

    foreach($Mandantes as $Mandante){
        $ToReturn .= "<option value='".$Mandante['id']."'>".$Mandante['nombre']."</option>";
    }
    echo $ToReturn;
?>