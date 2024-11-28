<?php 
    include("../../class/crm/crm.php");
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $Mandante = $_POST["Mandante"];
    $Cedente = $_POST["Cedente"];
    $crm = new crm();
    $crm->mostrarInforme($Mandante,$Cedente);
?>    