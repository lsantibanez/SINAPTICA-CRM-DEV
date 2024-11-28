<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_script.php");
    $ConfScript = new ConfScript();

    $Cedentes = $ConfScript->getCedentesCreateScriptCompleto();
    $Array = array();
    if($Cedentes){
        foreach($Cedentes as $Cedente){
            $ArrayTmp = array();
            $ArrayTmp["idCedente"] = $Cedente["idCedente"];
            $ArrayTmp["NombreCedente"] = $Cedente["NombreCedente"];
            array_push($Array,$ArrayTmp);
        }
    }
    echo json_encode($Array);
?>