<?php
    if(!isset($_SESSION)){
        session_start();
    }
	include("../../class/db/DB.php");
    include("../../class/reclutamiento/reclutamiento.php");
    $db = new DB();
    $ReclutamientoClass = new Reclutamiento();
    $SqlDatosGenerales = "SELECT * FROM datos_generales_reclutamiento WHERE IdUsuarioReclutamiento = '".$_SESSION["idUsuario_reclutamiento"]."'";
    $DatosGenerales = $db->select($SqlDatosGenerales);
    $FieldsArray = array();
    if(count($DatosGenerales) > 0){
        $DatosGenerales = $DatosGenerales[0];
        
        $CamposEstaticos = $ReclutamientoClass->CamposEstaticos();
        foreach($CamposEstaticos as $Campo){
            $ArrayTmp = array();
            $ArrayTmp["Codigo"] = $Campo["Codigo"];
            $ArrayTmp["Tipo"] = $Campo["Tipo"];
            $ArrayTmp["Valor"] = utf8_encode($DatosGenerales[$Campo["CampoDB"]]);
            array_push($FieldsArray,$ArrayTmp);
        }

        $RespuestasCamposDinamicos = $ReclutamientoClass->RespuestasCamposDinamicos();
        foreach($RespuestasCamposDinamicos as $Campo){
            $ArrayTmp = array();
            $ArrayTmp["Codigo"] = $Campo["Codigo"];
            $ArrayTmp["Tipo"] = $Campo["Tipo"];
            $ArrayTmp["Valor"] = utf8_encode($Campo["Valor"]);
            array_push($FieldsArray,$ArrayTmp);
        }
    }
    echo json_encode($FieldsArray);
?>