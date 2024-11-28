<?php
//    include_once("../../functions/Functions.php");
//    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $idScript = $_POST["idScript"];

    $Script = $ConfScript->getScriptCompleto($idScript);
    $Array = array();
    if($Script){
        $Array["script"] = html_entity_decode($Script["script"]);
        $Array["id_cedente"] = $Script["id_cedente"];
    }else{
        $Array["script"] = '';
        $Array["id_cedente"] = '';
    }
    echo json_encode($Array);
?>