<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $id = $_POST["id"];

    $Politica = $ConfScript->getPolitica($id);
    $Array = array();
    if($Politica){
        $Array["politica"] = html_entity_decode($Politica["politica"]);
        $Array["id_cedente"] = $Politica["id_cedente"];
    }else{
        $Array["politica"] = '';
        $Array["id_cedente"] = '';
    }
    echo json_encode($Array);
?>