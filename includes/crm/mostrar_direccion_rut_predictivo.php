<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $crm->mostrarDireccionRutPredictivo($_POST['rut'],$_POST["Queue"]);
?>    