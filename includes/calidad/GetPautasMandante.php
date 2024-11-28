<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idMandante = $_POST["Mandante"];
    $Pautas = $CalidadClass->getPautasMandante($idMandante);
    $ToReturn = "";
    foreach($Pautas as $Pauta){
        $ToReturn .= "<option value='".$Pauta['id']."'>".utf8_encode($Pauta['nombreContenedor'])."</option>";
    }
    echo $ToReturn;
?>