<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $Supervision = new Supervision();
    $ratios = $Supervision->getRatios();

    $ToReturn = '';

    foreach($ratios as $ratio){
        $ToReturn .= "<option value='" . $ratio['id'] . "'>" . $ratio['ratio'] . "</option>";
    }
    echo $ToReturn;
?>