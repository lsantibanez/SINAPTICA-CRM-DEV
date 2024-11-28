<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $Supervision = new Supervision();

    $ratio = trim($_POST['ratio']);

    $gestiones = $Supervision->getTipoContacto($ratio);

    $ToReturn = '';

    foreach($gestiones as $gestion){
        $ToReturn .= "<option value='" . $gestion['Id_TipoContacto'] . "'>" . $gestion['Nombre'] . "</option>";
    }
    echo $ToReturn;
?>