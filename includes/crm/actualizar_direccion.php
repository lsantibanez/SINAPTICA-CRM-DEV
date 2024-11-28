<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $ToReturn = $crm->actualizarDireccion($_POST['id_direccion'],$_POST['direccion'],$_POST['comuna']);
    echo json_encode($ToReturn);
?>    