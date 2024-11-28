<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();
    
    $Politica = $_POST["Politica"];
    $Cedente = $_POST["Cedente"];
    
    $ToReturn = $ConfScript->CrearPolitica($Politica,$Cedente);
    echo json_encode($ToReturn);
?>