<?php
    include("../../class/global/global.php");
    include("../../class/db/DB.php");
    $Omni = new Omni();
    $ToReturn = $Omni->getUnusedColumns('Deuda');
    echo $ToReturn;
?>
