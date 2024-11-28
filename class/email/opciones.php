<?php  

class opciones{

	function __construct(){
	}
	public function estrategias($tipo){
		$db = new DB();
		$cedente = $_SESSION['cedente'];
		$query = "SELECT TABLE_NAME as tabla FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'foco' AND LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 2 AND TABLE_NAME LIKE 'QR_".$_SESSION['cedente']."%'";
		$rows = $db->select($query); 
		$estrategias = '';
		if($rows) { 
		    foreach($rows as $row){
				$tabla = $row["tabla"];
				$cad = strrchr($tabla, "_");
				$idQueEstra = substr($cad, 1);
				$query = "SELECT id_estrategia, cola FROM SIS_Querys_Estrategias WHERE id = '".$idQueEstra."' AND Id_Cedente = '".$cedente."'";
				$SIS_Querys_Estrategias = $db->select($query);
				if($SIS_Querys_Estrategias){
					$SIS_Querys_Estrategias = $SIS_Querys_Estrategias[0];
					$idEstra = $SIS_Querys_Estrategias['id_estrategia'];
					$nomCola = $SIS_Querys_Estrategias['cola'];
					$query = "SELECT nombre FROM SIS_Estrategias WHERE id = '".$idEstra."' AND tipo = '".$tipo."'";
					$SIS_Estrategias = $db->select($query);
					if($SIS_Estrategias){
						$SIS_Estrategias = $SIS_Estrategias[0];
						$nomEstra = $SIS_Estrategias['nombre'];
						$nom = $nomEstra . ' - ' . $nomCola;
						$estrategias .= '<option value="'.$tabla.'">'.$nom.'</option>';
					}
				}
		    }
		}	
		return $estrategias;
	}

	public function general(){
		$db = new DB();
		$mandante = $_SESSION['mandante'];
		$query = "SELECT t.id, t.nombre FROM EMAIL_Template t INNER JOIN mandante_cedente mc ON t.id_cedente = mc.Id_Cedente WHERE mc.Id_Mandante = '".$mandante."'";
		$templates = $db->select($query); 
		$list='';
		if($templates) { 
		    foreach($templates as $template){
		        $list .= '<option value="'.$template['id'].'">'.$template['nombre'].'</option>';
		    }
		}
		return $list;
	}

	public function getTemplatesSMS(){
		$db = new DB();
		$query_temp = $db->select("SELECT 
										id, Nombre 
									FROM 
										SMS_Template 
									WHERE 
										id_cedente = '" . $_SESSION['cedente'] . "' 
										AND id_usuario = '" . $_SESSION['id_usuario'] . "'"); 
		$list='';
		if(count($query_temp)>0) { 
		    foreach($query_temp as $row_temp){
		        $list.= '<option value="' . $row_temp['id'] . '">' . $row_temp['Nombre'] . '</option>';
		    }
		}
		return $list;
	}

	public function getColores(){
		$db = new DB();
		$query_temp = $db->select("SELECT id, comentario FROM SIS_Colores"); 
		$list='';
		if(count($query_temp)>0) { 
		    foreach($query_temp as $row_temp){
		        $list.= '<option value="' . $row_temp['id'] . '">' . $row_temp['comentario'] . '</option>';
		    }
		}
		return $list;
	}

	public function templates(){
		$db = new DB();
		$query_select = $db->select("SELECT * FROM EMAIL_Template WHERE id_cedente='".$_SESSION['cedente']."'");

	    $templates = '<tr><td colspan="2">No hay templates guardadas.</td></tr>';

	    if(count($query_select)> 0){
	        $templates = '';
	        foreach($query_select as $row_select){
				$InputFactura = "";
				// if($_SESSION["tipoSistema"] == "1"){
				// 	$InputCheckFactura = "";
				// 	$InputCheck = "";
				// 	if($row_select["factura"] == "1"){
				// 		$UsuariosArray = explode(",",$row_select["id_usuario"]);
				// 		if(in_array($_SESSION["id_usuario"],$UsuariosArray)){
				// 			$InputCheckFactura = "checked = ''";
				// 		}
				// 	}
				// 	$InputFactura = "<td><label class='form-checkbox form-normal form-primary inputCheckFactura' ><input type='checkbox' ".$InputCheckFactura."></label></td>";
				// }
	            $templates .= '<tr data-id="'.$row_select["Id"].'"><td>'.$row_select["Nombre"].'</td>'.$InputFactura.'<td><button type="button" data-id="'.$row_select["Id"].'" class="use-template fa fa-pencil-square-o btn btn-success btn-icon icon-lg"></button> <button data-id="'.$row_select["Id"].'" class="delete-template fa fa-trash btn-danger btn btn-icon icon-lg" type="button"></button></td></tr>';
	        }
	    }

		return $templates;
	}

	public function templatesSMS(){
		$db = new DB();

		$query_select = $db->select("SELECT * 
										FROM 
											SMS_Template 
										WHERE 
											id_cedente='" . $_SESSION['cedente'] . "' 
											AND id_usuario = '" . $_SESSION['id_usuario'] . "' ");

	    $templates = '<tr><td colspan="2">No hay templates guardadas.</td></tr>';

	    if(count($query_select)> 0){
	        $templates = '';
	        foreach($query_select as $row_select){
	            $templates .= '<tr data-id="'.$row_select["id"].'">
									<td>'.$row_select["Nombre"].'</td>
									<td id="sms">
										<button type="button" data-id="'.$row_select["id"].'" class="use-template-sms fa fa-pencil-square-o btn btn-success btn-icon icon-lg"></button> 
										<button data-id="'.$row_select["id"].'" class="delete-template btn fa fa-trash btn-danger btn-icon icon-lg" type="button"></button>
									</td>
								</tr>';
	        }
	    }
		return $templates;
	}

	public function variables(){
		$db = new DB();
		$query_select = $db->select("SELECT * FROM Variables where id_cedente='".$_SESSION['cedente']."'");

	    $variables = '<tr><td colspan="2">No hay variables guardadas.</td></tr>';

	    if(count($query_select)> 0){
	        $variables = '';
	        foreach($query_select as $row_select){
	            $variables .= '<tr data-id="'.$row_select["id"].'"><td>'.utf8_encode($row_select["variable"]).'</td><td id="email"><button type="button" data-id="'.$row_select["id"].'" class="edit-var fa fa-pencil-square-o btn btn-success btn-icon icon-lg"></button> <button data-id="'.$row_select["id"].'" class="delete-var fa fa-trash btn btn-danger btn-icon icon-lg" type="button"></button></td></tr>';
	        }
	    }

		return $variables;
	}

	public function variablesSMS(){
		$db = new DB();
		$query_select = $db->select("SELECT * FROM VariablesSMS WHERE id_cedente='".$_SESSION['cedente']."'");

	    $variables = '<tr><td colspan="2">No hay variables guardadas.</td></tr>';

	    if(count($query_select)> 0){
	        $variables = '';
	        foreach($query_select as $row_select){
	            $variables .= '<tr data-id="'.$row_select["id"].'"><td>'.utf8_encode($row_select["variable"]).'</td><td id="sms"><button type="button" data-id="'.$row_select["id"].'" class="edit-var btn fa fa-pencil-square-o btn btn-success btn-icon icon-lg"></button> <button data-id="'.$row_select["id"].'" class="delete-var fa fa-trash btn btn-danger btn-icon icon-lg" type="button"></button></td></tr>';
	        }
	    }

		return $variables;
	}

	public function campos($tabla){
		$db = new DB();
		$query_campos = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = '".$tabla."'"); 
		$campos = '';
		if(count($query_campos)>0) { 
		    foreach($query_campos as $rows){
		        $campos .= '<option value="'.$rows["Field"].'">'.$rows["Field"].'</option>';
		    }
		}
		return $campos;
	}

	public function configvalues($Cedente = "",$tipoModulo=""){
		$db = new DB();
		$Cedente = $Cedente != "" ? $Cedente : $_SESSION['cedente'];

		$WhereIdUsuario = $tipoModulo == "2" ? " AND id_usuario = '".$_SESSION["id_usuario"]."'" : "";

		$Query = "SELECT * FROM control_envio WHERE tipoModulo = '".$tipoModulo."' AND Id_Cedente = '".$Cedente."' ".$WhereIdUsuario;
		$consulta = $db->select($Query);
		$result = array();
		
		if(count($consulta)>0){
			$row_consulta = $consulta[0];

			$protocolo = $row_consulta['protocolo'];
			$prot1 = $protocolo == 1 ? 'checked=""' : '';
			$prot2 = $protocolo == 2 ? 'checked=""': '';
			$secure = $row_consulta['secure'];
			$sec1 = $secure == 1 ? 'checked=""': '';
			$sec2 = $secure == 2 ? 'checked=""': '';
			$sec3 = $secure == 0 ? 'checked=""': '';
			$host = $row_consulta['host'];
			$port = $row_consulta['puerto'];
			$email = $row_consulta['email'];
			$pass = $row_consulta['contrasena'];
			$from_email = $row_consulta['from_email'];
			$from_name = $row_consulta['from_name'];
			$ConfirmReadingTo = $row_consulta['ConfirmReadingTo'];
			$result["result"] = true;
		} else {
			$prot1 = '';
			$prot2 = '';
			$sec1 = '';
			$sec2 = '';
			$sec3 = '';
			$host = '';
			$port = '';
			$email = '';
			$pass = '';
			$from_email = '';
			$from_name = '';
			$protocolo = '';
			$secure = '';
			$ConfirmReadingTo = '';
			$result["result"] = false;
		}
		$result["ProtocolSMTP"] = $prot1;
		$result["ProtocolPOP3"] = $prot2;
		$result["SecureSSL"] = $sec1;
		$result["SecureTLS"] = $sec2;
		$result["SecureNone"] = $sec3;
		$result["Host"] = $host;
		$result["Port"] = $port;
		$result["Email"] = $email;
		$result["Pass"] = $pass;
		$result["FromEmail"] = $from_email;
		$result["FromName"] = $from_name;
		$result["protocolo"] = $protocolo;
		$result["secure"] = $secure;
		$result["ConfirmReadingTo"] = $ConfirmReadingTo;

		//$result = array("ProtocolSMTP"=>$prot1,"ProtocolPOP3"=>$prot2,"SecureSSL"=>$sec1,"SecureTLS"=>$sec2,"SecureNone"=>$sec3,"Host"=>$host,"Port"=>$port,"Email"=>$email,"Pass"=>$pass,"FromEmail"=>$from_email,"FromName"=>$from_name);

		return $result;

	}
	public function getTemplateFactura($idTemplate){
		$db = new DB();
		$query = "SELECT Id, Template, Nombre, Asunto FROM EMAIL_Template WHERE Id = '".$idTemplate."'";
		$row = $db->select($query);
		$ToReturn = array();
		$ToReturn["result"] = false;
	    if(count($row) > 0){
			$ToReturn["Template"] = $row[0]["Template"];
			$ToReturn["nombre"] = $row[0]["Nombre"];
			$ToReturn["asunto"] = $row[0]["Asunto"];
			$ToReturn["result"] = true;
	    }
		return $ToReturn;
	}
}


?>