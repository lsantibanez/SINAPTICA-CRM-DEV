<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $ToReturn = '';

    if(trim($_POST['cola']) != ""){
        $Supervision = new Supervision();
        $Asignaciones = $Supervision->getAsignacionColas(trim($_POST['cola']));
        
        foreach($Asignaciones as $asignacion){
            print_r($asignacion);
            $ToReturn .= "<option value='".$asignacion['id']."'>".$asignacion['asignacion']."</option>";
        }
    }
    
    echo $ToReturn;
?>