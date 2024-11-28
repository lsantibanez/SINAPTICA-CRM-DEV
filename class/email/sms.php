<?php 
class Sms
{
    /*********************************************************************
    ** infoEstrategia (Cálculo de fonos de estrategia seleccionada)     **
    **  Parámetros                                                      **
    **      Estrategia (QR_), tableFonos (0-1), colores (id colores).   **
    **  Return                                                          **
    **      Arreglo con cantidad de ruts y fonos                        **
    **********************************************************************/
    function infoEstrategia($colores, $queue){
        $db     = new DB();
        // $cedente = $_SESSION["cedente"];

        if($queue !== ""){
            $sqlColores = "";
            if($colores != ""){
                $sqlColores = " AND fc.color IN ($colores)";
            }

            $query = "	SELECT 
							COUNT(DISTINCT q.Rut) as Ruts, COUNT(fc.formato_subtel) AS Fonos 
						FROM 
							".$queue." q 
						LEFT JOIN
							fono_cob fc
						ON 
                            q.Rut = fc.Rut
                        WHERE
                            fc.formato_subtel LIKE '9%'" . $sqlColores;

            $cantidad = $db->select($query);

            if($cantidad){
                $Ruts = $cantidad[0]["Ruts"];
                $Fonos = $cantidad[0]["Fonos"];
            }else{
                $Ruts = $Fonos = '0';
            }
        }else{
            $Ruts = $Fonos = '0';
        }
        $values = array($Ruts, $Fonos);
        echo json_encode($values);
    }

    /*********************************************************************
    ** enviarSMS (Envío de SMS a fonos de estrategia)                   **
    **  Parámetros                                                      **
    **      Texto SMS, Estrategia (QR_), Fonos (cant), tablaFonos (0-1) **
    **      colores (id colores), template (id template)                **
    **  Return                                                          **
    **      Envío de SMS en proceso.                                    **
    **********************************************************************/
    public function enviarSMS($mensaje, $cantidad, $colores, $queue, $template, $rut, $telefonos){
        $db = new DB();

        $fecha      = date('Y-m-d H:i:s');
        $cedente 	= $_SESSION["cedente"];
        $mandante 	= $_SESSION["mandante"];
        $usuario 	= $_SESSION["id_usuario"];
        $username   = $_SESSION["MM_Username"];
        if($queue == 'undefined'){
            $queue = '';
        }
        if($template != 's' && $mensaje == ''){
            $query = "SELECT Template as mensaje FROM SMS_Template WHERE id = '".$template."'";
            $SMS_Template = $db->select($query);
            if($SMS_Template){
                $mensaje = $SMS_Template[0]['mensaje'];
            } 
        }else{
            $template = 0;
        }
        if(is_array($colores)){
            $colores = implode(",",$colores);
        }

        $sqlEnvioSMS = "    INSERT INTO 
                                envio_sms 
                                    (asignacion, 
                                    cantidad, 
                                    sms, 
                                    tabla_fonos, 
                                    id_cedente, 
                                    id_usuario, 
                                    fechaHora, 
                                    colores, 
                                    template) 
                            VALUES ('" . $queue . "', 
                                    '" . $cantidad . "', 
                                    '" . $mensaje . "', 
                                    '0', 
                                    '" . $cedente . "', 
                                    '" . $usuario ."', 
                                    '" . $fecha . "', 
                                    '" . $colores . "',
                                    '" . $template . "')";

        $envio = $db->insert($sqlEnvioSMS);
        if($envio){
            if(!$telefonos){
                $query = 'php /var/www/html/task/SMS/envioSMS.php "'.$queue.'" "'.$cedente.'" "'.$colores.'" "'.$mensaje.'" "'.$envio.'" "'.$mandante.'" "'.$usuario.'" "'.$username.'" "'.$rut.'" "'.$telefonos.'" "'.$cantidad.'" > /dev/null 2>&1 &';
                shell_exec($query);
            }else{
                $query_ve = "SELECT variable FROM VariablesSMS where id_cedente = '".$cedente."' ";
                $variables_existentes = $db->select($query_ve);
                $uso_variables = array();

                if(count($variables_existentes) > 0){
                    foreach($variables_existentes as $var_e){
                        $var = $var_e['variable'];
                        $uso = strpos($sms, '['.$var.']');
                        if($uso !== false){
                            $uso_variables[] = $var;
                        }
                    }
                }
                $fonos = explode(",",$telefonos);
                foreach ($fonos as $tlf) {
                    //Obtener valor de cada Variable para cada rut
                    foreach ($uso_variables as $var){
                        $aux = $this->get_var_value($rut, $var, $cedente);
                        if(!is_numeric($aux)){
                            $aux = $this->limpiarString($aux);
                        }
                        $mensaje = str_replace("[" . $var . "]", $aux, $mensaje);
                    }

                    $response = $this->envioSMS($tlf, $mensaje);
                    if($response !== false){
                        $resp = $response;
                    }else{
                        $resp = "";
                    }
                    $sqlDetalle = "INSERT INTO detalle_envio_sms (id_envio_sms, fono, rut, estado, respuesta) VALUES ('" . $envio . "', '" . $tlf . "', '" . $rut . "', '0', '" . $resp . "')";
                    $db->query($sqlDetalle);

                }
            }
            return "1";
        }else{
            return "0";
        }
    }

    /*********************************************************************
    ** getSMSEnviados (Obtener histórico de SMS Enviados)               **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Arreglo con datos de los SMS Enviados                       **
    **********************************************************************/
    public function getSMSEnviados($start, $end){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT 
                    es.id, se.nombre AS estrategia, es.cantidad, es.sms, es.fechaHora, u.nombre AS usuario, t.nombre AS template 
                    FROM 
                        envio_sms AS es 
                        INNER JOIN Usuarios AS u ON (es.id_usuario = u.id) 
                        INNER JOIN SMS_Template AS t ON (es.template = t.id), 
                        SIS_Estrategias AS se 
                        INNER JOIN SIS_Querys_Estrategias AS qe ON (se.id = qe.id_estrategia) 
                        INNER JOIN asignacion_cola AS ac ON (ac.id_cola = qe.id) 
                    WHERE 
                        es.id_cedente = '" . $cedente . "' 
                        AND ac.asignacion = es.asignacion
                        AND DATE_FORMAT(es.fechaHora,'%Y-%m-%d') >= '" . $start . "' 
                        AND DATE_FORMAT(es.fechaHora,'%Y-%m-%d') <= '" . $end . "'";

        $enviados = $db->select($sql);

        $response= array();
        if(is_array($enviados)){
            foreach($enviados as $enviado){
                $arreglo = array();
                $sqlEnv = "SELECT colores FROM envio_sms WHERE id = '" . $enviado["id"] . "'";
                $colores = $db->select($sqlEnv);

                $sqlColores = "SELECT 
                                    GROUP_CONCAT(comentario SEPARATOR ', ') AS colores 
                                FROM 
                                    SIS_Colores 
                                WHERE 
                                    id IN (" . $colores[0]["colores"] . ")";
                                    
                $comentarios = $db->select($sqlColores);

                $arreglo = $enviado;
                $arreglo["colores"] = $comentarios[0]["colores"];
                array_push($response, $arreglo);
            }
        }

        return $response;
    }

    /*********************************************************************
    ** getDetalleSMSEnviados (Obtener el estado de los SMS Enviados)    **
    **  Parámetros                                                      **
    **      Id de los envíos de sms programados                         **
    **  Return                                                          **
    **      Consulta de estados de los SMS                              **
    **********************************************************************/
    public function getDetalleSMSEnviados($id){
        $db = new DB();
        $sqlDetalle = "SELECT * FROM detalle_envio_sms WHERE id_envio_sms = '" . $id . "'";

        $result = $db->select($sqlDetalle);

        return $result;
    }

    /*********************************************************************
    ** getHorasSMS (Obtener horario de envío SMS para el cedente)       **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Consulta con el horario de envío de SMS                     **
    **********************************************************************/
    public function getHorasSMS(){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT * FROM mantenedor_sms WHERE cedente = '" . $cedente . "'";

        $result = $db->select($sql);

        return $result;
    }

    /*********************************************************************
    ** guardarMantenedor (Guardar configuración de envío de SMS)        **
    **  Parámetros                                                      **
    **      Horario Inicio, Horario Fin, Cantidad Cedente, Valor SMS    **
    **  Return                                                          **
    **      TRUE:FALSE                                                  **
    **********************************************************************/
    public function guardarMantenedor($ini, $fin, $cost){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT id FROM mantenedor_sms WHERE cedente = '" . $cedente . "'";
        $result = $db->select($sql);

        if($result){
            //Existe registro para el cedente
            $sqlQuery = "UPDATE 
                                mantenedor_sms 
                            SET horaInicio = '" . $ini . "', 
                                horaFin = '" . $fin . "', 
                                costoSMS = '" . $cost . "'
                            WHERE 
                                id = '" . $result[0]["id"] . "'";
        }else{
            $sqlQuery = "INSERT INTO 
                                mantenedor_sms (horaInicio, horaFin, cedente, costoSMS, cantidad) 
                            VALUES ('" . $ini . "', '" . $fin . "', '" . $cedente . "', '" . $cost . "', '0')";
        }

        $query = $db->query($sqlQuery);

        return $query;
    }

    /*********************************************************************
    ** guardarURLSMS (Guardar las URL de conexión a API SMS)            **
    **  Parámetros                                                      **
    **      Password, Usuario, URLEnvío, URLConsultaSMS, URLSaldo       **
    **  Return                                                          **
    **      TRUE:FALSE                                                  **
    **********************************************************************/
    public function guardarURLSMS($pwd, $user, $urlEnvio, $urlConsulta, $saldo){
        $db = new DB();

        $result = $this->getCredenciales();

        if($result){
            $contrasena = "";
            if($pwd !== ""){
                $contrasena = ", contrasena = '" . $pwd . "'";
            }

            $sql = "UPDATE credenciales_sms 
                        SET 
                            urlEnvio = '" . $urlEnvio . "', urlConsulta = '" . $urlConsulta . "',
                            usuario = '" . $user . "', urlSaldo = '" . $saldo . "'" . $contrasena . " 
                        WHERE id = '" . $result[0]["id"] . "'";
        }else{
            $sql = "INSERT INTO 
                            credenciales_sms (urlEnvio, urlConsulta, usuario, contrasena, urlSaldo) 
                        VALUES ('" . $urlEnvio . "', '" . $urlConsulta . "', '" . $user . "', '" . $pwd . "', '" . $saldo . "')";
        }

        $query = $db->query($sql);

        if($query){
            return true;
        }else{
            return false;
        }
    }

    /*********************************************************************
    ** verificarEnvioSMS (Verificar condiciones para envío SMS)         **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Arreglo con detalle para comportamiento en pantalla.        **
    **********************************************************************/
    public function verificarEnvioSMS(){
        $db      = new DB();
        $arreglo = array();
        $user    = $_SESSION["id_usuario"];
        $cedente = $_SESSION["cedente"];

        /*****************************************************************
        ** VERIFICAR QUE LAS CREDENCIALES ESTÉN ESTABLECIDAS EN BD      **
        ** 1- !(count($resp) > 0) NO TIENE CONFIGURACIONES              **
        ******************************************************************/
        $sqlCredenciales = "SELECT id FROM credenciales_sms";
        $resp = $db->select($sqlCredenciales);

        if(!$resp){
            //No tiene configuración de credenciales
            $arreglo["respuesta"] = "4";
            return $arreglo;
        }

        /*****************************************************************
        ** VERIFICAR SI ESTÁ EN EL PERÍODO DE TIEMPO PARA ENVÍO DE SMS  **
        ** 1- (count($result) > 0)                                      **
        **      A. ESTÁ EN EL PERÍODO.                                  **
        **      B. ESTÁ FUERA DEL PERÍODO                               **
        ** 2- NO TIENE CONFIGURACIÓN EN TABLA mantenedor_sms            **
        ******************************************************************/
        $sql = "SELECT horaInicio, horaFin FROM mantenedor_sms WHERE cedente = '" . $cedente . "'";
        $result = $db->select($sql);

        if($result){
            $inicio = $result[0]["horaInicio"];
            $fin = $result[0]["horaFin"];
            $actual = date("H:i:s");

            $horaInicio = strtotime($inicio);
            $horaFin = strtotime($fin);
            $horaActual = strtotime($actual);
            $horaInicio = new DateTime($inicio);
			$horaInicio = $horaInicio->format('H:i');
			$horaFin = new DateTime($fin);
			$horaFin = $horaFin->format('H:i');
			$horaActual = new DateTime($actual);
			$horaActual = $horaActual->format('H:i');

            if(($horaActual > $horaInicio) && ($horaActual < $horaFin)){
                //Está dentro del período establecido
                $arreglo["respuesta"] = "1";
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

    /*********************************************************************
    ** getReporteSMS (Obtener datos para el reporte del Cedente)        **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Consulta con detalle del reporte.                           **
    **********************************************************************/
    public function getReporteSMS($start, $end){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT 
                        COUNT(des.id) AS cantidad, DATE_FORMAT(es.fechaHora,'%Y-%m-%d') AS fecha, u.nombre, ms.costoSMS
                    FROM 
                        detalle_envio_sms AS des 
                        INNER JOIN envio_sms AS es ON (des.id_envio_sms = es.id)
                        INNER JOIN Usuarios AS u ON (es.id_usuario = u.id),
                        mantenedor_sms AS ms
                    WHERE 
                        ms.cedente = '" . $cedente . "' 
                    AND DATE_FORMAT(es.fechaHora,'%Y-%m-%d') >= '" . $start . "' 
                    AND DATE_FORMAT(es.fechaHora,'%Y-%m-%d') <= '" . $end . "'
                    GROUP BY es.id_usuario, DATE_FORMAT(es.fechaHora,'%Y-%m-%d'), u.nombre, ms.costoSMS ";

        $result = $db->select($sql);

        $sqlMes = "SELECT 
                        COUNT(des.id) AS cantidad
                    FROM 
                        detalle_envio_sms AS des 
                        INNER JOIN envio_sms AS es ON (des.id_envio_sms = es.id),
                        mantenedor_sms AS ms
                    WHERE 
                        ms.cedente = '" . $cedente . "' 
                        AND YEAR(DATE_FORMAT(es.fechaHora,'%Y-%m-%d')) = YEAR(NOW()) 
                        AND MONTH(DATE_FORMAT(es.fechaHora,'%Y-%m-%d')) = MONTH(NOW())";

        $mes = $db->select($sqlMes);

        $arreglo = array();

        $arreglo["detalle"] = $result;
        $arreglo["totalMes"] = $mes[0]["cantidad"];

        return $arreglo;
    }

    /*********************************************************************
    ** get_var_value (Obtener los valores de las Variables establecidas)**
    **  Parámetros                                                      **
    **      rut, variable, cedente                                      **
    **  Return                                                          **
    **      Consulta con detalle del reporte.                           **
    **********************************************************************/
    public function get_var_value($rut, $var, $cedente){
    	$db = new DB();
		$return = false;

		$fields_variable = "SELECT * FROM VariablesSMS WHERE variable = '" . $var . "' AND id_cedente = '" . $cedente . "'";
		$row = $db->select($fields_variable);

		if($row){
			$row_var = $row[0];
			$tabla = $row_var['tabla'];
			$campos = $row_var['campo'];
			$cedente = ($tabla == 'Deuda') ? " AND Id_Cedente = '".$cedente."'" : "";

            $CamposSelect = $campos;
            $consulta_valores = "SELECT ".$CamposSelect." FROM ".$tabla." WHERE Rut='".$rut."'".$cedente;

			$valores = $db->select($consulta_valores);

            $valores = $valores[0];
            $return = $valores[$campos];
		}
        return $return;
	}

    /*********************************************************************
    ** limpiarString (Quitar caracteres a un string)                    **
    **  Parámetros                                                      **
    **      String a limpiar                                            **
    **  Return                                                          **
    **      Cadena recibida sin caracteres prohibidos.                  **
    **********************************************************************/
    public function limpiarString($string){
        $string = preg_replace("/[áàâãª]/","a",$string);
        $string = preg_replace("/[ÁÀÂÃ]/","A",$string);
        $string = preg_replace("/[éèê]/","e",$string);
        $string = preg_replace("/[ÉÈÊ]/","E",$string);
        $string = preg_replace("/[íìî]/","i",$string);
        $string = preg_replace("/[ÍÌÎ]/","I",$string);
        $string = preg_replace("/[óòôõº]/","o",$string);
        $string = preg_replace("/[ÓÒÔÕ]/","O",$string);
        $string = preg_replace("/[úùû]/","u",$string);
        $string = preg_replace("/[ÚÙÛ]/","U",$string);
        $string = str_replace("%","",$string);
        $string = str_replace("ñ","n",$string);
        $string = str_replace("Ñ","N",$string);

        return $string;
    }

    /*********************************************************************
    **  envioSMS (Envío de SMS; ejecutando URL API con CURL-PHP-)            **
    **  Parámetros                                                      **
    **      telefono, mensaje                                           **
    **  Return                                                          **
    **      Respuesta envío:FALSE                                       **
    **********************************************************************/
    public function envioSMS($fono = null, $sms = null){
        $credenciales = $this->getCredenciales();
        if($credenciales){
            $mensaje = urlencode($sms);

            $numero = "&numero=56". trim($fono);
            $texto  = "&mensaje=" . trim($mensaje);
            $usuario = "&usuario=" . trim($credenciales["usuario"]);
            $clave  = "&clave=" . trim($credenciales["contrasena"]);
            $urlEnvio = trim($credenciales["urlEnvio"]);

            $url = $urlEnvio . $numero . $texto . $usuario . $clave;
            //$url = "http://168.197.51.148/sms/sms.php?funcion=enviar&numero=56". trim($fono) ."&mensaje=" . $mensaje . "&usuario=Soporte&clave=eart123y6";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $resultado = curl_exec($ch);
            if(strpos($resultado, "Response")){
                $explode = explode('","', $resultado);
                $strlen = strlen($explode[1]);
                $substr = substr($explode[1], 0, $strlen - 2);
                return $substr;
            }else{
                return false;
            }
        }else{
            echo 'No hay credenciales configuradas';
        }
    }

    /*********************************************************************
    **  estadoSMS (Consulta de estado SMS; ejecución CURL-PHP-)         **
    **  Parámetros                                                      **
    **      código de respuesta                                         **
    **  Return                                                          **
    **      Estatus de respuesta:FALSE                                  **
    **********************************************************************/
    public function estadoSMS($respuesta){
        $credenciales = $this->getCredenciales();

        $respuesta = "&idrespuesta=" . trim($respuesta);
        $urlConsulta = trim($credenciales["urlConsulta"]);

        $url = $urlConsulta . $respuesta;

        //$url = "http://168.197.51.148/sms/sms.php?funcion=estadosms&idrespuesta=" . trim($respuesta);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $resultado = curl_exec($ch);

        if(strpos($resultado, "Status :")){
            $resp = explode("Status :", $resultado);
            $response = explode('"', $resp[1]);

            return trim($response[0]);
        }else{
            return false;
        }
    }

    /*********************************************************************
    **  getCredenciales (Obtener las credenciales para envío SMS)       **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Arreglo con credenciales                                    **
    **********************************************************************/
    public function getCredenciales(){
        $db = new DB();
        $sql = "SELECT * FROM credenciales_sms";

        $result = $db->select($sql);
        if($result){
            $ToReturn = $result[0];
        }else{
            $ToReturn = array();
        }

        return $ToReturn;
    }

    /*********************************************************************
    **  SMSNotificacion (Envío EMAIL de Notificación entrega SMS)       **
    **  Parámetros                                                      **
    **      texto, asunto, mail destinatario, Nombre remitente          **
    **  Return                                                          **
    **      TRUE:FALSE                                                  **
    **********************************************************************/
    public function SMSNotificacion($html, $subject, $destino, $cedente){ 
		$mail = new PHPMailer();
        $ToReturn = FALSE;

        $conf = $this->getConfiguracionNoti($cedente);

        if($conf){
            $host = trim($conf[0]["host"]);
            $port = trim($conf[0]["puerto"]);
            $user = trim($conf[0]["email"]);
            $pass = trim($conf[0]["contrasena"]);
            $from = trim($conf[0]["from_email"]);
            $name = trim($conf[0]["from_name"]);

            $mail->IsSMTP();
            $mail->SMTPAuth = TRUE;
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->Username = $user;
            $mail->Password = $pass;
            $mail->From = $from;
            $mail->FromName = $name;
            $mail->Subject = $subject;
            $mail->IsHTML(TRUE);
            $mail->MsgHTML($html);
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                )
            );

            if( $destino != ""){   
                $mail->AddAddress($destino);   
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

    public function configuracionNotificacion($protocolo, $secure, $host, $port, $email, $pass, $from, $fromname){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $conf = $this->getConfiguracionNoti($cedente);

        if($conf){
            $sqlContra = "";
            if($pass != ""){
                $sqlContra = "contrasena = '".$pass."', ";
            }

            $sql = "UPDATE 
                            configuracion_notificacion 
                        SET 
                            protocolo = '".$protocolo."', 
                            secure = '".$secure."', 
                            host = '".$host."', 
                            puerto = '".$port."', 
                            email = '".$email."', 
                            " . $sqlContra . "
                            from_email = '".$from."', 
                            from_name = '".$fromname."'
                        WHERE 
                            id_cedente = '" . $cedente . "'";
        }else{
            $sql = "INSERT INTO configuracion_notificacion (id_cedente, protocolo, secure, host, puerto, email, contrasena, from_email, from_name) VALUES ('".$cedente."', '".$protocolo."', '".$secure."', '".$host."', '".$port."', '".$email."', '".$pass."','".$from."', '".$fromname."')";
        }

        $result = $db->query($sql);

        return $result;
    }

    public function getConfiguracionNoti($cedente){
        $db = new DB();

        $sql = "SELECT * FROM configuracion_notificacion WHERE id_cedente = '" . $cedente . "'";
        $result = $db->select($sql);

        return $result;
    }

    public function fillQueues($estrategia){
        $db = new DB();

        $cad = strrchr($estrategia, "_");
		$idQueEstra = substr($cad, 1);

        $sql = "SELECT asignacion FROM asignacion_cola WHERE id_cola = '".$idQueEstra."'";

        $asignaciones = $db->select($sql);

        $response = array();
        if(is_array($asignaciones)){
            foreach($asignaciones as $asignacion){
                $arreglo = array();
                $arreglo = $this->findAsignacion($asignacion["asignacion"]);

                array_push($response, $arreglo);
            }
        }

        return $response;        
    }

    private function findAsignacion($colas){
		$db = new DB();
		$asignacion = array ();
		$asig = explode("_", $colas);
        if(isset($asig[3])){
            if($asig[3] == "G"){
                $asignaciones = $db->select("SELECT Nombre FROM grupos WHERE IdGrupo = ".$asig[4]."");
                $asignacion["id"] = $colas;
                $asignacion["asignacion"] = "Asignación Grupo " . $asignaciones[0]["Nombre"];
            }else if($asig[3] == "S"){
                $asignaciones = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = ".$asig[4]."");
                $asignacion["id"] = $colas;
                $asignacion["asignacion"] = "Asignación Supervisor " . $asignaciones[0]["Nombre"];
            }else if($asig[3] == "E"){
                $asignaciones = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = ".$asig[4]."");
                $asignacion["id"] = $colas;
                $asignacion["asignacion"] = "Asignación Ejecutivo " . $asignaciones[0]["Nombre"];
            }else if($asig[3] == "EE"){
                $asignaciones = $db->select("SELECT Nombre FROM empresa_externa WHERE IdEmpresaExterna = ".$asig[4]."");
                $asignacion["id"] = $colas;
                $asignacion["asignacion"] = "Asignación Empresa Externa " . $asignaciones[0]["Nombre"];
            }
        }

		return $asignacion;
    }
    public function mensajesEnviados(){
		$db = new DB();
		$query = "	SELECT 
						SUM(cantidad) as cantidad
					FROM 
						envio_sms  
					WHERE
						id_cedente = '".$_SESSION['cedente']."'";
		$cantidad = $db->select($query);
		if($cantidad[0]["cantidad"]){
			$cantidad = $cantidad[0]["cantidad"];
		}else{
			$cantidad = 0;
		}
		return $cantidad;
    }
    public function fillFechas($estrategia){
        $db = new DB();
        $query = "	SELECT 
						id, fechaHora
					FROM 
						envio_sms
					WHERE 
						asignacion = '".$estrategia ."'";

        $fechas = $db->select($query);
		$response = array();
		
        if($fechas){
            foreach($fechas as $fecha){
				$arreglo = array();
				$arreglo['id'] = $fecha['id'];
				$arreglo['fechahora'] = $fecha['fechaHora'];
                array_push($response, $arreglo);
            }
        }

        return $response;        
    }
    public function getSMSEstadistica($id_envio){
		$db = new DB();
		$response = array();
		$response['dataSet'] = array();
		$Entregado = 0;
		$No_Entregado = 0;
        $Rechazado = 0;
        $Pendiente = 0;
        $Error_Proveedor = 0;
        $query = "	SELECT 
						p.Rut, p.Nombre_Completo as Nombre, d.fono as Fono, d.estado as Estado
					FROM 
						Persona p
					INNER JOIN
						detalle_envio_sms d
					ON
						p.Rut = d.rut
					WHERE
						d.id_envio_sms = '".$id_envio."'";

        $Envios = $db->select($query);
        if($Envios){
            foreach($Envios as $Envio){
				$Estado = $Envio['Estado'];
				if($Estado == 'ENTREGADO'){
					$Entregado++;
				}else if($Estado == 'NO ENTREGADO'){
					$No_Entregado++;
				}else if($Estado == 'RECHAZADO'){
					$Rechazado++;
				}else if($Estado == 'PENDIENTE'){
					$Pendiente++;
				}else{
					$Error_Proveedor++;
				}
				$arreglo = array();
				$arreglo['Rut'] = $Envio['Rut'];
				$arreglo['Nombre'] = $Envio['Nombre'];
				$arreglo['Fono'] = $Envio['Fono'];
				$arreglo['Estado'] = $Estado;
				array_push($response['dataSet'], $arreglo);
            }
		}
		$Enviados = $Entregado + $No_Entregado + $Rechazado + $Pendiente + $Error_Proveedor;
		$response['Enviados'] = $Enviados;
		$response['Entregado'] = $Entregado;
		$response['No_Entregado'] = $No_Entregado;
        $response['Rechazado'] = $Rechazado;
        $response['Pendiente'] = $Pendiente;
        $response['Error_Proveedor'] = $Error_Proveedor;
        return $response;        
	}
}
?>