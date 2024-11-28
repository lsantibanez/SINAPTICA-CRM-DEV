<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");

    $ToReturn = '';

    if(trim($_POST['cedente']) != ""){
        $Supervision = new Supervision();
        $Estrategias = $Supervision->getEstrategias(trim($_POST['cedente']));
        $ToReturn = '<option value="">Todas</option>';

        foreach($Estrategias as $estrategia){
            $ToReturn .= "<option value='".$estrategia['id']."'>".$estrategia['nombre']."</option>";
        }
    }

    echo $ToReturn;
?>