<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();
    $Campos = $ConfCamposGestion->getCampoTableList();
    $Array = array();
    if($Campos) {
        foreach((array) $Campos as $Campo) {
            $ArrayTmp = array();
            $ArrayTmp["Codigo"] = utf8_encode($Campo["Codigo"]);
            $ArrayTmp["Titulo"] = $Campo["Titulo"];
            $ArrayTmp["ValorEjemplo"] = utf8_encode($Campo["ValorEjemplo"]);
            $ArrayTmp["ValorPredeterminado"] = utf8_encode($Campo["ValorPredeterminado"]);
            $ArrayTmp["Tipo"] = utf8_encode($Campo["Tipo"]);
            $ArrayTmp["Dinamico"] = $Campo["Dinamico"];
            $ArrayTmp["Mandatorio"] = $Campo["Mandatorio"];
            $ArrayTmp["Deshabilitado"] = $Campo["Deshabilitado"];
            $ArrayTmp["Cedente"] = $Campo["Cedente"];
            $ArrayTmp["Accion"] = $Campo["id"];
            array_push($Array,$ArrayTmp);
        }
    }
    echo json_encode($Array);
?>