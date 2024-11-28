<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $Cedente = $_POST["Cedente"];
    
    $Campos = $ConfCamposGestion->getCamposSinOrden($Cedente);
    $ToReturn = "";
    if($Campos){
        foreach($Campos as $Campo){
            $ToReturn .= "<a class='list-group-item Field' id='".$Campo["id"]."' Codigo='".$Campo["Codigo"]."' Tipo='".utf8_encode($Campo["Tipo"])."' style='text-align: center; cursor: pointer;'><strong>".$Campo["Codigo"]."</strong><br>".utf8_encode($Campo["Tipo"])."</a>";
        }
    }
    echo $ToReturn;
?>