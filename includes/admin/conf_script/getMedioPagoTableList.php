<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $Campos = $ConfScript->getMedioPagoTableList();
    $Array = array();
    if($Campos){
        foreach($Campos as $Campo){
            $ArrayTmp = array();
            $ArrayTmp["Nombre_Cedente"] = $Campo["Nombre_Cedente"];
            $ArrayTmp["Accion"] = $Campo["id"];
            array_push($Array,$ArrayTmp);
        }
    }
    echo json_encode($Array);
?>