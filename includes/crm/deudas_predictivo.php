<?php 
    include("../../class/crm/crm.php");
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $crm = new crm();
    $ToReturn = $crm->mostrarDeudasPredictivo($_POST['rut'],$_POST['cedente'],$_POST["Queue"]);
    echo json_encode($ToReturn);
?>    