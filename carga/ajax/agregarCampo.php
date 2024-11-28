<?php
	require_once('../../class/db/DB.php');
	$db = new DB();
	$Tabla = $_POST['Tabla'];
	$Campo = $_POST['Campo'];
	$Tipo = $_POST['Tipo'];
	$Separador = strrpos($Tabla, "_");
	$Tabla =  substr($Tabla,0,$Separador);

	$query = "	SELECT 
					COUNT(*) AS Cantidad
				FROM 
					SYSOBJECTS  
				INNER JOIN 
					SYSCOLUMNS ON SYSOBJECTS.ID = SYSCOLUMNS.ID 
				WHERE 
					SYSOBJECTS.NAME = '".$Tabla."' 
				AND 
					SYSCOLUMNS.NAME = '".$Campo."'";
	$select = $db->select($query);

	$Cantidad = $select[0]['Cantidad'];

	if($Cantidad == 0){
		$Tabla = $_POST['Tabla'];
		$ToReturn = $db->query("ALTER TABLE ".$Tabla." ADD ".$Campo." ".$Tipo);
		if ($ToReturn) {
			$Separador = strrpos($Tabla, "_");
			$Tabla =  substr($Tabla,0,$Separador);
			switch($Tabla){
				case 'Deuda':
					$ToReturn = $db->query("ALTER TABLE Deuda_Historico ADD ".$Campo." ".$Tipo);
					$ToReturn = $db->query("ALTER TABLE ".$Tabla." ADD ".$Campo." ".$Tipo);
				break;
				case 'Persona':
					$ToReturn = $db->query("ALTER TABLE ".$Tabla." ADD ".$Campo." ".$Tipo);
					$ToReturn = $db->query("ALTER TABLE ".$Tabla."_Periodo ADD ".$Campo." ".$Tipo);
				break;
				case 'pagos_deudas':
					$ToReturn = $db->query("ALTER TABLE ".$Tabla." ADD ".$Campo." ".$Tipo);
				break;
				default:
					$ToReturn = $db->query("ALTER TABLE ".$Tabla." ADD ".$Campo." ".$Tipo);
					$ToReturn = $db->query("ALTER TABLE ".$Tabla."_cedente ADD ".$Campo." ".$Tipo);
				break;
			}
			$CanAddColumn = false;
			$idTabla = "";
			switch($Tabla){
				case "Deuda":
					$CanAddColumn = true;
					$Cedente = "1";
					$idTabla = "2";
				break;
				case "Persona":
					$CanAddColumn = true;
					$Cedente = "0";
					$idTabla = "1";
				break;
				case "fono_cob":
					$CanAddColumn = true;
					$Cedente = "0";
					$idTabla = "50";
				break;
				case "Direcciones":
					$CanAddColumn = true;
					$Cedente = "0";
					$idTabla = "3";
				break;
				case "Mail":
					$CanAddColumn = true;
					$Cedente = "0";
					$idTabla = "4";
				break;
			}
			if($CanAddColumn){
				$sqlInsertColumnEstrategia = "insert into SIS_Columnas_Estrategias (columna,id_tabla,Id_Cedente,Cedente) values ('".$Campo."','".$idTabla."','".$_SESSION["cedente"]."','".$Cedente."')";
				$InsertColumnEstrategia = $db->query($sqlInsertColumnEstrategia);
			}
			
		}
		if($ToReturn){
			$ToReturn = array();
			$ToReturn["result"] = true;
			$ToReturn["message"] = "Campo creado satisfactoriamente";
		}else{
			$ToReturn = array();
			$ToReturn["result"] = false;
			$ToReturn["message"] = "Error al crear el campo " . $Campo . " en la tabla " . $Tabla;
		}
	}else{
		$ToReturn = array();
		$ToReturn["result"] = false;
		$ToReturn["message"] = "El campo " . $Campo . " ya existe en la tabla " . $Tabla;
	}
	echo json_encode($ToReturn);
 ?>