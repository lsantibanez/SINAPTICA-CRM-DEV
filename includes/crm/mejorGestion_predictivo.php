<?php 
    include("../../class/crm/crm.php");
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $crm = new crm();
    $crm->mostrarMejorGestionPredictivo($_POST['rut'],$_POST["Queue"]);
?>    