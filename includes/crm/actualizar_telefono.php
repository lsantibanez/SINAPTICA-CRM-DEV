<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $ToReturn = $crm->actualizarTelefono(trim($_POST['id_reg']),trim($_POST['telefono']),trim($_POST['nombre']),trim($_POST['cargo']),trim($_POST['observacion']));
    echo json_encode($ToReturn);
?>