<?php 
    include("../../class/sac/sac.php");
    include("../../class/db/DB.php");
    $sac = new sac();
    $sac->seleccioneTipo($_POST['tipo']);
?>    