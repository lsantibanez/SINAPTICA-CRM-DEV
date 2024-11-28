<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $id = $_POST["id"];

    $ToReturn = $ConfScript->deleteMedioPago($id);
    echo json_encode($ToReturn);
?>