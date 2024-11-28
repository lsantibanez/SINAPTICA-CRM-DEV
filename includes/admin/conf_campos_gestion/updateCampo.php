<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    $ConfCamposGestion = new ConfCamposGestion();

    $Titulo = $_POST["Titulo"];
    $ValorEjemplo = $_POST["ValorEjemplo"];
    $ValorPredeterminado = $_POST["ValorPredeterminado"];
    $Tipo = $_POST["Tipo"];
    $Mandatorio = $_POST["Mandatorio"];
    $Deshabilitado = $_POST["Deshabilitado"];
    $Cedente = $_POST["Cedente"];
    $Respuesta_Nivel3 = $_POST["Respuesta_Nivel3"];
    $idCampo = $_POST["idCampo"];

    $ToReturn = $ConfCamposGestion->updateCampo($Titulo,$ValorEjemplo,$ValorPredeterminado,$Tipo,$Mandatorio,$Deshabilitado,$Cedente,$Respuesta_Nivel3,$idCampo);
    echo json_encode($ToReturn);
?>