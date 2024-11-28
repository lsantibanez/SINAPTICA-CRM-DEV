<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    echo json_encode($crm->getTabsContent($_POST['rut']));
?>    