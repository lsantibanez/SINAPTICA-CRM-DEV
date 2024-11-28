<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("global");

    $ToReturn = '';

    if(trim($_POST['mandante']) != ""){
        $CedenteClass = new Cedente();
        $Cedentes = $CedenteClass->getCedentesMandante(trim($_POST['mandante']));
        $ToReturn = '<option value="">Todos</option>';

        foreach($Cedentes as $Cedente){
            $ToReturn .= "<option value='".$Cedente['idCedente']."'>".$Cedente['NombreCedente']."</option>";
        }
    }
    echo $ToReturn;
?>