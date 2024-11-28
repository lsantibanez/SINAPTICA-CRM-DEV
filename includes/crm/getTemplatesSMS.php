<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $ToReturn = $crm->getTemplatesSMS();
    echo json_encode($ToReturn);
?>    