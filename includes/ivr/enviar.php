<?php 
	include_once("../functions/Functions.php");
	QueryPHP_IncludeClasses("db");

	$Queue   = $_POST["Queue"];
	$IVR_Table = "IVR_".$_POST["Queue"];
	$nombre = $_POST["nombre"];
	$canales = $_POST["canales"];
	$audio = $_FILES['audio']['tmp_name'];
	$TipoCategorias = $_POST["TipoCategorias"];
	$TipoTelefono = $_POST["TipoTelefono"];
	$duracion = $_POST['duracion'];

	//$TipoTelefono = implode(",",$TipoTelefono);
	$WhereTipoCategorias = "";
	switch($TipoCategorias){
		case "Colores":
			$WhereTipoCategorias = "ctf.color IN (".$TipoTelefono.") AND";
		break;
		case "Prioridad_Fonos":
			$WhereTipoCategorias = "f.Prioridad_Fono IN (".$TipoTelefono.") AND";
		break;
	}
	$db = new DB();
	$dbDiscador = new DB("discador");
	$focoConfig = getFocoConfig();
	$CodigoFoco = $focoConfig["CodigoFoco"];
	$query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '".$IVR_Table."'";
	$Exist = $dbDiscador->select($query);
	if(!$Exist){
		$cedente = $_SESSION['cedente'];
		$query = "INSERT INTO ivrConfig (cola,nombre,canales,Cedente,CodigoFoco,estatus,duracion) VALUES ('".$IVR_Table."','".$nombre."','".$canales."','".$cedente."','".$CodigoFoco."','0','".$duracion."')";
		$id_config = $dbDiscador->insert($query);
		if($id_config){
			$IpServidorDiscado = $focoConfig["IpServidorDiscado"];
			if (function_exists('curl_file_create')) {
				$audio = curl_file_create($audio);
			} else {
				$audio = '@' . realpath($audio);
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://".$IpServidorDiscado."/includes/ivr/storeAudio.php");
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'audio'         => $audio,
				'CodigoFoco'    => $CodigoFoco,
				'id_config'     => $id_config
			));
			$result = curl_exec($ch);
			curl_close($ch);
			if($result){
				$SqlCreate = "CREATE TABLE IF NOT EXISTS ".$IVR_Table." ( 
							id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
							Fono int,
							Rut int,
							Cedente  varchar(100),
							CodigoFoco varchar(100),
							llamado int DEFAULT 0,
							fecha date,
							hora time
						)";
				$Create = $dbDiscador->query($SqlCreate);
				$SqlTruncate = "TRUNCATE TABLE ".$IVR_Table;
				$Truncate = $dbDiscador->query($SqlTruncate);
				if($Truncate){
					$SqlRuts = "SELECT 
									f.formato_subtel AS Fono, f.Rut
								FROM 
									fono_cob f
								INNER JOIN 
									SIS_Categoria_Fonos ctf 
								ON 
									ctf.color = f.color
								WHERE
									".$WhereTipoCategorias."
									Rut IN (SELECT Rut FROM ".$Queue." GROUP BY Rut)";
					$Ruts = $db->select($SqlRuts);
					if($Ruts){
						$Array1000 = array();
						$Cont1000 = 0;
						$Array1000[$Cont1000] = array();
						foreach($Ruts as $RutArray){
							$Rut = $RutArray["Rut"];
							$Fonos = $RutArray["Fono"];
							$FonosArray = explode(",",$Fonos);
							foreach($FonosArray as $Fono){
								if($Fono != ""){
									$Value = "('".$Fono."','".$Rut."','".$cedente."','".$CodigoFoco."',NOW(),NOW())";
									array_push($Array1000[$Cont1000],$Value);
									if(count($Array1000[$Cont1000]) == 1000){
										$Cont1000++;
										$Array1000[$Cont1000] = array();
									}
								}
							}
						}
						if($Array1000){
							foreach($Array1000 as $ValuesArray){
								$Values = implode(",",$ValuesArray);
								$SqlInsert = "INSERT INTO ".$IVR_Table." (Fono,Rut,Cedente,CodigoFoco,fecha,hora) VALUES ".$Values;
								$Insert = $dbDiscador->query($SqlInsert);
							}
						}
					}
					echo '1';
				}
			}else{
				echo curl_error($ch);
			}
		}
	}else{
		echo '2';
	}
    
?>