<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $Supervision = new Supervision();
    $Listas = $Supervision->getListas();

    $ToReturn = '';
    if($Listas){
        foreach($Listas as $lista){
            $ToReturn .= "<option value='" . $lista['Queue'] . "'>" . $lista['Queue'] . "</option>";
        }
    }
    echo $ToReturn;
?>