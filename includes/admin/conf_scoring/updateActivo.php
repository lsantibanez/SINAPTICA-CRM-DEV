<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $idVariable = $_POST['idVariable'];
    $Activo = $_POST["Activo"];
    
    $ToReturn = $ConfScoring->updateActivo($idVariable,$Activo);
    echo json_encode($ToReturn);
?>