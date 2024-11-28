<?php
   // include_once("../../functions/Functions.php");
   // Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $Cedente = $_POST["Cedente"];
    $ArrayCampos = $_POST["ArrayCampos"];

    $ConfCamposGestion->deleteOrdenCampos($Cedente);
    $ConfCamposGestion->agregarOrdenCampos($ArrayCampos,$Cedente);
?>