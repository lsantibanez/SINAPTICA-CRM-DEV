<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $Supervision = new Supervision();

    $ToReturn = '';

    if(trim($_POST['estrategia']) != ""){
        $Queues = $Supervision->getQueues(trim($_POST['estrategia']));
        $ToReturn = '<option value="">Todas</option>';

        foreach($Queues as $queue){
            $ToReturn .= "<option value='".$queue['id']."'>".$queue['cola']."</option>";
        }
    }

    echo $ToReturn;
?>