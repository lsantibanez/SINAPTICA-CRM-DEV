<?php
 //   include_once("../../functions/Functions.php");
 //   Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $MedioPago = $_POST["MedioPago"];
    $Cedente = $_POST["Cedente"];
    $id = $_POST["id"];

    $ToReturn = $ConfScript->updateMedioPago($MedioPago,$Cedente,$id);
    echo json_encode($ToReturn);
?>