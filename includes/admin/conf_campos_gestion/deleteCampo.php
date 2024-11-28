<?php
   // include_once("../../functions/Functions.php");
   // Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $idCampo = $_POST["idCampo"];

    $ToReturn = $ConfCamposGestion->deleteCampo($idCampo);
    echo json_encode($ToReturn);
?>