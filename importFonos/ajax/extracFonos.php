<?php
	require_once('../../class/db/DB.php');
	$db = new DB();
	$doc = $_POST['doc'];
	if (substr($doc, strrpos($doc, ".")) == ".csv") {
		$contador = 0;
		$incorrecto = "INSERT INTO fonos_incorrectos (Rut, Fono, FechaRegistro, FechaActualizacion) VALUES ";
		$correcto = "INSERT INTO fonos_correctos (Rut, Fono, FechaRegistro, FechaActualizacion) VALUES ";
		$fp = fopen ($doc,"r");
		while ($data = fgetcsv ($fp, 1000, ";")) {
			$num = count ($data);
			for ($i=0; $i < $num; $i++) {
				$values[$i] = $data[$i];
			}

			$Quita56=str_replace("+56","",$values[$_POST['Fono']]);
			$ConvierteArray= explode(" ", $Quita56);
			$contar = count($ConvierteArray);
			$j = 0;
			$Codigo = isset($_POST['Codigo']) ? $values[$_POST['Codigo']] : "";
			while($j<$contar){
				$Fono = $ConvierteArray[$j];
				if($Codigo == NULL || $Codigo == ''){
					$ConsultaComuna = $db->select("SELECT Comuna FROM Direcciones WHERE Rut = ".$values[$_POST['Rut']]." LIMIT 1");
					$ComunaDirecciones = "";
					foreach($ComunaDirecciones as $row){
						$ComunaDirecciones = $row["Comuna"];
					}
					if($ComunaDirecciones != ""){
						$ConsultaCodigo  = $db->select("SELECT Codigo FROM Codigo_Area WHERE Comuna LIKE '%$ComunaDirecciones%' LIMIT 1");
						foreach($ComunaDirecciones as $row){
							$Codigo = $row["Codigo"];
						}
					}
					$arrayDepurador = Depurador($Codigo,$Fono,$values[$_POST['Rut']]);
					if (array_key_exists(0, $arrayDepurador)) {
						$incorrecto.= $arrayDepurador[0];
					}else{
						$correcto.= $arrayDepurador[1];
					}
				
				}else{
					$arrayDepurador = Depurador($Codigo,$Fono,$values[$_POST['Rut']]);
					if (array_key_exists(0, $arrayDepurador)) {
						$incorrecto.= $arrayDepurador[0];
					}else{
						$correcto.= $arrayDepurador[1];
					}
				}   
				$j++;
			}

			/*$array = Depurador($values[$_POST['Codigo']],$values[$_POST['Fono']],$values[$_POST['Rut']]);
			if (array_key_exists(0, $array)) {
				$incorrecto.= $array[0];
			}else{
				$correcto.= $array[1];
			}*/
		}
		echo $incorrecto = substr($incorrecto, 0, -1);
		echo $correcto = substr($correcto, 0, -1);
		$db->query($incorrecto);
		$db->query($correcto);
		echo "1";
	}else{
		require '../../plugins/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPExcel_IOFactory::load($doc);
		$sheetNames = $objPHPExcel->getSheetNames();
		$list = '';
		$incorrecto = "INSERT INTO fonos_incorrectos (Rut, Fono, FechaRegistro, FechaActualizacion) VALUES ";
		$correcto = "INSERT INTO fonos_correctos (Rut, Fono, FechaRegistro, FechaActualizacion) VALUES ";
		$objPHPExcel->setActiveSheetIndex(0);
		$numColumnI = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
		$numColumnI = PHPExcel_Cell::columnIndexFromString($numColumnI);
		$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		for ($i = 2; $i < $numRows; $i++) {
			for ($j=0; $j < $numColumnI; $j++) {
				$cell =$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i);
				$value= $cell->getValue();
				$array[$j] = $value;
			}

			$Quita56=str_replace("+56","",$array[$_POST['Fono']]);
			$ConvierteArray= explode(" ", $Quita56);
			$contar = count($ConvierteArray);
			$j = 0;
			$Codigo = isset($_POST['Codigo']) ? $array[$_POST['Codigo']] : "";
			while($j<$contar){
				$Fono = $ConvierteArray[$j];
				if($Codigo == NULL || $Codigo == ''){
					$ConsultaComuna = $db->select("SELECT Comuna FROM Direcciones WHERE Rut = ".$array[$_POST['Rut']]." LIMIT 1");
					$ComunaDirecciones = "";
					foreach($ComunaDirecciones as $row){
						$ComunaDirecciones = $row["Comuna"];
					}
					if($ComunaDirecciones != ""){
						$ConsultaCodigo  = $db->select("SELECT Codigo FROM Codigo_Area WHERE Comuna LIKE '%$ComunaDirecciones%' LIMIT 1");
						foreach($ComunaDirecciones as $row){
							$Codigo = $row["Codigo"];
						}
					}
					$arrayDepurador = Depurador($Codigo,$Fono,$array[$_POST['Rut']]);
					if (array_key_exists(0, $arrayDepurador)) {
						$incorrecto.= $arrayDepurador[0];
					}else{
						$correcto.= $arrayDepurador[1];
					}
				
				}else{
					$arrayDepurador = Depurador($Codigo,$Fono,$array[$_POST['Rut']]);
					if (array_key_exists(0, $arrayDepurador)) {
						$incorrecto.= $arrayDepurador[0];
					}else{
						$correcto.= $arrayDepurador[1];
					}
				}   
				$j++;
			}

			/*$array = Depurador($array[$_POST['Codigo']],$array[$_POST['Fono']],$array[$_POST['Rut']]);
			if (array_key_exists(0, $array)) {
				$incorrecto.= $array[0];
			}else{
				$correcto.= $array[1];
			}*/
		}
		echo $incorrecto = substr($incorrecto, 0, -1);
		echo $correcto = substr($correcto, 0, -1);
		$db->query($incorrecto);
		$db->query($correcto);
		echo "1";
	}

	function Depurador($Codigo,$Fono,$Rut){
		$ContarCodigo = strlen($Codigo);
		$Fono = ereg_replace("[^0-9]", "", $Fono);
		$Largo = strlen($Fono);
		$Fail = 0;
		$array = array();

		switch ($Largo) {
			case 9:
				$Fail = 1;
			break;
			case 8:
				$Consulta = substr($Fono,0,2);
				$CodigoArea = 0;
				$ConsultaCodigoArea = $db->select("SELECT Codigo FROM Codigo_Area WHERE Codigo=$Consulta LIMIT 1");
				foreach($ConsultaCodigoArea as $row){
					$CodigoArea = $row["Codigo"];
				}
				if($Consulta==$CodigoArea && $Codigo==''){
					if($CodigoArea == $Codigo){
						$part2 = substr($Fono, 2, 10);
						$Fono = $Consulta."2".$part2;
						$Fail = 1;
					}else{
						$part2 = substr($Fono, 2, 10);
						$Fono1  = $Consulta."2".$part2;
						$Fono2  = "9".$Fono;
						$FonoArray = array();
						array_push($FonoArray,$Fono1);
						array_push($FonoArray,$Fono2);
						$Fail = 1;
						$contar = count($FonoArray);
						$i = 0;
						while($i<$contar){
							$Fono = $FonoArray[$i];
							$i++;
						}
					}
				} else{
					$PrimerDigito = substr($Fono, 0, 1);
					if($PrimerDigito>=4){
						$Fono = "9".$Fono;
						$Fail = 1;
					}
				else{
						$Fono = "2".$Fono;
						$Fail = 1;
					}  
				}
			break;
			case 7:
				if($Codigo){
					$Fono = $Codigo.$Fono;
					$Fail = 1;
				}
			break;
			case 6:
				if($Codigo){
					if($ContarCodigo==2){
						$Fono  = $Codigo."2".$Fono;
						$Fail = 1;
					}elseif($ContarCodigo==1){
						$Fono = "222".$Fono;
						$Fail = 1;
					}else{
						$Fail = 0;
					}
				}
				else
				{
					$Fail = 0;
				}
			break;
			default:
				$Fail = 0;
			break;
		}
		if($Fail==0){
			if(is_array($Fono)){
				$array[0] ="";
				for ($i=0; $i < count($Fono); $i++) {
					$array[0].= "('".$Rut."','".$Fono[$i]."','".date("Y-m-d")."','".date("Y-m-d")."'),";
				}
			}else{
				$array[0] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
			}
		}else{
			if(is_array($Fono)){
				for ($i=0; $i < count($Fono); $i++) {
					$array[0] ="";
					if (stripos($Fono[$i], 'NULL') === 0) {
						$array[0].= "('".$Rut."','".$Fono[$i]."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}else{
						$array[1] = "('".$Rut."','".$Fono[$i].",'".date("Y-m-d")."','".date("Y-m-d")."'','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}
				}
			}else{
				if (stripos($Fono, 'NULL') === 0) {
					$array[0] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
				}else{
					$array[1] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
				}
			}
		}
		return $array;
	}
 ?>




