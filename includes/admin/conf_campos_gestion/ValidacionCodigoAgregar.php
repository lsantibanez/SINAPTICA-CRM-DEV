<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
include_once("../../../class/admin/conf_campos_gestion.php");
$Codigo = $_POST["Codigo"];
$ConfCamposGestion = new ConfCamposGestion();
$ToReturn = $ConfCamposGestion->ValidacionCodigoAgregar($Codigo);
echo json_encode($ToReturn);
?>