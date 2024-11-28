<?php
 //   include_once("../../functions/Functions.php");
 //   Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $Script = $_POST["Script"];
    $Cedente = $_POST["Cedente"];
    $idScript = $_POST["idScript"];

    $ToReturn = $ConfScript->updateScript($Script,$Cedente,$idScript);
    echo json_encode($ToReturn);
?>