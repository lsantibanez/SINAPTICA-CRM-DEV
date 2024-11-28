<?php 
    include_once("../functions/Functions.php");
	include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

    $estrategia 	= $_POST["estrategia"];

    $email = new email();
    $codigos = $email->fillCodigos($estrategia);

    $ToReturn = "";

    if(is_array($codigos)){
        foreach($codigos as $codigo){
            $ToReturn .= "<option value='" . $codigo['codigo'] . "'>" . $codigo['fechahora'] . "</option>";
        }
    }

    echo $ToReturn;
?>