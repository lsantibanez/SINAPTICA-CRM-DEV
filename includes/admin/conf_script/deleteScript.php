<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $idScript = $_POST["idScript"];

    $ToReturn = $ConfScript->deleteScript($idScript);
    echo json_encode($ToReturn);
?>