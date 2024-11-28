<?php 
    include("../../class/db/DB.php");
    include("../../class/exclusiones/exclusiones.php");
    $Exclusion = new Exclusion();
    echo $Exclusion->deleteExclusion($_POST['id_registr']);
?>   