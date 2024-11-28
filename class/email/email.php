<?php 

class Email
{
	//Versión 2
	public function SendMail($html,$subject,$email_list,$info,$cedente,$tipoModulo){
		if(file_exists("../../class/global/cedente.php")){
			include_once('../../class/global/cedente.php');
		}else{
			include_once('/var/www/html/class/global/cedente.php');
		}
		if(!class_exists('DB')){
			if(file_exists("../../class/db/DB.php")){
				include_once('../../class/db/DB.php');
			}else{
				include_once('/var/www/html/class/db/DB.php');
			}
		}
		//include('opciones.php'); 
		$CedenteClass = new Cedente();
		$config = new opciones; 
		$mail = new PHPMailer();  
		
		$Mandante = $CedenteClass->getMandanteFromCedente($cedente);
		$Conf = $config->configvalues($cedente,$tipoModulo);

		$ToReturn = false;
		if($Conf["ProtocolSMTP"] != ""){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		}
		if($Conf["SecureSSL"] != ""){
			$mail->SMTPSecure = "ssl";
		}

		if($Conf["SecureTLS"] != ""){
			$mail->SMTPSecure = "TLS";
		}

		if($Conf["ConfirmReadingTo"] != ""){
			if(filter_var($Conf["ConfirmReadingTo"], FILTER_VALIDATE_EMAIL)) {
				$mail->ConfirmReadingTo = $Conf["ConfirmReadingTo"];
			}
		}
		
		$mail->Host = $Conf["Host"]; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = $Conf["Port"];  
		$mail->Username = $Conf["Email"];  
		$mail->Password = $Conf["Pass"];  
		$mail->From = $Conf["FromEmail"];   
		$mail->FromName = $Conf["FromName"];  
		//$mail->Subject = $subject;  
		$mail->IsHTML(true);  

		if(isset($info['adjuntos'])){
			$adjuntos = $info['adjuntos'];
		}else{
			$adjuntos = false;
		}
		if(isset($info['Archivos'])){
			$Archivos = $info['Archivos']; 
		}else{
			$Archivos = ''; 
		}

		if($Archivos){
			foreach($Archivos as $Archivo){
				$mail->addAttachment($Archivo['tmp_name'],$Archivo['name']);  
			}
		}
		if(isset($info['variables'])){
			$variables = $info['variables'];
		}else{
			$variables = false;
		}

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){
					$find = array('[correo]');
					$replace = array($email);
					if($variables){
						foreach ($variables as $var){
							$find[]='['.$var.']';
							$replace[] = $info[$email][$var];
						}
					}
					$content = str_replace($find, $replace, $html);
					$subject = str_replace($find, $replace, $subject);
					$mail->Subject = $subject;
					$content = html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $content), ENT_QUOTES, 'UTF-8');
					$content = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $content);
					$mail->MsgHTML($content);   

					if(isset($adjuntos[$email])){
						if(is_array($adjuntos[$email])){
							foreach ($adjuntos[$email] as $adjunto) {
								$facturas = explode(',', $adjunto);
								foreach($facturas as $factura){
									$archivo = '../../facturas/'.$Mandante["id"]."/".$cedente."/".$info[$email]["Rut"]."/".$factura.'.pdf';
									if(file_exists($archivo)){
										$mail->addAttachment($archivo);  
									}
								}
							}
						}else{
							$facturas = explode(',', $adjuntos[$email]);
							foreach($facturas as $factura){
								$archivo = '../../facturas/'.$Mandante["id"]."/".$cedente."/".$info[$email]["Rut"]."/".$factura.'.pdf';
								if(file_exists($archivo)){
									$mail->addAttachment($archivo);  
								}
							}
						}
						//$mail->AddAddress('soporte@Soporte.cl'); 
						$mail->AddAddress($email); 
						//$mail->CharSet = 'UTF-8';
						$mail->send();
						$mail->ClearAllRecipients();
						$mail->clearAttachments();
					}else{
						//$mail->AddAddress('soporte@Soporte.cl'); 
						$mail->AddAddress($email); 
						//$mail->CharSet = 'UTF-8';
						$mail->send();
						$mail->ClearAllRecipients();
					}
				}
			}
			$ToReturn = true;
		} else { 
			if($email_list != ""){
				$find = array('[correo]');
				$replace = array($email_list);
				if(is_array($adjuntos[$email_list])){
					foreach ($adjuntos[$email_list] as $adjunto) {
						$facturas = explode(',', $adjunto);
						foreach($facturas as $factura){
							$archivo = '../../facturas/'.$Mandante["id"]."/".$cedente."/".$info[$email_list]["Rut"]."/".$factura.'.pdf';
							if(file_exists($archivo)){
								$mail->addAttachment($archivo);  
							}
						}
					}
				}else{
					if(is_array($adjuntos[$email_list])){
						$facturas = explode(',', $adjuntos[$email_list]);
						foreach($facturas as $factura){
							$archivo = '../../facturas/'.$Mandante["id"]."/".$cedente."/".$info[$email_list]["Rut"]."/".$factura.'.pdf';
							if(file_exists($archivo)){
								$mail->addAttachment($archivo);  
							}
						}
					}
					/* $facturas = explode(',', $adjuntos[$email_list]);
					foreach($facturas as $factura){
						$archivo = '../../facturas/'.$Mandante["id"]."/".$cedente."/".$info[$email_list]["Rut"]."/".$factura.'.pdf';
						if(file_exists($archivo)){
							$mail->addAttachment($archivo);  
						}
					} */
				}
				if($variables){
					foreach ($variables as $var){
						$find[] = '['.$var.']';
						$replace[] = $info[$email_list][$var];
					}
				}
				$content = str_replace($find, $replace, $html);
				$subject = str_replace($find, $replace, $subject);
				$mail->Subject = $subject;
				$content = html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $content), ENT_QUOTES, 'UTF-8');
				$content = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $content);
				$mail->MsgHTML($content);
				$mail->AddAddress($email_list);
				$mail->AddCC($Conf["Email"]);
				if(!$mail->Send()){   
					echo "Error al enviar, causa: " .$mail->ErrorInfo;  
					$ToReturn = false;
				}else{   
					$ToReturn = true;
				}
			}else{
				$ToReturn = false;
			}
		}
		return $ToReturn;	
	}
	public function SendMailGeneral($html,$subject,$email_list,$cedente,$tipoModulo,$CC = ""){
		if(file_exists("../../class/global/cedente.php")){
			include_once('../../class/global/cedente.php');
		}else{
			include_once('/var/www/html/class/global/cedente.php');
		}
		if(!class_exists('DB')){
			if(file_exists("../../class/db/DB.php")){
				include_once('../../class/db/DB.php');
			}else{
				include_once('/var/www/html/class/db/DB.php');
			}
		}
		//include('opciones.php'); 
		$CedenteClass = new Cedente();
		$config = new opciones; 
		$mail = new PHPMailer();  
		
		$Mandante = $CedenteClass->getMandanteFromCedente($cedente);
		$Conf = $config->configvalues($cedente,$tipoModulo);

		$ToReturn = false;
		if($Conf["ProtocolSMTP"] != ""){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		}
		if($Conf["SecureSSL"] != ""){
			$mail->SMTPSecure = "ssl";
		}

		if($Conf["SecureTLS"] != ""){
			$mail->SMTPSecure = "TLS";
		}

		if($Conf["ConfirmReadingTo"] != ""){
			if(filter_var($Conf["ConfirmReadingTo"], FILTER_VALIDATE_EMAIL)) {
				$mail->ConfirmReadingTo = $Conf["ConfirmReadingTo"];
			}
		}
		
		$mail->Host = $Conf["Host"]; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = $Conf["Port"];  
		$mail->Username = $Conf["Email"];  
		$mail->Password = $Conf["Pass"];  
		$mail->From = $Conf["FromEmail"];   
		$mail->FromName = $Conf["FromName"];  
		//$mail->Subject = $subject;  
		$mail->IsHTML(true);

		$mail->Subject = $subject;
		$content = html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $html), ENT_QUOTES, 'UTF-8');
		$html = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $html);
		$mail->MsgHTML($html);

		$Emails = explode(";",$email_list);
		foreach($Emails as $Email){
			$Email = trim($Email);
			if($Email != ""){
				$mail->AddAddress($Email); 
			}
		}
		$Emails = explode(";",$CC);
		foreach($Emails as $Email){
			$Email = trim($Email);
			if($Email != ""){
				$mail->AddCC($Email); 
			}
		}

		if(!$mail->Send()){   
			echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			$ToReturn = false;
		}else{   
			$ToReturn = true;
		}
		
		return $ToReturn;	
	}

	public function SendNotification($html,$subject,$email_list,$FromName = "eMAIL foCO"){ 		
		$ToReturn = FALSE;
		$mail = new PHPMailer();  
		$mail->IsSMTP();
		$mail->SMTPAuth = true;  
		//$mail->SMTPSecure = "ssl";   
		$mail->Host = "mail.Soporte.cl"; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = 25;  
		$mail->Username = "redes@Soporte.cl";  
		$mail->Password = "M9a7r5s3A";  
		$mail->From = "redes@Soporte.cl";  		
		$mail->FromName = $FromName;  
		$mail->Subject = $subject; 
		$mail->IsHTML(true);
		$mail->MsgHTML($html); 
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){   
					$mail->AddAddress($email); 
					//$mail->CharSet = 'UTF-8';
			   		$mail->send();
			   		$mail->ClearAllRecipients();  
				}
			}
			$ToReturn = TRUE;
		} else { 
			if( $email_list != ""){   
			 	$mail->AddAddress($email_list);   
			}  
			if(!$mail->Send()){   
			  	echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			  	$ToReturn = FALSE;
			}else{   
			  	$ToReturn = TRUE;
		  	} 
		}
		return $ToReturn;
	
	}

	public function get_var_value($rut,$var,$cedente){

    	$db = new Db();

		$return = false;

		$fields_variable = "SELECT * FROM Variables WHERE variable= '".$var."' and id_cedente='".$cedente."'";

		$row = $db->select($fields_variable);

		if(count($row)>0){

			$row_var = $row[0];

			$tabla = $row_var['tabla'];
			$campos = $row_var['campo'];
			$operacion = $row_var['operacion'];
			$FieldListArray = explode("$&$",$campos);
			$array_campos = explode(',', $FieldListArray[0]);
			$cedente = ($tabla == 'Deuda') ? " AND Id_Cedente = '".$cedente."'" : "";
			$OrderBy = "";

			if($operacion == ''){
				$CamposSelect = "";
				if((count($array_campos) > 1) || (strpos($FieldListArray[0],"|") !== FALSE)){
					$ArrayCampos = array();
					$OrderBy = count($FieldListArray) > 1 ? " ORDER BY ".$FieldListArray[1] : "";
					if(count($array_campos) > 0){
						foreach($array_campos as $campoTmp){
							$campoTmpArray = explode("|",$campoTmp);
							array_push($ArrayCampos,$campoTmpArray[0]);
						}
					}
					$CamposSelect = implode(",",$ArrayCampos);
				}else{
					$CamposSelect = $FieldListArray[0];
				}
				$consulta_valores = "SELECT ".$CamposSelect." FROM ".$tabla." WHERE Rut='".$rut."'".$cedente." ".$OrderBy;

			} else{
				$consulta_valores = "SELECT ".$operacion."(".$FieldListArray[0].") AS ".$FieldListArray[0]." FROM ".$tabla." WHERE Rut='".$rut."'".$cedente;
			}
			
			$valores = $db->select($consulta_valores);
			
			if((count($array_campos) > 1) || (strpos($FieldListArray[0],"|") !== FALSE)){
				$tabla = '<table width="700" style="border-spacing: 0px;">
				<thead>
					<tr style="background-color: #5fa2dd; color: #FFFFFF; text-align: center;">';
				if(count($array_campos) > 0){
					foreach ($array_campos as $campo) {
						//$tabla .= '<th>'.ucfirst(str_replace('_',' ',$campo)).'</th>';
						$campoTmpArray = explode("|",$campo);
						if(isset($campoTmpArray[1])){
							$tabla .= '<th>'.$campoTmpArray[1].'</th>';
						}
					}
				}

				$tabla .= '</tr>
				</thead>
				<tbody>';
				if($valores){
					foreach($valores as $valor){
						$tabla .= '<tr>';
						if(count($array_campos) > 0){
							foreach ($array_campos as $campo) {
								$campoTmpArray = explode("|",$campo);
								$Value = $valor[$campoTmpArray[0]];
								if(is_numeric($Value)){
									$Value = intval($Value);
									$Value = number_format($Value, 0, '', '.');
								}
								$tabla .=  '<td style="border-bottom: 1px solid #CCCCCC;text-align: center;">'.$Value.'</td>';
							}
						}
						$tabla .= '</tr>';
					}
				}
				$tabla .= '</tbody>
				</table>';

				$return = $tabla;

			}else if($array_campos){
				if($valores){
					$valores = $valores[0];
					$Value = $valores[$FieldListArray[0]];
					if(is_numeric($Value)){
						$Value = intval($Value);
						$Value = number_format($Value, 0, '', '.');
					}

					$return = $Value;
				}
			} 

			return $return;

		}

		return $return;
	}

	public function gen_code(){
		$db = new DB();
		
		$exist = true;
		$char = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	$lon = strlen($char) - 1;
		$codigo = '';
		for($i=0;$i<6;$i++){
			$codigo .= substr($char, rand(0, $lon), 1);
		}
		return $codigo;
	}

	public function verificacionCron(){
		$db = new Db();
		$isValid = 2; // 1=activo y 2=inactivo   
		// verifico si el cron esta activo
		$consulta_cron = "SELECT * FROM cron_email WHERE estatus = 1 and id = 1";
		$cron = $db->select($consulta_cron);
		if(count($cron) > 0){
			$isValid = 1;
		}
		return $isValid;   
	}

	public function verificacionAlertaEnvio(){
		$db = new Db();
		//verifico si existe cola de envio
		$consulta_envio = "SELECT DISTINCT id_usuario FROM envio_email WHERE status = 0";
		$envio = $db->select($consulta_envio);
		$colaUsuarios = array();
		if(count($envio) > 0){			
		    foreach($envio as $cola){
				$idUsuario = $cola['id_usuario'];
        		$consultaUsuario = "SELECT nombre, id FROM Usuarios WHERE id = ".$idUsuario."";
        		$usua = $db->select($consultaUsuario);
          		foreach($usua as $usuario){
					$Array = array();
            		$Array[] = $usuario['nombre'];
            		array_push($colaUsuarios, $Array);
          		}
            }
		}
    	return $colaUsuarios;
	}

	public function getListarColas(){
    	$db = new Db();
		$colasArray = array();
		// id_usuario = ".$_SESSION['id_usuario']." AND
    	$Sql = "SELECT estrategia, id, FechaProceso FROM envio_email WHERE status IN (0,2)";
    	$colas = $db -> select($Sql);
    	foreach($colas as $cola){
			$Array = array();
			$queue = $this->findAsignacion($cola["estrategia"]);
			if($queue){
				$Array['estrategia'] = $queue["asignacion"];
				$Array['id'] = $cola["id"];
				$Array['fechahora'] = $cola["FechaProceso"];
				array_push($colasArray,$Array);
			}
    	}
   		return $colasArray;
  	}

	public function cancelarColaEnvio($idCola){
		$db = new DB();
		$SqlUpdate = "UPDATE gestion_correo SET estado = '5' WHERE id_envio = '".$idCola ."' AND estado = '4'";
		$delete = $db->query($SqlUpdate);
		$SqlUpdate = "UPDATE envio_email SET status = '3' WHERE id = '".$idCola."'";

		$result = $db->query($SqlUpdate);
		
		return $result;
	}
	
	public function pausarCola($idCola){
    	$db = new DB();
		$SqlUpdate = "UPDATE envio_email SET status = '2' WHERE id = '".$idCola ."'";

		$result = $db->query($SqlUpdate);

		return $result;
    }

	public function continuarEnvioCola($idCola){
		$db = new DB();
		$SqlUpdate = "UPDATE envio_email SET status = '0' WHERE id = '".$idCola."'";
		  
		$result = $db->query($SqlUpdate);

		return $result;
    } 

	public function enviados($inicio, $fin){
		$db = new DB();
		$cedente = $_SESSION["cedente"];
		$response = array();

		$sql = "SELECT 
						ee.estrategia, ee.cantidad, ee.actualizacion, ee.tabla_email, u.nombre, ee.offset AS enviados, ee.asunto, 
						ee.status, ee.id, t.Nombre AS template
					FROM 
						envio_email AS ee 
						INNER JOIN Usuarios AS u ON (u.id = ee.id_usuario) 
						INNER JOIN EMAIL_Template AS t ON (ee.template = t.id) 
					WHERE 
						ee.Id_Cedente = '" . $cedente ."' 
						AND DATE_FORMAT(ee.actualizacion,'%Y-%m-%d') >= '" . $inicio . "' 
                        AND DATE_FORMAT(ee.actualizacion,'%Y-%m-%d') <= '" . $fin . "'";

		$query_select = $db->select($sql);

		if(count($query_select)> 0){
	        $lista_enviados = '';
	        foreach($query_select as $row_select){
				$arreglo = array();
	        	$status  = $row_select["status"] == 1 ? 'Culminado' : 'En proceso';
	        	
	        	$query_leidos = $db->select("SELECT * FROM Confirmacion WHERE id_envio = '".$row_select["id"]."'");

	        	if(count($query_leidos)> 0){

	        		$row_leidos = $query_leidos[0];
					if($row_leidos['emails'] != ""){
						$emails_array = explode(',', $row_leidos['emails']);
	        			$leidos = count($emails_array);	
					}else{
						$leidos = '0';
					}

	        	} else {
	        		$leidos = '0';
				}
				
				$asignacion = $this->findAsignacion($row_select["estrategia"]);

				$arreglo = $row_select;
				$arreglo["leidos"] 		= $leidos;
				$arreglo["estatus"] 	= $status;
				$arreglo["asignacion"] 	= $asignacion["asignacion"];
				array_push($response, $arreglo);
	        }
	    }

		return $response;
	}

	public function selectTemplate($template_id, $canal){
		$db = new DB();

		if($canal != ""){
			$query_select = "SELECT id, Nombre, Template FROM SMS_Template WHERE id = ".$template_id;
		}else{
			$query_select = "SELECT Id, Nombre, Template, Asunto FROM EMAIL_Template WHERE Id = ".$template_id;
		}

		$row_select = $db->select($query_select);

		if(count($row_select) > 0){
			$row = $row_select[0];

			if($canal != ""){
				$temp = array(html_entity_decode($row['Template']), $row['Nombre'], $row['id']);
			}else{
				$temp = array(html_entity_decode($row['Template']), $row['Nombre'], $row['Id'], $row['Asunto']);
			}
		} else {
			$temp = array('','','');
		}
		
		return $temp;
	}

	public function saveTemplate($template_name, $template_asunto, $template){
		$db = new DB();
		$return = "";

		if($template_asunto != ""){
			$query_buscar = "SELECT * 
								FROM EMAIL_Template 
								WHERE Nombre = '".$template_name."' AND id_cedente = '".$_SESSION['cedente']."'";
		}else{
			$query_buscar = "SELECT * 
								FROM SMS_Template 
								WHERE Nombre = '".$template_name."' AND id_cedente = '".$_SESSION['cedente']."' AND id_usuario = '".$_SESSION['id_usuario']."'";
		}
	
		$existe = $db->select($query_buscar);

		if(count($existe) > 0){
			$return = "3";
		}else{
			if($template_asunto != ""){
			$query_guardar = "INSERT INTO 
									EMAIL_Template (Nombre, Template, id_cedente, Asunto) 
								VALUES('". $template_name ."', '". htmlentities($template) ."','".$_SESSION['cedente']."', '" . $template_asunto ."')";
			}else{
				$query_guardar = "INSERT INTO 
									SMS_Template (Nombre, Template, id_cedente, id_usuario) 
								VALUES('". $template_name ."', '". htmlentities($template) ."','".$_SESSION['cedente']."','".$_SESSION['id_usuario']."')";
			}

			$guardar = $db->query($query_guardar);
	
			if($guardar == false){
				$return = '2';
			} else {
				$return = '1';
			}
		}
		return $return;
	}

	public function updateTemplate($id, $title, $template, $tasunto){
		$db = new DB();
		$return = "";

		if($tasunto != ""){
			$query_buscar = "SELECT * 
								FROM  EMAIL_Template 
								WHERE Nombre = '".$title."' AND id != '".$id."' AND id_cedente = '".$_SESSION["cedente"]."'";
		}else{
			$query_buscar = "SELECT * 
								FROM  SMS_Template 
								WHERE Nombre = '".$title."' AND id != '".$id."' 
									AND id_cedente = '".$_SESSION["cedente"]."' AND id_usuario = '".$_SESSION["id_usuario"]."'";
		}

		$existe = $db->select($query_buscar);
	
		if(count($existe) > 0){
			$return = "3";
		}else{
			if($tasunto != ""){
				$query_update = "UPDATE 
										EMAIL_Template 
									SET 
										Nombre = '".$title."', Template='".htmlentities($template)."', Asunto='".$tasunto."' WHERE Id=".$id;
			}else{
				$query_update = "UPDATE 
										SMS_Template 
									SET 
										Nombre = '".$title."', Template='".htmlentities($template)."' WHERE id=".$id;
			}
	
			$update = $db->query($query_update);
	
			if($update !== false){
				$return = '1';
			} else{
				$return = '2';
			}
		}

		return $return;
	}

	public function enviarEmail($asignacion, $cantidad, $asunto, $adjuntar, $template){
		$db 	= new DB();
		$codigo 	= $this->gen_code();
		$cedente 	= $_SESSION["cedente"];
		$usuario 	= $_SESSION["id_usuario"];
		$nombre     = $_SESSION["nombreUsuario"];
		$return 	= "";

		//Consulta si existen envios pendientes
		$query = "SELECT * FROM envio_email WHERE status IN (0,2) AND Id_Cedente = '".$cedente."'";
		$pendientes = $db->select($query);

		if($pendientes){
			//Validar si existe otro registro pendiente de esa estrategia
			$existe = false;
			foreach($pendientes as $pendiente){
				$estrategia = $pendiente["estrategia"];
				$existe = ($estrategia == $asignacion) ? true : $existe;
			}

			if($existe){
				$return = '1'; // Error, Ya existe un envío programado para la estrategia
			}else{
				$return = $this->envioEmail($asignacion, $cantidad, $asunto, $adjuntar, 
											$template, $cedente, $usuario, $codigo, $nombre);
			}
		}else{
			$return = $this->envioEmail($asignacion, $cantidad, $asunto, $adjuntar, 
											$template, $cedente, $usuario, $codigo, $nombre);
		}
		return $return;
	}

	public function envioEmail($asignacion, $cantidad, $asunto, $adjuntar, $template, 
								$cedente, $usuario, $codigo, $nombre){
		$db = new DB();
		$query = "  SELECT  
						m.correo_electronico, m.Rut 
					FROM 
						Mail m , ".$asignacion." q 
					WHERE 
						m.Rut = q.Rut 
					ORDER BY 
						q.orden";
		$correos = $db->select($query);
		if($correos){
			$query =    "SELECT 
							Nombre, Template 
						FROM 
							EMAIL_Template 
						WHERE 
							Id = ".$template;	
			$template_email = $db->select($query);		
			if($template_email){
				$nomTemplate = $template_email[0]['Nombre'];
				$cuerpo = $template_email[0]['Template'];
				$cuerpo = $this->bodyEmail($codigo, $cuerpo);
			}else{
				$nomTemplate = '';
				$cuerpo = '';
			}
			$query = "  INSERT INTO 
							envio_email 
							(estrategia, cantidad, asunto, html, offset, status, actualizacion, adjuntar, Id_Cedente, tabla_email, id_usuario, fechaProceso, template, codigo) 
						VALUES 
							('".$asignacion."', '".$cantidad."', '".$asunto."', '".$cuerpo."', '0', '0', NOW(), '".$adjuntar."', '".$cedente."', '1', '".$usuario."', NOW(), '".$template."','".$codigo."')";

			$id_envio = $db->insert($query);
			if($id_envio){
				$Array1000 = array();
				$Cont1000 = 0;
				$Array1000[$Cont1000] = array();
				foreach ($correos as $correo) {
					$email = $correo['correo_electronico'];
					if($email){
						$rut = $correo['Rut'];
						$Value = "('".$rut."',NOW(),NOW(),'".$nombre."','".$cedente."','".$email."','','".$nomTemplate."','".$id_envio."','4')";
						array_push($Array1000[$Cont1000],$Value);
						if(count($Array1000[$Cont1000]) == 1000){
							$Cont1000++;
							$Array1000[$Cont1000] = array();
						}
					}
				}
				foreach($Array1000 as $ValuesArray){
					$Values = implode(",",$ValuesArray);
					$query = "  INSERT INTO 
									gestion_correo
									(rut_cliente,fecha_gestion,hora_gestion,nombre_ejecutivo,cedente,correos,facturas,template,id_envio,estado) 
								VALUES 
									".$Values;
					$db->query($query);
				}
				return '2';
			}
		}else{
			$return = '0';
		}
	}

	public function bodyEmail($codigo, $body){
		if(function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
			$host = $headers['Host'];
		}else{
			$arh = array();
			$rx_http = '/\AHTTP_/';
			foreach($_SERVER as $key => $val) {
				if( preg_match($rx_http, $key) ) {
					$arh_key = preg_replace($rx_http, '', $key);
					$rx_matches = array();
					// do some nasty string manipulations to restore the original letter case
					// this should work in most cases
					$rx_matches = explode('_', $arh_key);
					if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
						foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
						$arh_key = implode('-', $rx_matches);
					}
					$arh[$arh_key] = $val;
				}
			}
			$headers = $arh;
			if(isset($headers['HOST'])){
				$host = $headers['HOST'];
			}else{
				$host = '191.102.35.99:32000';
			}
		}

		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$host;
		$html = '<html>
					<head>
						<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
						<style>
							body{font-family:Open Sans;font-size:14px;}
							table{font-size:13px;border-collapse:collapse;}
							th{padding:8px;text-align:left;color:#595e62;border-bottom: 2px solid rgba(0,0,0,0.14);font-size:14px;}
							td{padding:8px;border-bottom: 1px solid rgba(0,0,0,0.05);}
						</style>
					</head>
					<body>
						'.$body.'
						<img src="'.$url.'/email/confirmar-lectura.php?codigo='.$codigo.'&email=[correo]" 
								style="opacity: 0; height:1px; width:1px;">
					</body>
				</html>';
		return $html;
	}

	public function getVariables($html,$CedenteDefault = ""){
		$db = new DB();
		$cedente 	= $CedenteDefault == "" ? $_SESSION["cedente"] : $CedenteDefault;

		//Consultar Variables Creadas
		$query_ve = "SELECT variable FROM Variables where id_cedente='".$cedente."'";
		$variables_existentes = $db->select($query_ve);
		$uso_variables = array();
		if(count($variables_existentes) > 0){
			foreach($variables_existentes as $var_e){
				$var = $var_e['variable'];
				$uso = strpos($html, '['.$var.']');
				if($uso !== false){
					$uso_variables[] = $var;
				}
			}
		}

		return $uso_variables;
	}

	public function getEstrategia($table){
		$db = new DB();
		// $cedente = $_SESSION["cedente"];

		if($table !== ""){
			$query = "	SELECT 
							COUNT(DISTINCT q.Rut) as Ruts, COUNT(m.correo_electronico) AS Emails 
						FROM 
							".$table." q 
						LEFT JOIN
							Mail m
						ON 
							q.Rut = m.Rut";

			$cantidad = $db->select($query);
		
			if($cantidad){
				$Ruts = $cantidad[0]["Ruts"];
				$Emails = $cantidad[0]["Emails"];
			}else{
				$Ruts = $Emails = '0';
			}
		}else{
			$Ruts = $Emails = '0';
		}
		$values = array($Ruts,$Emails);
		return $values;
	}

	public function estadoEnvio($id_envio){
		$db = new Db();
		$query = "SELECT status FROM envio_email WHERE id = '".$id_envio."'";
		$envio_email = $db->select($query);
		if($envio_email){
			$status = $envio_email[0]['status'];
			if($status == 0){
				$status = 'Activa';
			}else{
				$status = 'Pausada';
			}
		}else{	
			$status = '';
		}
		return $status;
	}

	private function findAsignacion($colas){
		$db = new DB();
		$asignacion = array ();
		$asig = explode("_", $colas);
		if(isset($asig[3])){
			if($asig[3] == "G"){
				$asignaciones = $db->select("SELECT Nombre FROM grupos WHERE IdGrupo = '".$asig[4]."'");
				$asignacion["id"] = $colas;
				$asignacion["asignacion"] = "Asignación Grupo " . $asignaciones[0]["Nombre"];
			}else if($asig[3] == "S"){
				$asignaciones = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = '".$asig[4]."'");
				$asignacion["id"] = $colas;
				$asignacion["asignacion"] = "Asignación Supervisor " . $asignaciones[0]["Nombre"];
			}else if($asig[3] == "E"){
				$asignaciones = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = '".$asig[4]."'");
				$asignacion["id"] = $colas;
				$asignacion["asignacion"] = "Asignación Ejecutivo " . $asignaciones[0]["Nombre"];
			}else if($asig[3] == "EE"){
				$asignaciones = $db->select("SELECT Nombre FROM empresa_externa WHERE IdEmpresaExterna = '".$asig[4]."'");
				$asignacion["id"] = $colas;
				$asignacion["asignacion"] = "Asignación Empresa Externa " . $asignaciones[0]["Nombre"];
			}
		}

		return $asignacion;
	}

	/*********************************************************************
    ** getHorasCorreo (Obtener horario de envío Correo para el cedente) **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Consulta con el horario de envío de Correo                  **
    **********************************************************************/
    public function getHorasCorreo(){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT * FROM mantenedor_correo WHERE cedente = '" . $cedente . "'";

        $result = $db->select($sql);

        return $result;
	}
	
	
	/*************************************************************************
    ** guardarMantenedorCorreo (Guardar configuración de envío de Correos) 	**
    **  Parámetros                                                      	**
    **      Horario Inicio, Horario Fin, Cantidad Cedente 				   	**
    **  Return                                                         	 	**
    **      TRUE:FALSE                                                  	**
    **************************************************************************/
    public function guardarMantenedorCorreo($ini, $fin){
        $db = new DB();
		$cedente = $_SESSION["cedente"];
		$sql = "SELECT id FROM mantenedor_correo WHERE cedente = '" . $cedente . "'";
		$result = $db->select($sql);

		if(count($result) > 0){
			//Existe registro para el cedente
			$sqlQuery = "UPDATE 
								mantenedor_correo 
							SET horaInicio = '" . $ini . "', 
								horaFin = '" . $fin . "'
							WHERE 
								id = '" . $result[0]["id"] . "'";
		}else{
			$sqlQuery = "INSERT INTO 
								mantenedor_correo (horaInicio, horaFin, cedente) 
							VALUES ('" . $ini . "', '" . $fin . "', '" . $cedente . "')";
		}

		$query = $db->query($sqlQuery);

		if($query){
			return true;
		}else{
			return false;
		}
	}

	/*********************************************************************
    ** verificarEnvioCorreo (Verificar condiciones para envío Correo)   **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Arreglo con detalle para comportamiento en pantalla.        **
    **********************************************************************/
    public function verificarEnvioCorreo(){
        $db      = new DB();
        $arreglo = array();
        $user    = $_SESSION["id_usuario"];
        $cedente = $_SESSION["cedente"];

        /*********************************************************************
        ** VERIFICAR SI ESTÁ EN EL PERÍODO DE TIEMPO PARA ENVÍO DE CORREOS  **
        ** 1- (count($result) > 0)                                      	**
        **      A. ESTÁ EN EL PERÍODO.                                  	**
        **      B. ESTÁ FUERA DEL PERÍODO                               	**
        ** 2- NO TIENE CONFIGURACIÓN EN TABLA mantenedor_correo            	**
        **********************************************************************/
        $sql = "SELECT id, horaInicio, horaFin FROM mantenedor_correo WHERE cedente = '" . $cedente . "'";
        $result = $db->select($sql);

        if(count($result) > 0){
            $inicio = $result[0]["horaInicio"];
            $fin = $result[0]["horaFin"];
            $actual = date("H:i:s");

			$horaInicio = new DateTime($inicio);
			$horaInicio = $horaInicio->format('H:i');
			$horaFin = new DateTime($fin);
			$horaFin = $horaFin->format('H:i');
			$horaActual = new DateTime($actual);
			$horaActual = $horaActual->format('H:i');

            if(($horaActual > $horaInicio) && ($horaActual < $horaFin)){
                //Está dentro del período establecido
				$arreglo["respuesta"] = "1";
				$mantenedor = $result[0]["id"];
            }else{
                //Está fuera del período establecido
                $arreglo["respuesta"] = "2";
                $arreglo["horaInicio"] = $horaInicio;
                $arreglo["horaFin"] = $horaFin;
                return $arreglo;
            }
        }else{
            //No tiene configuración para el cedente
            $arreglo["respuesta"] = "3";
            return $arreglo;
        }

        return $arreglo;
    }

	public function fillCodigos($estrategia){
        $db = new DB();
        $query = "	SELECT 
						codigo, FechaProceso
					FROM 
						envio_email
					WHERE 
						estrategia = '".$estrategia ."'";

        $codigos = $db->select($query);
		$response = array();
		
        if($codigos){
            foreach($codigos as $codigo){
				$arreglo = array();
				$arreglo['codigo'] = $codigo['codigo'];
				$arreglo['fechahora'] = $codigo['FechaProceso'];
                array_push($response, $arreglo);
            }
        }

        return $response;        
	}
	
	public function getCorreosEstadistica($codigo,$estrategia){
		$db = new DB();
		$response = array();
		$response['dataSet'] = array();
		$Abiertos = 0;
		$Recibidos = 0;
		$Rebotados = 0;
		$Pendientes = 0;
        $query = "	SELECT 
						p.Rut, p.Nombre_Completo as Nombre, g.correos as Correo, g.estado as Estado,
						CASE WHEN EXISTS (SELECT id_mail FROM Mail m WHERE g.correos = m.correo_electronico)
							THEN '1' 
							ELSE '0'
						END AS id_mail  
					FROM 
						Persona p
					INNER JOIN
						gestion_correo g
					ON
						p.Rut = g.rut_cliente
					INNER JOIN
						envio_email e
					ON
						g.id_envio = e.id
					WHERE
						e.codigo = '".$codigo."'";

        $Personas = $db->select($query);
        if($Personas){
            foreach($Personas as $Persona){
				$Estado = $Persona['Estado'];
				if($Estado == '3'){
					$Estado = 'ABIERTO';
					$Abiertos++;
				}else if($Estado == '1'){
					$Estado = 'RECIBIDO';
					$Recibidos++;
				}else if($Estado == '2'){
					$Estado = 'REBOTADO';
					$Rebotados++;
				}else{
					$Estado = 'PENDIENTE';
					$Pendientes++;
				}
				$arreglo = array();
				$arreglo['Rut'] = $Persona['Rut'];
				$arreglo['Nombre'] = $Persona['Nombre'];
				$arreglo['Correo'] = $Persona['Correo'];
				$arreglo['Estado'] = $Estado;
				if($Persona['id_mail']){
					$arreglo['Accion'] = $Persona['Correo'];
				}else{
					$arreglo['Accion'] = '';
				}
				array_push($response['dataSet'], $arreglo);
            }
		}
		$Enviados = $Abiertos + $Recibidos + $Rebotados;
		$Totales = $Enviados + $Pendientes;
		$response['Enviados'] = $Enviados;
		$response['Recibidos'] = $Recibidos;
		$response['Abiertos'] = $Abiertos;
		$response['Rebotados'] = $Rebotados;
		$response['Pendientes'] = $Pendientes;
		$response['Totales'] = $Totales;
        return $response;        
	}

	public function checkEmail($email){

		$vmail = new verifyEmail();
		$vmail->setStreamTimeoutWait(20);
		$vmail->setEmailFrom('carlos');
		// $vmail->Debug = TRUE;
		// $vmail->Debugoutput = 'html';

		if ($vmail->check($email)){
			return true;
		}else {
			return false;
		}
	}

	public function deleteEmail($email){
		$db = new DB();
  		$query = "DELETE FROM Mail WHERE correo_electronico = '".$email."'";
		$delete = $db->query($query);
		return $delete;
	} 
	public function getCorreosRestantes(){
		$db = new DB();
  		$query = "SELECT COUNT(*) as Cantidad FROM gestion_correo WHERE estado = '4'";
		$gestion_correo = $db->select($query);
		return $gestion_correo[0]['Cantidad'];
	} 
	
	public function SendMailFile($html,$subject,$email_list){
		$config = new opciones; 
		$mail = new PHPMailer();  

		$ToReturn = false;
		
		$mail->Host = "smtp.gmail.com"; 
		$mail->SMTPDebug = 1;  
		$mail->Port = 465;  
		$mail->Username = "soporte@mibot.cl";  
		$mail->Password = "Soporte.,2020";  
		$mail->From = "soporte@mibot.cl";   
		$mail->FromName = "Soporte MiBot";  
		$mail->IsHTML(true);
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->SMTPSecure = "ssl";
		$mail->Subject = $subject;
		$content = html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $html), ENT_QUOTES, 'UTF-8');
		$html = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $html);
		$mail->MsgHTML($html);

		$Emails = explode(";",$email_list);
		foreach($Emails as $Email){
			$Email = trim($Email);
			if($Email != ""){
				$mail->AddAddress($Email); 
			}
		}

		if(!$mail->Send()){   
			echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			$ToReturn = false;
		}else{   
			$ToReturn = true;
		}
		
		return $ToReturn;	
	}
}