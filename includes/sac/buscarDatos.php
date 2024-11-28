<?php 
    include("../../class/sac/sac.php");
    include("../../class/db/DB.php");
    $sac = new sac();
    $sac->buscarDatos($_POST['tipo'],$_POST['subTipo'],$_POST['dato']);

?>    