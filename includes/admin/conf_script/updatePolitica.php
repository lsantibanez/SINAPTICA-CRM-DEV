<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $Politica = $_POST["Politica"];
    $Cedente = $_POST["Cedente"];
    $id = $_POST["id"];

    $ToReturn = $ConfScript->updatePolitica($Politica,$Cedente,$id);
    echo json_encode($ToReturn);
?>