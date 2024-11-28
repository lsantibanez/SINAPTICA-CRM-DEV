<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $ToReturn = $crm->getTemplates();
    echo json_encode($ToReturn);
?>    