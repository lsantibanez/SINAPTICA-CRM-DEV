<?php
	if(!isset($_SESSION)){
        session_start();
    }
	include("../../class/db/DB.php");
	include("../../class/reclutamiento/reclutamiento.php");
	
	$db = new DB();
	$ReclutamientoClass = new Reclutamiento();
	$ArrayCampos = $_POST["ArrayCampos"];
	$Return = $ReclutamientoClass->EliminarRespuestasCampos();
	if($Return["result"]){
		foreach($ArrayCampos as $Campo){
			$Codigo = $Campo["Codigo"];
			$Valor = $Campo["Valor"];
			$Dinamico = $Campo["Dinamico"];
			$CampoDB = $Campo["CampoDB"];
			$Disabled = $Campo["Disabled"];
	
			switch($Dinamico){
				case "0":
					//Actualizar Campo
					$ReclutamientoClass->ActualizarCampoEstatico($CampoDB,$Valor);
				break;
				case "1":
					//Agregar Rspuestas nuevas
					$ReclutamientoClass->RegistrarRespuestasCampos($Codigo,$Valor);
				break;
			}
		}
	}
?>