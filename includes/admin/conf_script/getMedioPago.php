<?php
 //   include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $id = $_POST["id"];

    $MedioPago = $ConfScript->getMedioPago($id);
    $Array = array();
    if($MedioPago){
        $Array["medio_pago"] = html_entity_decode($MedioPago["medio_pago"]);
        $Array["id_cedente"] = $MedioPago["id_cedente"];
    }else{
        $Array["medio_pago"] = '';
        $Array["id_cedente"] = '';
    }
    echo json_encode($Array);
?>