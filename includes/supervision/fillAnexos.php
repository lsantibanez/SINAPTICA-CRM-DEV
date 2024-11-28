<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");

    $Supervision = new Supervision();
    $Anexos = $Supervision->getAnexos();

    $ToReturn = '';
    if($Anexos){
        foreach($Anexos as $anexo){
            $ToReturn .= "<option value='" . $anexo['anexo_foco'] . "'>" . $anexo['anexo_foco'] . " - " . $anexo['Nombre'] . "</option>";
        }
    }
    echo $ToReturn;
?>