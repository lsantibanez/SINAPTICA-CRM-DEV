<?php
  //  include_once("../../functions/Functions.php");
  //  Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_campos_gestion.php");
    
    $Codigo = $_POST["Codigo"];
    $Titulo = $_POST["Titulo"];
    $ValorEjemplo = $_POST["ValorEjemplo"];
    $ValorPredeterminado = $_POST["ValorPredeterminado"];
    $Tipo = $_POST["Tipo"];
    $Mandatorio = $_POST["Mandatorio"];
    $Deshabilitado = $_POST["Deshabilitado"];
    if(isset($_POST["ArrayOpciones"])){
        $ArrayOpciones = $_POST["ArrayOpciones"];
    }else{
        $ArrayOpciones = '';
    }
    $Cedente = $_POST["Cedente"];
    $Respuesta_Nivel3 = $_POST["Respuesta_Nivel3"];
    $ConfCamposGestion = new ConfCamposGestion();
    $ToReturn = $ConfCamposGestion->CrearCampo($Codigo,$Titulo,$ValorEjemplo,$ValorPredeterminado,$Tipo,$Mandatorio,$Deshabilitado,$ArrayOpciones,$Cedente,$Respuesta_Nivel3);
    echo json_encode($ToReturn);
?>