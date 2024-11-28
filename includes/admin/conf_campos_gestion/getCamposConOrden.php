<?php
  //  include_once("../../functions/Functions.php");
   // Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $Cedente = $_POST["Cedente"];
    
    $Campos = $ConfCamposGestion->getCamposConOrden($Cedente);
    $ToReturn = "";
    if($Campos){
        foreach($Campos as $Campo){
            $ToReturn .= "<div id='".$Campo["id"]."' anchura='".$Campo["Anchura"]."' class='FieldOrden form-group col-md-".$Campo["Anchura"]."'><div style='height: 80px; padding: 5px 10px; background-color: #eeeeee; border: 2px dashed #333333; cursor: pointer; position: relative;'><div class='deleteOrdenCampo fa fa-times' style='position: absolute; right: 15px; top: 15px;'></div><div class='CodigoCampo'>".$Campo["Codigo"]."</div><div class='TipoCampo'>".utf8_encode($Campo["Tipo"])."</div></div></div>";
        }
    }
    echo $ToReturn;
?>