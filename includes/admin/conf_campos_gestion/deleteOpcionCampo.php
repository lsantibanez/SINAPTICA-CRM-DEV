<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $idOpcion = $_POST["idOpcion"];

    $ToReturn = $ConfCamposGestion->deleteOpcionCampo($idOpcion);
    echo json_encode($ToReturn);
?>