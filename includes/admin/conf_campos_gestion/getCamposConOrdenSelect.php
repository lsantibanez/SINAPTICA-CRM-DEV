<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $Campos = $ConfCamposGestion->getCamposConOrdenNoSeleccionado();
    $ToReturn = "";
    if($Campos){
        foreach($Campos as $Campo){
            $ToReturn .= "<option value='".$Campo["id"]."'>".utf8_encode($Campo["Codigo"])."</option>";
        }
    }
    echo $ToReturn;
?>