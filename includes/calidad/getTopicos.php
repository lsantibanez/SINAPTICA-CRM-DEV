<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Modulo = $_POST['Modulo'];
    $Topicos = $CalidadClass->getTopicos($Modulo);
    $ToReturn = "";
    foreach($Topicos as $Topico){
        $ToReturn .= "<option value='".$Topico["id"]."'>".utf8_encode($Topico["Nombre"])."</option>";
    }
    echo $ToReturn;
?>