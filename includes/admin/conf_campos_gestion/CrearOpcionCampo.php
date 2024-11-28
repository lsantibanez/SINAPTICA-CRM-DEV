<?php
  //  include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    
    $Prioridad = $_POST["Prioridad"];
    $Opcion = $_POST["Opcion"];
    $Seleccionado = $_POST["Seleccionado"];
    $idCampo = $_POST["idCampo"];

    $ConfCamposGestion = new ConfCamposGestion();
    $ToReturn = $ConfCamposGestion->CrearOpcionCampo($Prioridad,$Opcion,$Seleccionado,$idCampo);
    echo json_encode($ToReturn);
?>