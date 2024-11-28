<?php 
	include_once("../functions/Functions.php");
	QueryPHP_IncludeClasses("db");

	$Queue   = $_POST["Queue"];
	$BOT_Table = "BOT_".$_POST["Queue"];
	$nombre = $_POST["nombre"];
	$canales = $_POST["canales"];
	$dialplan = $_POST["dialplan"];
	$id_voz = $_POST["id_voz"];
	$TipoCategorias = $_POST["TipoCategorias"];
	$TipoTelefono = $_POST["TipoTelefono"];

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
	$url_ip = $focoConfig["IpServidorDiscadoAux"];
	$query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '".$BOT_Table."'";
	$Exist = $dbDiscador->select($query);
	if(!$Exist){
		$cedente = $_SESSION['cedente'];
		$query = "INSERT INTO BT_config (tabla,nombre,canales,cartera,nombre_exten,estado,dialplan,id_voz,url_ip) VALUES ('".$BOT_Table."','".$nombre."','".$canales."','".$cedente."','".$CodigoFoco."','0','".$dialplan."','".$id_voz."','".$url_ip."')";
		$Insert = $dbDiscador->insert($query);
		if($Insert){
			$SqlCreate = "CREATE TABLE IF NOT EXISTS ".$BOT_Table." ( 
						id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
						Fono int,
						Rut int,
						Nombre_Completo varchar(100),
						Deuda decimal(20,2),
						Cedente  varchar(100),
						CodigoFoco varchar(100),
						llamado int DEFAULT 0,
						fecha date,
						hora time
					) ENGINE=MyISAM";
			$Create = $dbDiscador->query($SqlCreate);
			$SqlTruncate = "TRUNCATE TABLE ".$BOT_Table;
			$Truncate = $dbDiscador->query($SqlTruncate);
			if($Truncate){
				$SqlRuts = "SELECT 
								f.formato_subtel AS Fono, f.Rut, p.Nombre_Completo, SUM(d.Deuda) as Deuda
							FROM 
								fono_cob f
							INNER JOIN 
								SIS_Categoria_Fonos ctf 
							ON 
								ctf.color = f.color
							INNER JOIN 
								Persona p 
							ON 
								f.Rut = p.Rut
							INNER JOIN 
								Deuda d 
							ON 
								f.Rut = d.Rut
							WHERE
								".$WhereTipoCategorias."
							f.Rut 
								IN (SELECT Rut FROM ".$Queue." GROUP BY Rut)
							GROUP BY 
								f.Rut";
				$Ruts = $db->select($SqlRuts);
				if($Ruts){
					$Array1000 = array();
					$Cont1000 = 0;
					$Array1000[$Cont1000] = array();
					foreach($Ruts as $RutArray){
						$Rut = $RutArray["Rut"];
						$Fonos = $RutArray["Fono"];
						$Nombre_Completo = $RutArray["Nombre_Completo"];
						$Deuda = $RutArray["Deuda"];
						$FonosArray = explode(",",$Fonos);
						foreach($FonosArray as $Fono){
							if($Fono != ""){
								$Value = "('".$Fono."','".$Rut."','".$Nombre_Completo."','".$Deuda."','".$cedente."','".$CodigoFoco."',NOW(),NOW())";
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
							$SqlInsert = "INSERT INTO ".$BOT_Table." (Fono,Rut,Nombre_Completo,Deuda,Cedente,CodigoFoco,fecha,hora) VALUES ".$Values;
							$Insert = $dbDiscador->query($SqlInsert);
						}
					}
				}
				echo '1';
			}
		}
	
	}else{
		echo '2';
	}
    
?>