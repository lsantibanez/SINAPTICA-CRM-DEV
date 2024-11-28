<?php 
    include("../../class/crm/crm.php");
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $crm = new crm();
    
    $idGestion = $_POST["idGestion"];

    $ToReturn = $crm->deleteGestion($idGestion);

    echo json_encode($ToReturn);
?>    