<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $idCampo = $_POST["idCampo"];

    $Campo = $ConfCamposGestion->getCampo($idCampo);
    echo json_encode($Campo);
?>