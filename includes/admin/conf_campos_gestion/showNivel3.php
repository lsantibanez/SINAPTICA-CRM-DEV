<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $Niveles = $ConfCamposGestion->showNivel3();
    $ToReturn = "";
    if($Niveles){
        foreach($Niveles as $Nivel){
            $ToReturn .= "<option value='".$Nivel["id"]."'>".utf8_encode($Nivel["nivel_1"])." - ".utf8_encode($Nivel["nivel_2"])." - ".utf8_encode($Nivel["nivel_3"])."</option>";
        }
    }
    echo $ToReturn;
?>