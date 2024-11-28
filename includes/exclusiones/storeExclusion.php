<?php 
    include("../../class/db/DB.php");
    include("../../class/exclusiones/exclusiones.php");
    if($_POST['isInhibicion']){
        $Fecha_Term = '2999/01/31';
    }else{
        $Fecha_Term = $_POST['Fecha_Term'];
    }
    $Exclusion = new Exclusion();
    echo $Exclusion->storeExclusion($_POST['Tipo'],$_POST['Dato'],$_POST['Fecha_Inic'],$Fecha_Term,$_POST['Descripcio']);
?>    