<?php
class Supervision
{
	public function getAgentesEstatus($queue = "", $estatus = "", $cedente = "")
	{
		$db = new DB();
		$dbDiscador = new DB("discador");

		$whereEstatus = "";
		if ($estatus !== ""){
			if($estatus == "HABLANDO"){
				$anexos = array();
				$hablando = $dbDiscador->select("SELECT Anexo FROM Asterisk_Bridge");

				if(count($hablando) > 0){
					foreach ($hablando as $habla){
						array_push($anexos, "'SIP/" . $habla["Anexo"] . "'");
					}

					$agentes = implode(",", $anexos);

					$whereEstatus = " AND ro.anexo IN ($agentes)";
				}
				else{
					return $anexos;
				}
			}else{
				$whereEstatus = " AND ro.estatus = '" . $estatus . "'";
			}
		}

		$whereQueue = "";
		if($queue !== ""){
			$agentes = $dbDiscador->select("SELECT Agente FROM Asterisk_Agentes WHERE Queue = '" . $queue . "'");

			$anexos = array();

			if (count($agentes) > 0){
				foreach($agentes as $agente){
					array_push($anexos, "'" . $agente["Agente"] . "'");
				}

				$anexosIn = implode(",", $anexos);

				$whereQueue = " AND ro.anexo IN ($anexosIn)";
			}else{
				return $anexos;
			}
		}

		$this->updateTiempoTranscurrido();

		$whereCedente = "";
		if($cedente !== ""){
			$whereCedente = " AND ro.cartera = '" . $cedente . "'";
		}

		$sql = "SELECT 
					ro.anexo, 
					ro.estatus, 
					ro.ejecutivo, 
					ro.tiempo, 
					ro.pausa,
					ro.cartera,
					aa.Queue
				FROM 
					reporteOnLine ro
				INNER JOIN
					Asterisk_Agentes aa
				ON
					ro.anexo = aa.Agente
				WHERE 
					activo = '1'" 
					. $whereCedente 
					. $whereEstatus 
					. $whereQueue . " 
				ORDER BY 
					ro.estatus";

		$rows = $dbDiscador->select($sql);
		$Array = array();
		foreach($rows as $row){
			$cedente = $row['cartera'];
			$Queue = $row['Queue'];
			$query = "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$cedente."'";
			$Nombre_Cedente = $db->select($query);
			if($Nombre_Cedente){
				$cedente = $Nombre_Cedente[0]['Nombre_Cedente'];
			}else{
				$cedente = '';
			}
			$sql = "SELECT 
						ac.Cola
					FROM 
						Asterisk_Discador_Cola ac
					INNER JOIN 
						Asterisk_All_Queues aq on aq.id_discador = ac.id 
					WHERE
						aq.Queue = '".$Queue."'";
			$resu = $db->select($sql);
			if($resu){
				foreach($resu as $fila){
					$cola = $fila["Cola"];
					$array = explode('_',$cola);
					$tipo = $array[3];
					$id = $array[4];
					switch ($tipo){
						case 'G':
							$sql2 = "SELECT Nombre FROM grupos WHERE IdGrupo = '".$id."'";
							$resu2 = $db->select($sql2);
							$nombre = $resu2[0]["Nombre"];
						break;
						case 'E':
						case 'S':
							$sql3 = "SELECT Nombre FROM Personal WHERE Id_Personal = '".$id."'";
							$resu3 = $db->select($sql3);
							$nombre = $resu3[0]["Nombre"];
						break;
					}
					$Cola = $nombre." - ".$Queue; 
				}
			}else{
				$Cola = '';
			}

			$ArrayTmp = array();
			$ArrayTmp['anexo'] = $row['anexo'];
			$ArrayTmp['estatus'] = $row['estatus'];
			$ArrayTmp['ejecutivo'] = $row['ejecutivo'];
			$ArrayTmp['tiempo'] = $row['tiempo'];
			$ArrayTmp['pausa'] = $row['pausa'];
			$ArrayTmp['cedente'] = $cedente;
			$ArrayTmp['Cola'] = $Cola;
			array_push($Array,$ArrayTmp);
		}
        return $Array;
	}

	public function getColas($cedente = '')
	{
		$db 		= new DB();
		$response 	= array();

		if($cedente !== ""){
			$whereCedente = " WHERE Id_Cedente = '" . $cedente . "'";
		}

		$sql 	= "	SELECT 
						adc.Cola, aq.Queue
					FROM 
						Asterisk_Discador_Cola AS adc
					INNER JOIN 
						Asterisk_All_Queues AS aq 
					ON 
						adc.id = aq.id_discador" 
					. $whereCedente
					;

		$colas 	= $db->select($sql);

		foreach($colas as $cola){
			$arreglo = array();
			$asignacion = $this->findAsignacion($cola["Cola"]);
			$arreglo["asignacion"] = $asignacion["asignacion"];
			$arreglo["queue"] = $cola["Queue"];

			array_push($response, $arreglo);
		}

        return $response;
	}

	private function findAsignacion($colas){
		$db = new DB();
		$asignacion = array ();
		$asig = explode("_", $colas);

		if($asig[3] == "G"){
			$asignaciones = $db->select("SELECT Nombre FROM grupos WHERE IdGrupo = ".$asig[4]."");
			$asignacion["id"] = $colas;
			if($asignaciones){
				$Nombre = $asignaciones[0]["Nombre"];
			}else{
				$Nombre = '';
			}
			$asignacion["asignacion"] = "Asignación Grupo " . $Nombre;
		}else if($asig[3] == "S"){
			$asignaciones = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = ".$asig[4]."");
			$asignacion["id"] = $colas;
			if($asignaciones){
				$Nombre = $asignaciones[0]["Nombre"];
			}else{
				$Nombre = '';
			}
			$asignacion["asignacion"] = "Asignación Supervisor " . $Nombre;
		}else if($asig[3] == "E"){
			$asignaciones = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = ".$asig[4]."");
			$asignacion["id"] = $colas;
			if($asignaciones){
				$Nombre = $asignaciones[0]["Nombre"];
			}else{
				$Nombre = '';
			}
			$asignacion["asignacion"] = "Asignación Ejecutivo " . $Nombre;
		}else if($asig[3] == "EE"){
			$asignaciones = $db->select("SELECT Nombre FROM empresa_externa WHERE IdEmpresaExterna = ".$asig[4]."");
			$asignacion["id"] = $colas;
			if($asignaciones){
				$Nombre = $asignaciones[0]["Nombre"];
			}else{
				$Nombre = '';
			}
			$asignacion["asignacion"] = "Asignación Empresa Externa " . $Nombre;
		}

		return $asignacion;
	}

	public function deletePuesto($anexo){

		$foco_config = getFocoConfig();
		$updated = false;

		$dbDiscador = new DB("discador");

		$delete = $dbDiscador->query("DELETE FROM Asterisk_Agentes WHERE Agente = '" . $anexo . "' ");

		if($delete)
		{
			$updated = true;
		}
		
		$asm = new AGI_AsteriskManager();
		$asm->connect($foco_config['IpServidorDiscado'],"lponce","lponce");

		$db = new DB();
		$SqlQueues = "select Queue from Asterisk_All_Queues order by Queue";
		$Queues = $db->select($SqlQueues);
		foreach($Queues as $Queue){
			$Command = "queue remove member " . $anexo . " from " . $Queue["Queue"];
			$Result = $asm->Command($Command);
		}
		$asm->disconnect();

		$update = $dbDiscador->query("UPDATE reporteOnLine SET activo = 0 WHERE anexo = '" . $anexo . "'");

		if($update)
		{
			$updated = true;
		}

		return $updated;
	}

	public function deletePuestoByQueue($anexo, $queue){

		$foco_config = getFocoConfig();
		$updated = false;

		$dbDiscador = new DB("discador");
		$anexo = "SIP/" . $anexo;
		$delete = $dbDiscador->query("DELETE FROM Asterisk_Agentes WHERE Agente = '" . $anexo . "' AND Queue = '" . $queue ."'");

		$asm = new AGI_AsteriskManager();
		$asm->connect($foco_config['IpServidorDiscado'],"lponce","lponce");

		$Command = "queue remove member " . $anexo . " from " . $queue;
		$Result = $asm->Command($Command);
		$asm->disconnect();
	}

	public function getAnexos()
	{
		$db = new DB();

        $rows = $db->select("SELECT u.anexo_foco, p.Nombre FROM Usuarios AS u INNER JOIN Personal AS p ON (u.id = p.id_usuario) WHERE u.anexo_foco <> '0' ORDER BY u.anexo_foco DESC");

        return $rows;
	}

	public function getListas()
	{
		$db = new DB();

        $rows = $db->select("SELECT Queue FROM Asterisk_All_Queues ORDER BY Queue DESC");

        return $rows;
	}

	public function getTipoContacto($ratio){
		$db = new DB();

		$sql = "";

		if($ratio == "Penetracion"){
			$sql = "SELECT 
						Id_TipoContacto, Nombre 
					FROM 
						Tipo_Contacto 
					WHERE Id_TipoContacto NOT IN (SELECT id_tipo_contacto FROM ratios_tipo_contacto WHERE id_ratio = 1)
					ORDER BY 1";
		}else{
			$sql = "SELECT 
						Id_TipoContacto, Nombre 
					FROM 
						Tipo_Contacto 
					WHERE Id_TipoContacto NOT IN (SELECT id_tipo_contacto FROM ratios_tipo_contacto WHERE id_ratio = 2)
					ORDER BY 1";
		}

        $rows = $db->select($sql);

        return $rows;
	}

	public function getGestionCola($cola){
		$db = new DB();

		$result = $this->getIDColaFromQueue($cola);

		if($result){
			$cola = $result["id_cola"];

			$sql = "SELECT 
						count(nc.nivel1) AS cantidad, Respuesta_N1 AS label
					FROM 
						titular_niveles_cola AS nc
					INNER JOIN 
						Nivel1 AS n1 ON (n1.Id = nc.nivel1) 
					WHERE 
						nc.id_cola = '" . $cola . "' 
					GROUP BY nc.nivel1, Respuesta_N1";
			$rows = $db->select($sql);
			if(!$rows){
				$rows = '';
			}
		}else{
			$rows = '';
		}

		return $rows;
	}

	public function getGestionColaNivel2($cola, $nivel1){
		$db = new DB();

		$result = $this->getIDColaFromQueue($cola);

		if(count($result) > 0){
			$cola = $result["id_cola"];
			$sql = "SELECT 
						count(nc.nivel2) AS cantidad , n2.Respuesta_N2 AS label 
					FROM 
						titular_niveles_cola AS nc
					INNER JOIN 
						Nivel1 AS n1 ON (n1.Id = nc.nivel1) 
					INNER JOIN 
						Nivel2 AS n2 ON (n2.id = nc.nivel2)
					WHERE 
						nc.id_cola = '" . $cola . "' 
						AND n1.Respuesta_N1 = '" . utf8_decode($nivel1) . "' 
					GROUP BY nc.nivel2, Respuesta_N2";
		}

		$rows = $db->select($sql);

		return $rows;
	}

	public function getGestionColaNivel3($cola, $nivel1, $nivel2){
		$db = new DB();

		$result = $this->getIDColaFromQueue($cola);

		if(count($result) > 0){
			$cola = $result["id_cola"];
			$sql = "SELECT 
						count(nc.nivel3) AS cantidad, n3.Respuesta_N3 AS label
					FROM 
						titular_niveles_cola AS nc
					INNER JOIN 
						Nivel1 AS n1 ON (n1.Id = nc.nivel1) 
					INNER JOIN 
						Nivel2 AS n2 ON (n2.id = nc.nivel2)
					INNER JOIN
						Nivel3 AS n3 ON (n3.id = nc.nivel3)
					WHERE 
						nc.id_cola = '" . $cola . "' 
						AND n1.Respuesta_N1 = '" . utf8_decode($nivel1) . "' 
						AND n2.Respuesta_N2 = '" . utf8_decode($nivel2) . "' 
					GROUP BY nc.nivel3, Respuesta_N3";
		}

		$rows = $db->select($sql);

		return $rows;
	}

	public function getGestiones($cola, $nivel1, $nivel2){
		$db = new DB();

		$result = $this->getIDColaFromQueue($cola);

		if(count($result) > 0){
			$cola = $result["id_cola"];

			$sqlNivel2 = "";
			if ($nivel2 != ""){
				$sqlNivel2 = " AND n2.Respuesta_N2 = '" . utf8_decode($nivel2) . "'";
			}

			$sql = "SELECT 
						qe.cola AS cola, 
						nc.rut AS rut, 
						n1.Respuesta_N1 AS nivel1, 
						n2.Respuesta_N2 AS nivel2, 
						n3.Respuesta_N3 AS nivel3,
						FORMAT(nc.fecha_hora, 'yyyy/MM/dd H:mm:ss') AS fecha
					FROM 
						titular_niveles_cola AS nc
					INNER JOIN
						SIS_Querys_Estrategias AS qe ON (qe.id = nc.id_cola)
					INNER JOIN 
						Nivel1 AS n1 ON (n1.Id = nc.nivel1) 
					INNER JOIN 
						Nivel2 AS n2 ON (n2.id = nc.nivel2)
					INNER JOIN
						Nivel3 AS n3 ON (n3.id = nc.nivel3)
					WHERE 
						nc.id_cola = '" . $cola . "' 
						AND n1.Respuesta_N1 = '" . utf8_decode($nivel1) . "'" . $sqlNivel2;
		}

		$rows = $db->select($sql);

		return $rows;
	}

	public function downloadReporteGestion($cola, $nivel1, $nivel2){
		$gestiones = $this->getGestiones($cola, $nivel1, $nivel2);

		$fileName = "Prueba";
		$objPHPExcel = new PHPExcel();
		ob_start();
		$objPHPExcel->
			getProperties()
				->setCreator("CRM Sinaptica")
				->setLastModifiedBy("CRM Sinaptica");
		
		$objPHPExcel->removeSheetByIndex(
			$objPHPExcel->getIndex(
				$objPHPExcel->getSheetByName('Worksheet')
			)
		);
		$NextSheet = 0;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Reporte por Tipo de Gestión');

		$style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

		$columnLetter = PHPExcel_Cell::stringFromColumnIndex(0);

		$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFont()->setBold(true);
    	$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E64FE');

		$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(1,1,"COLA");
		$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(2,1,"RUT");
		$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(3,1,"GESTIÓN NIVEL 1");
		$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(4,1,"GESTIÓN NIVEL 2");
		$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(5,1,"GESTIÓN NIVEL 3");
		$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(6,1,"FECHA");

		$row = 2;
		$nombreCola = "";

		foreach($gestiones as $gestion){
			$col = 1;
			$nombreCola = $gestion["cola"];
			$objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($col,$row,$gestion["cola"]);
			$objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($col+1,$row,$gestion["rut"]);
			$objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($col+2,$row,$gestion["nivel1"]);
			$objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($col+3,$row,$gestion["nivel2"]);
			$objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($col+4,$row,$gestion["nivel3"]);
			$objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($col+5,$row,$gestion["fecha"]);
			$row++;
		}

		$objPHPExcel->setActiveSheetIndex(0);

		foreach(range('B','G') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$fileName.'".xlsx');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$objWriter->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();
		$response =  array(
			'filename' => "Reporte Gestión Cola - ".$nombreCola." - ".date("d-m-Y") ,
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);

		return $response;
	}

	public function findQueues($anexo){
		$db = new DB();

        $rows = $db->select("SELECT Queue FROM Asterisk_All_Queues");

		$queues = array();
		$datos = array();

		$foco_config = getFocoConfig();
		$asm = new AGI_AsteriskManager();
		$asm->connect($foco_config['IpServidorDiscado'],"lponce","lponce");

		foreach($rows as $row){
			$Command = "queue show " . $row["Queue"];

			$Result = $asm->Command($Command);
			$queues["queue"] = $row["Queue"];

			if (strpos($Result["data"], "SIP/". trim($anexo)) !== false){
				array_push($datos, $queues);
			}
		}

		$asm->disconnect();

		return $datos;
	}

	public function getAnexosReporte($anexo){

		$foco_config = getFocoConfig();

		$asm = new AGI_AsteriskManager();
		$asm->connect($foco_config['IpServidorDiscado'],"lponce","lponce");

		$foco_config['IpServidorDiscado'];
		$Command = "sip show peer " . $anexo;

		$Result = $asm->Command($Command);

		$arrayResult = $this->llenarArregloAsterisk($Result);

		$asm->disconnect();

		return $arrayResult;
	}

	public function getListasReporte($lista){

		$foco_config = getFocoConfig();

		$asm = new AGI_AsteriskManager();
		$asm->connect($foco_config['IpServidorDiscado'],"lponce","lponce");

		$Command = "queue show " . $lista;
		$Result = $asm->Command($Command);

		$arrayResult = $this->llenarArregloLista($Result);

		$asm->disconnect();

		return $arrayResult;
	}

	public function getEnCola($queue = ""){
		$foco_config = getFocoConfig();
		$asm = new AGI_AsteriskManager();
		$asm->connect($foco_config['IpServidorDiscado'],"lponce","lponce");

		$llamadas = array();
		$arreglo = array();
		$dbDiscador = new DB("discador");

		if ($queue !== ""){
			$Command = "queue show " . $queue;
			$Result = $asm->Command($Command);
			
			$arreglo["enCola"] = $this->llamadasEnCola($Result);
			array_push($llamadas, $arreglo);
		}else{
			$colas = $dbDiscador->select("SELECT Queue FROM Asterisk_All_Queues WHERE Estado = 1");

			$contador = 0;
			if($colas){
				foreach($colas as $cola){
					$Command = "queue show " . $cola["Queue"];
					$Result = $asm->Command($Command);

					$cont = $this->llamadasEnCola($Result);
					$contador = $contador + $cont;
				}
			}
			$arreglo["enCola"] = $contador;
			array_push($llamadas, $arreglo);
		}

		$asm->disconnect();

		return $llamadas;
	}

	public function verGestion($cola, $label)
	{
		$this->cola	= $cola;
		$this->label= $label;
		session_start();
		$_SESSION['cola'] = $this->cola;
		$_SESSION['label'] = $this->label;
	}

	public function guardarRatioTipoContacto($ratio, $arrayTipoContacto){
		$db = new DB();

		foreach($arrayTipoContacto as $tipoContacto){

			$sql = "INSERT INTO 
							ratios_tipo_contacto (id_ratio, id_tipo_contacto) 
						VALUES ('" . $ratio . "' ,'". $tipoContacto . "')";
			if(!$db->query($sql)){
				return false;
			}
		}
		return true;
	}

	public function guardarMuestra($tiempo, $muestra, $ratios){
		$db = new DB();

		$rates = implode(",", $ratios);

		$sql = "UPDATE 
						ratios_mantenedor 
					SET 
						muestra = '" . $muestra . "', 
						tiempo_act = '" . $tiempo . "' 
					WHERE id IN (" . $rates . ")";

		if($db->query($sql)){
			return true;
		}
		else{
			return false;
		}
	}

	public function getRatioTipoContacto($ratio){
		$db = new DB();
		$sql = "SELECT 
					tc.Id_TipoContacto, tc.Nombre 
				FROM ratios_tipo_contacto AS rp 
					INNER JOIN Tipo_Contacto AS tc ON (rp.id_tipo_contacto = tc.Id_TipoContacto)
				WHERE rp.id_ratio = '" . $ratio . "'";

		$rows = $db->select($sql);

		return $rows;
	}

	public function getRatioPenetracion(){
		$db = new DB();
		$sql = "SELECT 
					tc.Id_TipoContacto, tc.Nombre 
				FROM ratio_penetracion AS rp 
					INNER JOIN Tipo_Contacto AS tc ON (rp.id_tipo_contacto = tc.Id_TipoContacto)";

		$rows = $db->select($sql);

		return $rows;
	}

	public function getRatioContactabilidad(){
		$db = new DB();
		$sql = "SELECT 
					tc.Id_TipoContacto, tc.Nombre 
				FROM ratio_contactabilidad AS rc 
					INNER JOIN Tipo_Contacto AS tc ON (rc.id_tipo_contacto = tc.Id_TipoContacto)";

		$rows = $db->select($sql);

		return $rows;
	}

	public function getRatios(){
		$db = new DB();
		$sql = "SELECT id, ratio FROM ratios_mantenedor";
		$rows = $db->select($sql);

		return $rows;
	}

	public function getMuestraRatios(){
		$db = new DB();
		$sql = "SELECT * FROM ratios_mantenedor";
		$rows = $db->select($sql);

		return $rows;
	}

	public function getEstatusAgentes($que = "", $cedente = ""){
		$dbDiscador = new DB("discador");
		$aux 	= array();

		$arreglo 	= array();
		$whereAnexo = "";

		if($que !== ""){

			$agentes = $dbDiscador->select("SELECT Agente FROM Asterisk_Agentes WHERE Queue = '" . $que . "'");

			if(count($agentes) > 0){
				$anexos = array();
				foreach($agentes as $agente){
					array_push($anexos, "'" . $agente["Agente"] . "'");
				}

				$anexosIn = implode(",", $anexos);
				$whereAnexo = " AND ro.anexo IN ($anexosIn)";
			}else{
				$aux["conectados"]  = 0;
				$aux["disponible"]  = 0;
				$aux["pausados"] 	= 0;
				$aux["dead"] 		= 0;
				array_push($arreglo, $aux);

				return $arreglo;
			}
		}
		$whereCedente = "";
		if($cedente !== ""){
			$whereCedente = " AND ro.cartera = '".$cedente."'";
		}

		/********************************************************
		****************** AGENTES CONECTADOS *******************
		********************************************************/
		$sql 	= "	SELECT 
						count(ro.anexo) AS con 
					FROM 
						reporteOnLine ro
					INNER JOIN
						Asterisk_Agentes aa
					ON
						ro.anexo = aa.Agente
					WHERE 
						activo = 1 
					" 
					. $whereCedente . $whereAnexo;
		$sqlCon = $dbDiscador->select($sql);

		$aux["conectados"] = $sqlCon[0]["con"];

		$aux["dead"] = 0;
		$aux["hablando"] = 0;
		$aux["pausados"] = 0;
		$aux["disponible"] = 0;

		/********************************************************
		******************* ESTADOS AGENTES *********************
		********************************************************/

		$sql = "SELECT 
					COUNT(ro.anexo) AS cantidad, ro.estatus 
				FROM 
					reporteOnLine ro 
				INNER JOIN
					Asterisk_Agentes aa
				ON
					ro.anexo = aa.Agente
				WHERE 
					ro.activo = 1" . 
					$whereCedente . 
					$whereAnexo ." 
				GROUP BY 
					ro.estatus";

		$estados = $dbDiscador->select($sql);

		foreach($estados as $estado){
			$est = $estado["estatus"];

			switch($est){
				case "DISPONIBLE":
					$aux["disponible"] = $estado["cantidad"];
				break;
				case "PAUSADO":
					$aux["pausados"] = $estado["cantidad"];
				break;
				case "MUERTO":
					$aux["dead"] = $estado["cantidad"];
				break;
				case "EN LLAMADA":
					$aux["hablando"] = $estado["cantidad"];
				break;
			}
		}

		array_push($arreglo, $aux);

		return $arreglo;
	}

	public function getCantidadLlamadas($que = ""){
		$db = new DB("discador");

		$whereAnexo = "";
		if($que !== ""){
			$whereAnexo = " INNER JOIN Asterisk_Agentes AS aa ON 
								(ab.Anexo = SUBSTRING(aa.Agente, LENGTH(aa.Agente) -4, 4)) 
							WHERE aa.Queue = '" . $que . "'";
		}

		$sql = "SELECT count(ab.id) AS hablando FROM Asterisk_Bridge AS ab" . $whereAnexo;

		$rows = $db->select($sql);

		return $rows;
	}

	public function getHablando($que = "", $cedente = ""){
		$dbDiscador = new DB("discador");

		$innerAnexo = "";
		$whereAnexo = "";
		if($que !== ""){
			$innerAnexo = " INNER JOIN Asterisk_Agentes AS aa ON (ab.Anexo = SUBSTRING(aa.Agente, LENGTH(aa.Agente) -4, 4)) ";
			$whereAnexo = " AND aa.Queue = '" . $que . "'";
		}
		if($cedente !== ""){
			$whereCedente = " AND ab.Cedente = '" . $cedente . "'";
		}

		$sql = "SELECT 
					count(ab.id) AS hablando 
				FROM 
					Asterisk_Bridge AS ab
					" . $innerAnexo . "
				WHERE 
					1 = 1 " 
					. $whereCedente 
					. $whereAnexo;

		$rows = $dbDiscador->select($sql);

		return $rows;
	}

	public function getDescartados($que = "", $cedente = ""){
		$dbDiscador = new DB("discador");
		$db = new DB();
		$descartados = 0;

		$whereAnexo = "";
		if($que !== ""){
			$whereAnexo = " AND Queue = '" . $que . "'";
		}

		$sql = "SELECT 
					id_discador
				FROM 
					Asterisk_Hangup_History 
				WHERE 
					Cause <> 16 
				AND 
					FechaHora > '" . date("Y-m-d 00:00:00") . "' 
				AND 
					FechaHora < '" . date("Y-m-d 23:59:59") . "'" 
				. $whereAnexo;

		$rows = $dbDiscador->select($sql);
		if($rows){
			foreach($rows as $row){
				if($cedente){
					$id_discador = $row['id_discador'];
					$query = "SELECT Id_Cedente FROM Asterisk_Discador_Cola WHERE id = '".$id_discador."'";
					$Asterisk_Discador_Cola = $db->select($query);
					if($Asterisk_Discador_Cola){
						$Id_Cedente = $Asterisk_Discador_Cola[0]['Id_Cedente'];
						if($Id_Cedente == $cedente){
							$descartados++;
						}
					}
				}else{
					$descartados++;
				}
			}
		}
		
		return $descartados;
	}

	public function getDetalleDescartadas($que = ""){
		$dbDiscador = new DB("discador");

		$whereAnexo = "";
		if($que !== ""){
			$whereAnexo = " AND Queue = '" . $que . "'";
		}

		$sql = "SELECT 
					COUNT(id) AS cantidad, CauseTxt AS label 
				FROM 
					Asterisk_Hangup_History 
				WHERE 
					Cause <> 16 
					AND FechaHora > '" . date("Y-m-d 00:00:00") . "' 
					AND FechaHora < '" . date("Y-m-d 23:59:59") . "'" . $whereAnexo;

		$rows = $dbDiscador->select($sql);

		if($rows[0]["cantidad"] == 0){
			return $rows = array();
		}else{
			return $rows;
		}
	}

	public function getHoy($que = "", $cedente = ""){
		$dbDiscador = new DB("discador");

		$whereAnexo = "";
		$whereCedente = "";
		if($que !== ""){
			$whereAnexo = " AND Queue = '" . $que . "'";
		}
		if($cedente !== ""){
			$whereCedente = " AND Cedente = '" . $cedente . "'";
		}

		$sql = "SELECT 
					COUNT(id) as hoy 
				FROM 
					Asterisk_Bridge_History 
				WHERE 
					FechaHora > '" . date("Y-m-d 00:00:00") . "' 
				AND 
					FechaHora < '" . date("Y-m-d 23:59:59") . "'" 
				. $whereCedente 
				. $whereAnexo;

		$rows = $dbDiscador->select($sql);

		return $rows;
	}

	public function getRatiosMonitoreo($que = ""){
		$db = new DB();

		$whereAnexo = "";
		if($que !== ""){
			$sqlCola = "SELECT 
							ac.id_cola 
						FROM 
							asignacion_cola AS ac 
							INNER JOIN Asterisk_Discador_Cola AS adc ON (adc.Cola = ac.asignacion)
							INNER JOIN Asterisk_All_Queues AS aq ON (aq.id_discador = adc.id)
						WHERE 
							aq.Queue = '" . $que . "'";

			$result = $db->select($sqlCola);

			if(count($result) > 0){
				$cola = $result[0]["id_cola"];

				$sql = "SELECT porcentaje FROM ratios_cola WHERE id_cola = '" . $cola . "' ORDER BY id_ratio";
			}
		}else{
			$sql = "SELECT SUM(porcentaje) AS porcentaje, COUNT(DISTINCT id_cola) AS colas FROM ratios_cola GROUP BY id_ratio";
		}

		$rows = $db->select($sql);

		$arreglo = array();
		if($rows){
			if($que !== ""){
				$arreglo["penetracion"] = $rows[0]["porcentaje"];
				$arreglo["contactabilidad"] = $rows[1]["porcentaje"];
			}else{
				$arreglo["penetracion"] = round(($rows[0]["porcentaje"]/$rows[0]["colas"]), 0);
				$arreglo["contactabilidad"] = round(($rows[1]["porcentaje"]/$rows[0]["colas"]), 0);
			}
		}else{
			$arreglo["penetracion"] = 0;
			$arreglo["contactabilidad"] = 0;
		}
		return $arreglo;
	}

	public function getPenetracion($que = ""){
		$db = new DB();

		$whereAnexo = "";
		if($que !== ""){
			$whereAnexo = " AND id_cola = '" . $que . "'";
		}

		$sql = "SELECT 
					porcentaje
				FROM 
					ratios_cola 
				WHERE 
					id_ratio = 1" . $whereAnexo;

		$rows = $db->select($sql);

		return $rows;
	}

	public function deleteTipoContacto($contacto, $ratio){
		$db = new DB();

		$sql = "";

		if($ratio == "Penetracion"){
			$sql = "DELETE FROM 
						ratios_tipo_contacto 
					WHERE 
						id_tipo_contacto = '" . $contacto . "' 
						AND id_ratio = '1'";
		}else{
			$sql = "DELETE FROM 
						ratios_tipo_contacto 
					WHERE 
						id_tipo_contacto = '" . $contacto . "' 
						AND id_ratio = '2'";
		}

		if($db->query($sql)){
			return true;
		}else{
			return false;
		}
	}

	private function getIDColaFromQueue($cola){
		$db = new DB();

		$sqlCola = "SELECT 
						ac.id_cola 
					FROM 
						asignacion_cola AS ac 
						INNER JOIN Asterisk_Discador_Cola AS adc ON (adc.Cola = ac.asignacion)
						INNER JOIN Asterisk_All_Queues AS aq ON (aq.id_discador = adc.id)
					WHERE 
						aq.Queue = '" . $cola . "'";

		$result = $db->select($sqlCola);

		if($result){
			return $result[0];
		}else{
			return '';
		}
	}

	private function llenarArregloAsterisk($response){
		$data = explode ("\n", $response["data"]);
		$cont = count($data);

		$datos = [];

		for ($i=0; $i < $cont; $i++){
			$aux = explode(": ", $data[$i]);

			if(isset($aux[1])){
				$datos [trim($aux[0])] = trim($aux[1]);
			}
		}
		return $datos;
	}

	private function llenarArregloLista($response){
		$data = explode ("\n", $response["data"]);
		$cont = count($data);

		$datos = array();
		$membersArray = array();
		$callersArray = array();

		$datos["membersArray"] = array();
		$datos["callersArray"] = array();

		$mcount = 0;
		$ccount = 0;

		$members = false;
		$callers = false;

		for ($i=0; $i < $cont; $i++){
			if ($callers){
				if((strpos($data[$i], 'No Callers') === false)){
					if(trim($data[$i]) != ""){
						$callersArray["caller"] = trim($data[$i]);
						array_push($datos["callersArray"], $callersArray);
					}
				}
			}

			if ((strpos($data[$i], 'Callers') === false) && ($members)){
				$membersArray["member"] = trim($data[$i]);
				array_push($datos["membersArray"],$membersArray);
			}else if ((strpos($data[$i], 'Callers')) !== false){
				$callers = true;
				$members = false;
			}

			if((strpos($data[$i], "Members:")) !== false){
				$members = true;
			}
		}

		return $datos;
	}

	private function llamadasEnCola($response){
		$data = explode ("\n", $response["data"]);
		$cont = count($data);

		$ccount = 0;

		$callers = false;

		for ($i=0; $i < $cont; $i++){
			if ($callers){
				if(trim($data[$i]) != ""){
					$ccount++;
				}
			}

			if ((strpos($data[$i], 'Callers:')) !== false){
				$callers = true;
			}
		}

		return $ccount;
	}

	private function updateTiempoTranscurrido(){
		$dbDiscador = new DB('discador');
		$horaActual = date('H:i:s');
		$sql = "SELECT inicio, id_reporte FROM reporteOnLine WHERE activo = 1";
		$resultado = $dbDiscador->select($sql);
		foreach($resultado as $fila){
			$horaInicio = $fila["inicio"];
			$idReporte = $fila["id_reporte"];
			$tiempo = $this->diferenciaEntreHoras($horaInicio,$horaActual);
			$sqlTiempo = "UPDATE reporteOnLine SET tiempo = '".$tiempo."' WHERE id_reporte = '".$idReporte."'";
			$dbDiscador->query($sqlTiempo);
		}
	}

	private function diferenciaEntreHoras($PrimeraFecha,$UltimaFecha){
		$PrimeraFecha = date($PrimeraFecha);
		$UltimaFecha = date($UltimaFecha);
		$PrimeraFecha = new DateTime($PrimeraFecha);
		$UltimaFecha = new DateTime($UltimaFecha);
		$Diferencia = $PrimeraFecha->diff($UltimaFecha);
		$Horas = strlen($Diferencia->h) > 1 ? $Diferencia->h : "0".$Diferencia->h;
		$Minutos = strlen($Diferencia->i) > 1 ? $Diferencia->i : "0".$Diferencia->i;
		$Segundos = strlen($Diferencia->s) > 1 ? $Diferencia->s : "0".$Diferencia->s;
		$diferencia = $Horas.":".$Minutos.":".$Segundos;
		return $diferencia;
	}

	    // public function getAgentes($mandante, $cedente, $estrategia = "", $cola = "", $asignacion = "")
	// {
	// 	$db = new DB();

	// 	$sqlCedente = "";

	// 	if ($cedente != ""){
	// 		$sqlCedente = "ro.cartera = '" . $cedente . "'";
	// 	}
	// 	else if ($mandante != ""){
	// 		$sqlCedente = "ro.cartera IN (SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = '" . $mandante . "')";
	// 	}
	// 	else{
	// 		$sqlCedente = "ro.cartera IN (SELECT Id_Cedente FROM mandante_cedente)";
	// 	}

	// 	$whereAnexo = "";
	// 	if ($estrategia != ""){
	// 		$whereAnexo = $this->anexosPredictivoManual($cedente, $estrategia, $cola, $asignacion);

	// 		if(trim($whereAnexo) == ""){
	// 			return array();
	// 		}
	// 	}

	// 	$sqlReporte = "SELECT 
	// 						ro.estatus, 
	// 						count(ro.estatus) AS cantidad, 
	// 						ISNULL(cer.icono, 'fa') AS icono, 
	// 						ISNULL(cer.color, 'white') AS color, 
	// 						ISNULL(cer.hovercolor, 'white') AS hcolor 
	// 					FROM 
	// 						reporteOnLine AS ro 
	// 					LEFT JOIN 
	// 						Colores_Estatus_reporteOnLine AS cer ON (cer.estatus = ro.estatus) 
	// 					WHERE 
	// 					-- " . 
	// 					-- 	$sqlCedente . " 
	// 					-- AND 
	// 						ro.activo = '1' " . $whereAnexo . " 
	// 					GROUP BY 
	// 						ro.estatus, cer.icono, cer.color,cer.hovercolor";

    //     $rows = $dbDiscador->select($sqlReporte);

	// 	return $rows;
	// }

    // public function getPuestos($mandante = "", $cedente = "", $estrategia = "", $cola = "", $asignacion = "")
	// {
	// 	$dbDiscador = new DB('discador');

	// 	$sqlCedente = "";

	// 	if ($cedente != ""){
	// 		$sqlCedente = "ro.cartera = '" . $cedente . "'";
	// 	}
	// 	else if ($mandante != ""){
	// 		$sqlCedente = "ro.cartera IN (SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante='" . $mandante . "')";
	// 	}else{
	// 		$sqlCedente = "ro.cartera IN (SELECT Id_Cedente FROM mandante_cedente)";
	// 	}

	// 	$this->updateTiempoTranscurrido();

	// 	$whereAnexo = "";
	// 	if ($estrategia != ""){
	// 		$whereAnexo = $this->anexosPredictivoManual($cedente, $estrategia, $cola, $asignacion);

	// 		if(trim($whereAnexo) == ""){
	// 			return array();
	// 		}
	// 	}
	// 	$query = "	SELECT 
	// 					ro.anexo, 
	// 					ro.estatus, 
	// 					ro.ejecutivo, 
	// 					ro.tiempo, 
	// 					ro.pausa 
	// 				FROM 
	// 					reporteOnLine AS ro
	// 				WHERE 
	// 				-- 	" . $sqlCedente . " 
	// 				-- AND 
	// 					ro.activo = '1'" . $whereAnexo;
	// 	$rows = $dbDiscador->select($query);
    //     return $rows;
	// }

	// public function getPuestosTrabajo($mandante, $estatus, $cedente, $estrategia = "", $queue = "", $asignacion = "")
	// {
	// 	$dbDiscador = new DB('discador');

	// 	$sqlCedente = "";

	// 	if ($cedente != ""){
	// 		$sqlCedente = "ro.cartera = '" . $cedente . "'";
	// 	}
	// 	else if ($mandante != ""){
	// 		$sqlCedente = "ro.cartera IN (SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = '" . $mandante . "')";
	// 	}else{
	// 		$sqlCedente = "ro.cartera IN (SELECT Id_Cedente FROM mandante_cedente)";
	// 	}

	// 	$whereAnexo = "";
	// 	if ($estrategia != ""){
	// 		$whereAnexo = $this->anexosPredictivoManual($cedente, $estrategia, $cola, $asignacion);

	// 		if(trim($whereAnexo) == ""){
	// 			return array();
	// 		}
	// 	}
	// 	$query = "	SELECT 
	// 					ro.anexo, ro.ejecutivo, 
	// 					ro.tiempo, ro.pausa 
	// 				FROM 
	// 					reporteOnLine AS ro 
	// 				WHERE 
	// 				-- 	" . $sqlCedente . " 
	// 				-- AND 
	// 					ro.activo = '1' 
	// 				AND 
	// 					ro.estatus = '" . $estatus . "'" . $whereAnexo;
	// 	$rows = $dbDiscador->select($query);

    //     return $rows;
	// }

	// private function anexosPredictivoManual($cedente, $estrategia, $cola, $asignacion){
	// 	$whereAnexo = "";
	// 	$arrayAnexo = array();

	// 	$whereEst 	= ($estrategia != "") ? " AND id_estrategia = '" . $estrategia ."' " : "";
	// 	$whereQue 	= ($cola != "") ? " AND id_cola = '" . $cola ."' " : "";

	// 	$selAsig 	= ($asignacion != "") ? ", ac.id " : "";
	// 	$joinAsig 	= ($asignacion != "") ? " INNER JOIN asignacion_cola AS ac ON (ac.id = eca.id_asignacion) " : "";
	// 	$whereAsig 	= ($asignacion != "") ? " AND ac.asignacion = '" . $asignacion ."' " : "";

	// 	$sqlManual = "SELECT 
	// 						u.anexo_foco " . $selAsig . "
	// 					FROM 
	// 						estrategias_cola_asignacion AS eca 
	// 						INNER JOIN Usuarios AS u ON (u.id = eca.id_usuario) 
	// 						" . $joinAsig . "
	// 					WHERE u.anexo_foco <> 0 " . 
	// 						$whereEst . 
	// 						$whereQue . 
	// 						$whereAsig;

	// 	$db = new DB();
	// 	$dbDiscador = new DB("discador");
	// 	$anexos = $db->select($sqlManual);

	// 	if (is_array($anexos)){
	// 		foreach ($anexos as $anexo){
	// 			array_push($arrayAnexo, "'SIP/".$anexo["anexo_foco"]."'");
	// 		}
	// 	}

	// 	$sqlPredictivo = "	SELECT 
	// 							qe.id 
	// 						FROM 
	// 							SIS_Querys_Estrategias AS qe 
	// 							INNER JOIN asignacion_cola AS ac ON (ac.id_cola = qe.id) 
	// 						WHERE 
	// 							qe.id_estrategia = '" . $estrategia ."' 
	// 							AND qe.terminal = 1 
	// 							// AND ac.asignacion LIKE CONCAT ('QR_', '" . $cedente . "_', qe.id, '_%')
	// 						GROUP BY qe.id";

	// 	$colas = $db->select($sqlPredictivo);

	// 	if (count($colas) > 0){
	// 		$arrayColas = array();

	// 		foreach ($colas as $cola){
	// 			array_push($arrayColas, $cola["id"]);
	// 		}

	// 		$colasImplode = implode(",", $arrayColas);
			
	// 		$sqlQueues = "SELECT 
	// 							aq.Queue
	// 						FROM
	// 							asignacion_cola AS ac
	// 								INNER JOIN Asterisk_Discador_Cola AS adc ON adc.Cola = ac.asignacion
	// 								INNER JOIN Asterisk_All_Queues AS aq ON aq.id_discador = adc.id
	// 						WHERE
	// 							ac.id_cola IN (" . $colasImplode . ")";

	// 		$queues = $db->select($sqlQueues);

	// 		if (count($queues) > 0){
	// 			$arrayQueues = array();

	// 			foreach ($queues as $queue){
	// 				array_push($arrayQueues, $queue["Queue"]);
	// 			}

	// 			$queuesImplode = implode(",", $arrayQueues);
	// 			$anexos = $dbDiscador->select("SELECT Agente FROM Asterisk_Agents WHERE Queue IN (" . $queuesImplode . ")");

	// 			if (is_array($anexos)){
	// 				foreach ($anexos as $anexo){
	// 					array_push($arrayAnexo, $anexo["Agente"]);
	// 				}
	// 			}
	// 		}
	// 	}

	// 	if(count($arrayAnexo) > 0){
	// 		$anexoImplode = implode(",", $arrayAnexo);
	// 		$whereAnexo = " AND ro.anexo IN (" . $anexoImplode . ")";
	// 	}

	// 	return $whereAnexo;
	// }

	// public function getEstrategias($cedente = null)
	// {
	// 	$db  = new DB();
	// 	$sql = "";

	// 	if(isset($cedente)){
	// 		$sql = "SELECT id, nombre FROM SIS_Estrategias WHERE Id_Cedente = '" . $cedente . "' AND estado = 0";
	// 	}else{
	// 		$sql = "SELECT id, nombre FROM SIS_Estrategias WHERE estado = 0";
	// 	}

    //     $rows = $db->select($sql);

    //     return $rows;
	// }

	// public function getQueues($estrategia)
	// {
	// 	$db = new DB();

    //     $rows = $db->select("SELECT id, cola FROM SIS_Querys_Estrategias WHERE id_estrategia = '" . $estrategia . "' AND terminal = '1'");

    //     return $rows;
	// }

	// public function getEstrategiasColas($mandante, $cedente, $estrategia, $cola, $asignacion)
	// {
	// 	$cedSql = "";
	// 	$estSql = "";
	// 	$manSql = "";
	// 	$queSql = "";
	// 	$selasig = "";
	// 	$joinasig = "";
	// 	$orderby = "";
	// 	$whereasig = "";
	// 	$response = array();

	// 	if($mandante == ""){
	// 		$orderby = " ORDER BY 1,2,3";
	// 	}else{
	// 		$manSql = " AND mc.Id_Mandante = '" . $mandante . "' ";
	// 		if($cedente == ""){
	// 			$orderby = " ORDER BY 2,3";
	// 		}else{
	// 			$cedSql = " AND es.Id_Cedente = '" . $cedente . "' ";
	// 			if($estrategia == ""){
	// 				$orderby = " ORDER BY 3 ";
	// 			}else{
	// 				$estSql = " AND es.id = '" . $estrategia . "' ";
	// 			}
	// 		}
	// 	}

	// 	$db = new DB();

	// 	if($cola != ""){
	// 		$queSql .= " AND qe.id = '" . $cola . "'";
		
	// 		if($asignacion != ""){
	// 			$asig = explode("_", $asignacion);
	// 			if($asig[3] == "G"){
	// 				$selasig = ", CONCAT('Asignación Grupo ', ag.Nombre) AS nombre";
	// 				$joinasig = " INNER JOIN grupos AS ag ";
	// 				$whereasig = " AND ag.IdGrupo = '" . $asig[4] ."'";
	// 			}else if($asig[3] == "S"){
	// 				$selasig = ", CONCAT('Asignación Supervisor ', ap.Nombre) AS nombre";
	// 				$joinasig = " INNER JOIN Personal AS ap";
	// 				$whereasig = " AND ap.Id_Personal = '" . $asig[4] ."'";
	// 			}else if($asig[3] == "E"){
	// 				$selasig = ", CONCAT('Asignación Ejecutivo ', ap.Nombre) AS nombre";
	// 				$joinasig = " INNER JOIN Personal AS ap ";
	// 				$whereasig = " AND ap.Id_Personal = '" . $asig[4] ."'";
	// 			}else if($asig[3] == "EE"){
	// 				$selasig = ", CONCAT('Asignación Empresa Externa ', aee.Nombre) AS nombre";
	// 				$joinasig = " INNER JOIN empresa_externa AS aee ";
	// 				$whereasig = " AND aee.IdEmpresaExterna = '" . $asig[4] ."'";
	// 			}
	// 		}else{
	// 			$selasig = ", ac.asignacion AS asignacion";
	// 			$joinasig = " INNER JOIN asignacion_cola AS ac ON (ac.id_cola = qe.id) ";
	// 		}

	// 		$rows = $db->select("SELECT 
	// 								m.nombre AS mandante, 
	// 								c.Nombre_Cedente AS cedente, 
	// 								es.nombre AS estrategia, 
	// 								qe.id AS idcola, 
	// 								qe.cola AS cola". 
	// 								$selasig . " 
	// 							FROM 
	// 								SIS_Estrategias AS es 
	// 								INNER JOIN Cedente AS c ON (es.Id_Cedente=c.Id_Cedente) 
	// 								INNER JOIN mandante_cedente AS mc ON (mc.Id_Cedente = c.Id_Cedente) 
	// 								INNER JOIN mandante AS m ON (m.id = mc.Id_Mandante) 
	// 								INNER JOIN SIS_Querys_Estrategias AS qe ON (qe.id_estrategia = es.id) " .
	// 								$joinasig . "
	// 							WHERE 
	// 								m.estatus = 1 
	// 								AND es.estado = 0 
	// 								AND qe.terminal = 1 " .
	// 								$manSql . 
	// 								$cedSql . 
	// 								$estSql .
	// 								$queSql);

	// 		if($asignacion == ""){
	// 			$auxiliar = explode(",", $rows[0]["asignacion"]);

	// 			foreach($auxiliar as $aux){	
	// 				$arreglo = array();

	// 				$asigEncontrado = $this->findAsignacion($aux);
	// 				$arreglo = $this->findQueueData($aux, $rows[0]["idcola"]);
	// 				$ratios = $this->findPenetracionContactabilidad($rows[0]["idcola"]);
	// 				$arreglo["mandante"] = $rows[0]["mandante"];
	// 				$arreglo["cedente"] = $rows[0]["cedente"];
	// 				$arreglo["estrategia"] = $rows[0]["estrategia"];
	// 				$arreglo["idcola"] = $rows[0]["idcola"];
	// 				$arreglo["cola"] = $rows[0]["cola"];
	// 				$arreglo["nombre"] = $asigEncontrado["asignacion"];
	// 				$arreglo["penetracion"] = $ratios["penetracion"];
	// 				$arreglo["contactabilidad"] = $ratios["contactabilidad"];

	// 				array_push($response, $arreglo);
	// 			}
	// 		}else{
	// 			$arreglo = array();
	// 			$arreglo = $this->findQueueData($asignacion, $rows[0]["idcola"]);
	// 			$ratios = $this->findPenetracionContactabilidad($rows[0]["idcola"]);

	// 			$arreglo["mandante"] = $rows[0]["mandante"];
	// 			$arreglo["cedente"] = $rows[0]["cedente"];
	// 			$arreglo["estrategia"] = $rows[0]["estrategia"];
	// 			$arreglo["idcola"] = $rows[0]["idcola"];
	// 			$arreglo["cola"] = $rows[0]["cola"];
	// 			$arreglo["nombre"] = $rows[0]["nombre"];
	// 			$arreglo["penetracion"] = $ratios["penetracion"];
	// 			$arreglo["contactabilidad"] = $ratios["contactabilidad"];

	// 			array_push($response, $arreglo);
	// 		}
	// 	}else{
	// 		$rows = $db->select("SELECT 
	// 								m.nombre AS mandante,
	// 								c.Nombre_Cedente AS cedente,
	// 								es.nombre AS estrategia,
	// 								qe.id AS idcola,
	// 								qe.cola AS cola,
	// 								ac.asignacion AS asignacion
	// 							FROM 
	// 								SIS_Estrategias AS es 
	// 								INNER JOIN Cedente AS c ON (es.Id_Cedente=c.Id_Cedente) 
	// 								INNER JOIN mandante_cedente AS mc ON (mc.Id_Cedente = c.Id_Cedente) 
	// 								INNER JOIN mandante AS m ON (m.id = mc.Id_Mandante) 
	// 								INNER JOIN SIS_Querys_Estrategias AS qe ON (qe.id_estrategia = es.id)
	// 								INNER JOIN asignacion_cola AS ac ON (ac.id_cola = qe.id)
	// 							WHERE 
	// 								m.estatus = 1 
	// 								AND es.estado = 0 
	// 								AND qe.terminal = 1 " .
	// 								$manSql . 
	// 								$cedSql . 
	// 								$estSql . 
	// 							$orderby);

	// 		foreach ($rows as $row){
	// 			$arreglo = array();

	// 			$arreglo["nombre"] = "";
	// 			$arreglo["marcacion"] = "";
	// 			$arreglo["casos"] = 0;
	// 			$arreglo["barridos"] = 0;
	// 			$arreglo["penetracion"] = 0;
	// 			$arreglo["contactabilidad"] = 0;

	// 			$asignacion = $row["asignacion"];

	// 			if($asignacion !== ""){
	// 				$asigEncontrado = $this->findAsignacion($asignacion);
	// 				$arreglo = $this->findQueueData($asignacion, $row["idcola"]);
	// 				$ratios = $this->findPenetracionContactabilidad($row["idcola"]);

	// 				$arreglo["nombre"] 		= $asigEncontrado["asignacion"];
	// 				$arreglo["mandante"] 	= $row["mandante"];
	// 				$arreglo["cedente"] 	= $row["cedente"];
	// 				$arreglo["estrategia"] 	= $row["estrategia"];
	// 				$arreglo["idcola"] 		= $row["idcola"];
	// 				$arreglo["cola"] 		= $row["cola"];
	// 				$arreglo["penetracion"] = $ratios["penetracion"];
	// 				$arreglo["contactabilidad"] = $ratios["contactabilidad"];


	// 				array_push($response, $arreglo);
	// 			}else{
	// 				$arreglo["mandante"] = $row["mandante"];
	// 				$arreglo["cedente"] = $row["cedente"];
	// 				$arreglo["estrategia"] = $row["estrategia"];
	// 				$arreglo["idcola"] = $row["idcola"];
	// 				$arreglo["cola"] = $row["cola"];

	// 				array_push($response, $arreglo);
	// 			}
	// 		}
	// 	}

    //     return $response;
	// }

	// private function findQueueData ($queue, $idcola){
	// 	$db = new DB();
	// 	$dbDiscador = new DB("discador");
	// 	$existe = $db->select("SELECT 
	// 								dc.Cola, aq.Queue 
	// 							FROM 
	// 								Asterisk_Discador_Cola AS dc
	// 								INNER JOIN Asterisk_All_Queues AS aq ON (aq.id_discador = dc.id) 
	// 							WHERE dc.Cola = '" . trim($queue) . "'");

	// 	if(count($existe) > 0){
	// 		$arreglo["marcacion"] = "Predictivo";
	// 		$colaPredictivo = "DR_" . trim($existe[0]["Queue"]) . "_" . trim($queue);

	// 		$casos = $dbDiscador->select("SELECT count(*) AS casos FROM " . $colaPredictivo);

	// 		if($casos){
	// 			$arreglo["casos"] = $casos[0]["casos"];
	// 		}else{
	// 			$arreglo["casos"] = 0;
	// 		}

	// 		$casosBarridos = $dbDiscador->select("SELECT count(id) AS casosBarridos FROM " . $colaPredictivo . " WHERE llamado <> 0");

	// 		$barridos = $casosBarridos[0]["casosBarridos"];

	// 		if($barridos > 0){
	// 			$arreglo["barridos"] = $barridos;
	// 		}else{
	// 			$arreglo["barridos"] = 0;
	// 		}
	// 	}else{
	// 		$arreglo["marcacion"] = "Manual";

	// 		$casos = $db->select("SELECT count(id) AS casos FROM " . trim($queue));

	// 		if($casos){
	// 			$arreglo["casos"] = $casos[0]["casos"];
	// 		}else{
	// 			$arreglo["casos"] = 0;
	// 		}

	// 		$casosBarridos = $db->select("SELECT count(id) AS casosBarridos FROM " . trim($queue) . " WHERE estado <> 0");

	// 		$barridos = $casosBarridos[0]["casosBarridos"];

	// 		if($barridos > 0){
	// 			$arreglo["barridos"] = $barridos;
	// 		}else{
	// 			$arreglo["barridos"] = 0;
	// 		}
	// 	}
	// 	return $arreglo;
	// }

	// public function getAsignacionColas($cola)
	// {
	// 	$asignaciones = "";
	// 	$response = array();
	// 	$asg = array();

	// 	if($cola != ""){
	// 		$db = new DB();

	// 		$sql = "SELECT
	// 					TABLE_NAME
	// 				FROM
	// 					INFORMATION_SCHEMA.TABLES
	// 				WHERE
	// 					LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 2
	// 				AND TABLE_NAME LIKE 'QR_%'
	// 				AND SUBSTRING_INDEX(TABLE_NAME, '_' ,- 1) = '".$cola."'";

	// 		$getCola = $db->select($sql);

	// 		if ($getCola[0]["TABLE_NAME"] != ""){
	// 			$sql2 = "SELECT 
	// 							TABLE_NAME AS tabla 
	// 						FROM 
	// 							INFORMATION_SCHEMA.TABLES 
	// 						WHERE 
	// 							TABLE_CATALOG='foco' 
	// 							AND (LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', ''))) = 7 
	// 							AND TABLE_NAME LIKE '" . $getCola[0]["TABLE_NAME"] . "%'";
	// 			$rows = $db->select($sql2);

	// 			if(count($rows) > 0){
	// 				foreach($rows as $row){
	// 					$asg = $this->findAsignacion($row["tabla"]);
	// 					array_push($response, $asg);
	// 				}
	// 			}
	// 		}
	// 	}

    //     return $response;
	// }

	// private function findPenetracionContactabilidad($cola){
	// 	$db = new DB();

	// 	$arreglo = array();
	// 	$arreglo['penetracion'] = '';
	// 	$arreglo['contactabilidad'] = '';

	// 	$sql = "SELECT porcentaje, id_ratio FROM ratios_cola WHERE id_cola = '" . $cola . "'";

	// 	$porcentajes = $db->select($sql);

	// 	if($porcentajes){
	// 		foreach($porcentajes as $porcentaje){
	// 			$ratio = $porcentaje["id_ratio"];

	// 			if($ratio == "1"){
	// 				$arreglo['penetracion'] = $porcentaje["porcentaje"];
	// 			}else{
	// 				$arreglo['contactabilidad'] = $porcentaje["porcentaje"];
	// 			}
	// 		}
	// 	}

	// 	return $arreglo;
	// }
}
?>