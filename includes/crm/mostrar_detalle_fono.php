<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $crm->mostrarDetalleFono($_POST['id_fono']);
?>    