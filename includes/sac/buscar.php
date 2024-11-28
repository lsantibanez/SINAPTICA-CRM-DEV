<?php 
    include("../../class/sac/sac.php");
    include("../../class/db/DB.php");
    $sac = new sac();
    $toReturn = $sac->buscar($_POST['tipo'],$_POST['subTipo'],$_POST['dato']);

?>    