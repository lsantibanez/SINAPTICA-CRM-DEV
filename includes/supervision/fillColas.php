<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $Supervision = new Supervision();
    $Colas       = $Supervision->getColas();
    $ToReturn    = "<option value=''>Todas</option>";

    foreach($Colas as $cola){
        $ToReturn .= "<option value='" . $cola['queue'] . "'>" . $cola['queue'] . " - " . $cola['asignacion'] . "</option>";
    }
    echo $ToReturn;
?>