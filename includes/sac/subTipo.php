<?php 
    include("../../class/sac/sac.php");
    include("../../class/db/DB.php");
    $sac = new sac();
    $sac->subTipo($_POST['tipo'],$_POST['subTipo']);
?>    