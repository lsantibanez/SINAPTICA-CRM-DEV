<?php
 
class crm
{
	function __construct(){
		if(!isset($_SESSION)){
			session_start();
		}
	}
	public function mostrarRut($prefijo,$estrategia,$ordenViejo,$tipo){
		$db = new DB();
		$id_usuario = $_SESSION['id_usuario'];
		$Id_Cedente = $_SESSION["cedente"];
		$ToReturn = '';
		if($prefijo){
			if($tipo != 3){
				$resu = '';
				$query = "UPDATE ".$prefijo." SET id_usuario = 0 WHERE id_usuario = '".$id_usuario."'";
				$db->query($query);

				$SqlCantidadRut = "SELECT count(*) as cantidad FROM ".$prefijo."";
				$cant = $db->select($SqlCantidadRut);
				$cantidadRut  = $cant[0]["cantidad"];
				if($cantidadRut > 0){

					$Cedente = $db->select("SELECT * FROM Cedente WHERE Id_Cedente = '".$Id_Cedente."'");
					$Cedente = $Cedente[0];
					if($Cedente['algoritmo_discado'] == 1){
						$EncontroRut = false;
						$cantidadIteraciones = 0;
						$orden = $ordenViejo;
						while(($EncontroRut == false) && ($cantidadIteraciones < $cantidadRut)){
							if($tipo == 1){
								$orden = $orden + 1;	
								if ($orden > $cantidadRut){	
									$orden = 1;
								}
							}else{
								$orden = $orden - 1;
								if ($orden == 0){
									$orden = $cantidadRut;
								}
							}
									
							/*$SqlRut = "	SELECT 
											Rut, estado_cola, estado
										FROM 
											".$prefijo." 
										WHERE 
											orden = '".$orden."'
										AND
											orden != '".$ordenViejo."'
										AND 
											llamado = 0 
										AND 
											(id_usuario = 0 OR id_usuario = '".$id_usuario."')
										AND
											Rut NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 1 AND Id_Cedente = '".$Id_Cedente."' AND Fecha_Term >= CURDATE())";*/

											$SqlRut = "	SELECT 
											Rut, estado_cola, estado
										FROM 
											".$prefijo." 
										WHERE 
											orden = '".$orden."'
										AND
											orden != '".$ordenViejo."'
										
										AND
											Rut NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 1 AND Id_Cedente = '".$Id_Cedente."' AND Fecha_Term >= CURDATE())";				
							$resu = $db->select($SqlRut);
							
							if ($resu){
								$EncontroRut = true;
								$query = "UPDATE ".$prefijo." SET id_usuario = '".$id_usuario."' WHERE orden = '".$orden."'";
								$db->query($query);
								break;
							}
							$cantidadIteraciones++;
						}

						if(!$EncontroRut){
							$query = "UPDATE ".$prefijo." SET llamado = '0'";
							$db->query($query);
							$this->mostrarRut($rut,$prefijo,$estrategia,$ordenViejo,$tipo);
							exit();
						}
						if($estrategia){
							$this->insertEstrategiaColaAsignacion($prefijo, $estrategia);
						}
					}else{
						$query = "	SELECT 
										Rut, orden, estado_cola, estado
									FROM 
										".$prefijo."
									WHERE 
										(fechaRellamar <= NOW() OR fechaRellamar = '' OR fechaRellamar IS NULL OR fechaRellamar = '0000-00-00 00:00:00')
									AND 
										(id_usuario = 0 OR id_usuario = '".$id_usuario."')
									AND
										Rut NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 1 AND Id_Cedente = '".$Id_Cedente."' AND Fecha_Term >= CURDATE())
									ORDER BY
										estado_cola ASC,
										orden ASC";
						$resu = $db->select($query);
						if ($resu){
							$orden = $resu[0]["orden"];
							$query = "UPDATE ".$prefijo." SET id_usuario = '".$id_usuario."' WHERE orden = '".$orden."'";
							$db->query($query);
						}
					}
				}
			
				if($resu){
					$rut = $resu[0]["Rut"];
					$estado_cola = $resu[0]["estado_cola"];
					$estado = $resu[0]["estado"];
					$siete = $orden." de ".$cantidadRut." Rut";
					// $query = "SELECT Nombre_Completo FROM Persona WHERE Rut = '".$rut."' LIMIT 1";
					// $Persona = $db->select($query);
					// if($Persona){
					// 	$nombre = utf8_encode($Persona[0]["Nombre_Completo"]);
					// }else{
						$nombre = '';
					// }
				}else{
					$rut = '';
					$estado_cola = '3';
					$estado = '0';
					$orden = 0;
					$siete = "0 de 0 Rut";
					$nombre = '';
				}
				if($prefijo){
					$seis = $this->getProgressAsignacion($prefijo);	
				}else{
					$seis = 0;
				}
				$uno =  "<input id='next_rut' type='text' value='$rut' class='form-control' readonly='readonly'>";
				$cinco= "Rut : ".$rut;
				$ToReturn = array('uno' => $uno, 'dos' => $rut, 'tres' => $nombre, 'cuatro' => $prefijo, 'cinco' => $cinco, 'seis' => $seis, 'siete' => $siete, 'ocho' => $orden, 'estado_cola' => $estado_cola, 'estado' => $estado);
			}else{
				$query = "	SELECT
								Rut,
								Id_Cedente 
							FROM
								Persona 
							WHERE
								Rut = '".$prefijo."' 
								AND FIND_IN_SET( '".$Id_Cedente."', Id_Cedente ) 
								AND Rut NOT IN ( SELECT Dato FROM Exclusiones WHERE Tipo = 1 AND Id_Cedente = '".$Id_Cedente."' AND Fecha_Term >= CURDATE() )";
				$rowsCedente = $db->select($query);
				if($rowsCedente){
					$ToReturn = 1;
				}else{
					$ToReturn = 0;
				}
			}
		}
		
		echo json_encode($ToReturn);
	}
	public function Pantalla($id,$rut,$cedente,$fono,$usuario)
	{
		$this->id=$id;
		$this->rut=$rut;
		$this->cedente=$cedente;
		$this->fono=$fono;
		$this->usuario=$usuario;
		session_start();
		$_SESSION['id_dial'] = $this->id;
		$_SESSION['MM_UserGroup'] = '4';
		$_SESSION['rut_dial'] = $this->rut;
		$_SESSION['cedente_dial'] = $this->cedente;
		$_SESSION['fono_dial'] = $this->fono;
		$_SESSION['MM_Username'] = $this->usuario;
		header('Location: index.php');

		session_start();

	}

 public function mostrarEstrategia($id)
{
	$db = new DB();
	$this->id=$id;
	$rows = $db->select("SELECT id,nombre FROM SIS_Estrategias WHERE Id_Cedente = $this->id AND estado = 0");
	echo "<select id='seleccione_estrategia' class='selectpicker' name='seleccione_estrategia' data-live-search='true' data-width='100%'>";
	echo "<option value='0'>Seleccione</option>";
	if($rows){
		foreach($rows as $row)
		{
			echo "<option value='".$row["id"]."'>".utf8_encode($row["nombre"])."</option>";

		}
	}
	echo "</select>";
}


 public function GuardarConvenio()
{
	$db = new DB();
	$montoConvenio = $_POST['montoConvenio'];
	$DescuentoConvenio = $_POST['DescuentoConvenio'];
	$CalculoConvenio = $_POST['CalculoConvenio'];
	$diasConvenio = $_POST['diasConvenio'];
	$hoyConvenio = $_POST['hoyConvenio'];
	$vencimientoConvenio = $_POST['vencimientoConvenio'];
	$cuotasConvenio = $_POST['cuotasConvenio'];
	$ValorCuotas = $_POST['ValorCuotas'];

	
	$sqlCreaRegistro = "INSERT INTO `foco`.`PE_CRM_convenio` (`rut`, `operacion`, `factura`, `descuento`, `dia_pago`, `cuotas`, `valor`) VALUES ('0', NULL, NULL, $DescuentoConvenio, $diasConvenio, $cuotasConvenio, $CalculoConvenio);";
	//var_dump($sqlCreaRegistro);
	$last = $db->insert($sqlCreaRegistro);

	for ($i=0; $cuotasConvenio > $i ; $i++) { 
		$iteracion = $i+1;
		$fecha = DateTime::createFromFormat('d/m/Y', $hoyConvenio);
		$diasAdicionales = $diasConvenio * $iteracion;
		$fecha->modify('+'.$diasAdicionales.' day');
		$vencimiento = $fecha->format('Y-m-d');
		$sqlCreaRegistro = "INSERT INTO `foco`.`PE_CRM_convenio_detalle` (`id_convenio`, `cuota`, `valor`, `fecha_vencimiento`) VALUES ($last, $iteracion, $ValorCuotas, '$vencimiento');";
		$db->query($sqlCreaRegistro);

		# code...
	}
	$sql = "SELECT * FROM PE_CRM_convenio_detalle WHERE id_convenio = $last";
	$resu = $db->select($sql);
	$ToReturn = array();
			foreach ($resu as $key => $row) {
				$ToReturn[$key] = $row;
			}
	return $ToReturn;
}

	public function excelConvenio($last){
		$db = new DB();
		$sql = "SELECT cuota, fecha_vencimiento, valor FROM PE_CRM_convenio_detalle WHERE id_convenio = $last";
		$resu = $db->select($sql);
		$ToReturn = array();
			foreach ($resu as $key => $row) {
				$ToReturn[$key] = $row;
			}
	return $ToReturn;
	}

	public function mostrarColaDiscador() //DISCADOR
	{
		$db = new DB();
		$sql = "SELECT 
					Asterisk_Discador_Cola.id, 
					Asterisk_Discador_Cola.Cola, 
					Asterisk_All_Queues.Queue 
				FROM 
					Asterisk_Discador_Cola
				INNER JOIN 
					Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id 
				WHERE 
					Status = 1 
				AND 
					Id_Cedente = '".$_SESSION['cedente']."' 
				GROUP BY 
					Asterisk_All_Queues.Queue,
					Asterisk_Discador_Cola.id, 
					Asterisk_Discador_Cola.Cola";
		$resu = $db->select($sql);

		echo "<select id='seleccione_tipo_busqueda' class='selectpicker' name='seleccione_tipo_busqueda' data-live-search='true' data-width='100%'>";
		echo "<option value='0'>Seleccione</option>";
		if($resu){
			foreach($resu as $fila){
				$idCola = $fila["id"];
				$cola = $fila["Cola"];
				$array = explode('_',$cola);
				$tipo = $array[3];
				$id = $array[4];
				$Queue = $fila["Queue"];


				switch ($tipo){
					case 'G':
						$sql2 = "SELECT Nombre FROM grupos WHERE IdGrupo = '".$id."'";
						$resu2 = $db->select($sql2);
						$nombre = $resu2[0]["Nombre"];
					break;
					case 'E':
					case 'S':
						$sql3 ="SELECT Nombre FROM Personal WHERE Id_Personal = '".$id."'";
						$resu3 = $db->select($sql3);
						$nombre = $resu3[0]["Nombre"];
					break;
				}

				echo "<option value='".$idCola."'>".$nombre." - ".$Queue."</option>";
			}
		}
		echo "</select>";

    }

	public function unPausePredictivo(){
		$dbDiscador = new DB("discador");
		$DiscadorClass = new Discador();
		$Anexo = "SIP/".$_SESSION['anexo_foco'];
		$row2 = $dbDiscador->select("SELECT Queue FROM Asterisk_Agentes WHERE Agente = '$Anexo' LIMIT 1");
		$queues = $row2[0]["Queue"];
		$DiscadorClass->UnPause_Predictivo($queues,$Anexo);
		//shell_exec("php /var/www/html/produccion/discador/AGI/Unpause.php '$queues' '$Anexo'");
	}

	public function predictivoRut($datos){
		$datos = str_replace("Id=","",$datos["Datos"]);
		$datos = explode("&",$datos);

		$Fono = $datos[1];
		$Queue = $datos[2];
		$rut = $datos[3];
		$Cedente = $datos[4];
		$CodigoFoco = $datos[5];
		$IpDiscador = $datos[6];
		$Usuario = $datos[9];

		if(strlen($Cedente) > 2){
			$Cedente = $Cedente;
		}else{
			if(strlen($Cedente) == 2){
				$Cedente = "0".$Cedente;
			}else{
				if(strlen($Cedente) == 1){
					$Cedente = "00".$Cedente;
				}
			}
		}
		$dbDiscador = new DB("discador");
		$query = "SELECT Nombre_Completo FROM ".$Queue."_Persona WHERE Rut = '".$rut."' LIMIT 1";
		$Persona = $dbDiscador->select($query);
		if($Persona){
			$nombre_cliente = $Persona[0]["Nombre_Completo"];
		}else{
			$nombre_cliente = '';
		}

		// $Anio = date("Y");
		// $Mes = date("m");
		// $Dia = date("d");
		// $Hora = date("H");
		// $Minuto = date("i");
		// $Segundo = date("s");
		// $NombreGrabacion = $Anio.$Mes.$Dia."-".$Hora.$Minuto.$Segundo."_".$Fono."_".$Cedente."_".$Usuario;
		// $nomArchivo = $NombreGrabacion."-all";
		// $UrlGrabacion = "http://".$IpDiscador."/Records/".$CodigoFoco."/".$Cedente."/".$Anio.$Mes.$Dia."/".$Usuario."/".$nomArchivo.".wav";
		$this->pausaPredictivo();
		// $array = array('uno' => $rut, 'dos' => $Fono, 'Queue' => $Queue, 'Nombre_Grabacion' => $NombreGrabacion, 'UrlGrabacion' => $UrlGrabacion);
		$array = array('uno' => $rut, 'dos' => $Fono, 'Queue' => $Queue, 'nombre_cliente' => $nombre_cliente);
		echo json_encode($array);
	}

	public function insertarDatosCola($idCola){
		$dbDiscador = new DB("discador");
		$DiscadorClass = new Discador();
		if($_SESSION['anexo_foco'] == 0){
			echo "1"; // el usuario no tiene anexo
		}else{
			$row = $dbDiscador->select("SELECT Queue FROM Asterisk_All_Queues WHERE id_discador = $idCola");
			$queues = $row[0]["Queue"];
			$Anexo = "SIP/".$_SESSION['anexo_foco'];
			
			$DiscadorClass->entrarCola($queues,$Anexo);
			//shell_exec("php /var/www/html/produccion/discador/AGI/EntrarCola.php '$Anexo' '$queues'");

			$ValidarAnexo = $dbDiscador->select("SELECT * FROM Asterisk_Agentes WHERE Agente = '$Anexo'");
			if(count($ValidarAnexo)>0){
				$dbDiscador->query("UPDATE Asterisk_Agentes SET Agente = '$Anexo',Queue = '$queues' WHERE Agente = '$Anexo' ");
			}
			else{
				$dbDiscador->query("INSERT INTO Asterisk_Agentes(Agente,Queue) VALUES ('$Anexo','$queues')");
			}
			echo "2";
		}

	}

	public function eliminarAnexo($idCola){
		$db = new DB("discador");
		$DiscadorClass = new Discador();
		$anexo = "SIP/".$_SESSION['anexo_foco'];

		$sql = $db->query("DELETE FROM Asterisk_Agentes WHERE Agente = '$anexo' ");

		$row = $db->select("SELECT Queue FROM Asterisk_All_Queues WHERE id_discador = $idCola");
		$queues = $row[0]["Queue"];
		$DiscadorClass->salirCola($queues,$anexo);
		//shell_exec("php /var/www/html/produccion/discador/AGI/SalirCola.php '$anexo' '$queues'");


	}

    public function mostrarCola($id)
	{
		$db = new DB();
		$this->id=$id;
        $rows = $db->select("SELECT id,cola FROM SIS_Querys_Estrategias WHERE id_estrategia = $this->id  AND terminal = 1 AND discador=1");
        echo "<select id='seleccione_cola' class='selectpicker' name='seleccione_cola' data-live-search='true' data-width='100%'>";
		echo "<option value='0'>Seleccione</option>";
		if($rows){
			foreach($rows as $row)
			{
				echo "<option value='".$row["id"]."'>".$row["cola"]."</option>";

			}
		}
        echo "</select>";
    }
	function getProgressAsignacion($Asignacion){
		$db = new DB();
		$ToReturn = "";
		$SqlTotal = "	SELECT 
							count(*) as Total
						FROM
							".$Asignacion;
		$Total = $db->select($SqlTotal);
		$Total = count($Total) > 0 ? $Total[0]["Total"] : 0;
		$SqlGestionado = "	SELECT 
								count(*) as Gestionado
							FROM
								".$Asignacion."
							WHERE
								estado <> '0'";
		$Gestionado = $db->select($SqlGestionado);
		$Gestionado = count($Gestionado) > 0 ? $Gestionado[0]["Gestionado"] : 0;
		$ToReturn = $Gestionado == 0 ? 0 : ($Gestionado * 100) / $Total;
		$ToReturn = round($ToReturn);
		return $ToReturn;
	}
	public function mostrarNombreCliente($rut){
		$db = new DB();
		$query = "SELECT Nombre_Completo FROM Persona WHERE Rut = '".$rut."' LIMIT 1";
		$Persona = $db->select($query);
		if($Persona){
			
			$query = "SHOW COLUMNS FROM Deuda LIKE 'Carrera'";
			$Educational = $db->select($query);
			if(!$Educational){
				$Nombre = $Persona[0]["Nombre_Completo"];
			}else{
				$query = "SELECT Carrera, Ano_ingreso FROM Deuda WHERE Rut = '.$rut.'";
				$Carrera = $db->select($query);
				if($Carrera){
					$Nombre = 'Nombre Alumno: ' . $Persona[0]["Nombre_Completo"] . '<br>';
					$Nombre .= 'Carrera: ' . $Carrera[0]["Carrera"] . '<br>';
					$Nombre .= 'Año Ingreso: ' . $Carrera[0]["Ano_ingreso"] . '<br>';
				}else{
					$Nombre = $Persona[0]["Nombre_Completo"];
				}
			}
		}else{
			$Nombre = '';
		}
		echo $Nombre;
	}
	public function mostrarNombreRut($rut,$Queue){
		$dbDiscador = new DB("discador");
		$qn = $dbDiscador->select("SELECT Nombre_Completo FROM ".$Queue."_Persona WHERE Rut = $rut LIMIT 1");
		if($qn){
			echo $qn[0]["Nombre_Completo"];
		}else{
			echo '';
		}
	}
	public function cantRegistros($rut,$prefijo)
	{
		if($prefijo != '1'){
			$db = new DB();
			$this->rut = $rut;
			$this->prefijo = $prefijo;
			$query = "SELECT Rut FROM $this->prefijo";
			$qn = $db->select($query);
			$num = count($qn);
			$query = "SELECT id FROM $this->prefijo WHERE Rut = '".$this->rut."'";
			$rows = $db->select($query);
			if($rows){
				foreach($rows as $row){
					$id = $row["id"];
				}
			}else{
				$id = 0;
			}
	        $valor = $id." de ".$num;
	        echo "<input type='text' value='".$valor."' disabled='disabled'  class='form-control'>";
        }
	}
	public function marcarFactura($rut,$cedente,$id_deuda,$id)
	{
		$this->rut=$rut;
		$this->cedente=$cedente;
		$this->id_deuda=$id_deuda;
		$this->id=$id;
		if($this->id ==1)
		{

			session_start();
			$_SESSION['mfacturas'][] = $this->id_deuda;
			$mfacturas = $_SESSION['mfacturas'];
			echo "Factura Adjunta".print_r($mfacturas);
			session_start();
		}
		else
		{
			session_start();
			$clavem = array_search($this->id_deuda, $_SESSION['mfacturas']);
			unset($_SESSION['mfacturas'][$clavem]);
			echo "Factura Removida".$clavem;
			session_start();
		}


	}
	public function marcarMail($id_mail,$id)
	{
		$this->id_mail=$id_mail;
		$this->id=$id;
		if($this->id ==1)
		{

				session_start();
				$_SESSION['correos'][] = $this->id_mail;
				$correos = $_SESSION['correos'];
				echo "Email Activado".print_r($correos);
				session_start();
		}
		else
		{
				session_start();
				$clave = array_search($this->id_mail, $_SESSION['correos']);
				unset($_SESSION['correos'][$clave]);
				echo "Email Desactivado".$clave;
				session_start();
		}
	}

	public function marcarMailcc($id_mail,$id)
	{
		$this->id_mail=$id_mail;
		$this->id=$id;
		if($this->id ==1)
		{

				session_start();
				$_SESSION['correos_cc'][] = $this->id_mail;
				$correos_cc = $_SESSION['correos_cc'];
				echo "Email Activado".print_r($correos_cc);
				session_start();
		}
		else
		{
				session_start();
				$clave_cc = array_search($this->id_mail, $_SESSION['correos_cc']);
				unset($_SESSION['correos_cc'][$clave_cc]);
				echo "Email Desactivado".$clave_cc;
				session_start();
		}
	}

	public function actualizarCorreo($id_mail,$mail,$nombre,$cargo,$obs){
		$db = new DB();
		$this->id_mail=$id_mail;
		$this->mail=$mail;
		$this->nombre=$nombre;
		$this->cargo=$cargo;
		$this->obs=$obs;

		$q = "UPDATE Mail SET correo_electronico='$this->mail',Nombre='$this->nombre',Cargo='$this->cargo',Observacion ='$this->obs'  WHERE id_mail = $this->id_mail";
		$db->query($q);


	}
	public function actualizarDireccion($id_direccion,$direccion,$comuna){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		$SqlUpdate = "UPDATE Direcciones SET Direccion='".$direccion."', Comuna='".$comuna."' WHERE id_direccion = '".$id_direccion."'";
		$Update = $db->query($SqlUpdate);
		if($Update){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}

	public function actualizarTelefono($id_telefono, $telefono, $nombre, $cargo, $observacion){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		$SqlUpdate = "UPDATE fono_cob SET formato_subtel='".$telefono."', Nombre='".$nombre."', Cargo='".$cargo."', Observacion='".$observacion."' WHERE id_fono = '".$id_telefono."'";
		$Update = $db->query($SqlUpdate);
		if($Update){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}

	public function enviarMail($cedente,$rut)
	{
		session_start();
		$mailArray = $_SESSION['correos'];
		$mailArraycc = $_SESSION['correos_cc'];
		$facturaArray = $_SESSION['mfacturas'];
		$contarf = count($facturaArray);
		$contarm = count($mailArray);
		if($contarm == 0)
		{
			echo 2;
		}
		else if($contarf == 0)
		{
			echo 3;
		}
		else
		{


			$this->cedente=$cedente;
			$this->rut=$rut;
			if($this->cedente == 48)
			{
				$template = new Template();
				$template->Claro($this->rut,$this->cedente,$mailArray,$facturaArray,$mailArraycc);
			}
			else
			{
				echo 1;
			}
			session_start();
		}

	}
	//MOSTRAR GESTIONES CON CONTACTO
	public function mostrarGestionRut($rut)
	{
		$db 		= new DB();
		$this->rut 	= $rut;
		$response 	= array();

		$q = $db->select("SELECT 
								rut_cliente 
							FROM 
								gestion_ult_trimestre 
							WHERE 
								rut_cliente = '" . $this->rut ."' 
								AND Id_TipoGestion IN (1 ,2 ,5)");

		if(count($q) > 0)
		{
			$query1 = $db->select("SELECT 
										id_gestion, rut_cliente, fecha_gestion, resultado, fono_discado, 
										nombre_ejecutivo, cedente, fec_compromiso, monto_comp, Id_TipoGestion, 
										origen, resultado, resultado_n2, resultado_n3, observacion,n1,n2,n3 
									FROM 
										gestion_ult_trimestre 
									WHERE 
										rut_cliente = '" . $this->rut ."' 
										AND Id_TipoGestion IN (1 ,2 ,5) 
									ORDER BY fecha_gestion DESC 
									LIMIT 20");
			if($query1){
				foreach($query1 as $q1)
				{
					$v1 	= $q1["rut_cliente"];
					$v2 	= $q1["fecha_gestion"];
					$v3 	= $q1["resultado"];
					$v4 	= $q1["fono_discado"];
					$v5 	= $q1["nombre_ejecutivo"];
					$v6 	= $q1["cedente"];
					$v7 	= $q1["fec_compromiso"];
					$v8 	= $q1["monto_comp"];
					$v9 	= $q1["Id_TipoGestion"];
					$v10	= $q1["observacion"];
					$origen = $q1["origen"];
					/* $r1 	= $q1["resultado"];
					$r2 	= $q1["resultado_n2"];
					$r3 	= $q1["resultado_n3"]; */
					$r1 	= $q1["n1"];
					$r2 	= $q1["n2"];
					$r3 	= $q1["n3"];
					
					$idGestion = $q1["id_gestion"];

					$idCanales = $db->select("SELECT canales FROM omnicanalidad WHERE rut = '$this->rut'");
					$canales = "";
					if (count($idCanales)){
						$nombresCanales = $db->select("SELECT canal FROM canales_omnicanalidad WHERE id IN (".$idCanales[0]['canales'].")");
						$canales = utf8_encode(implode(', ', array_column($nombresCanales, 'canal')));
					}

					$res1 = "";
					$res2 = "";
					$res3 = "";

					if($origen==1){
						if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
							$v7 = '---';
							$v8 = '---';
						}else{
							$v7 = $v7;
							$v8 = $v8;
						}

						$query5 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = $r1");
						foreach($query5 as $q5)
						{
							$res1 = $q5["Gestion_Nivel_1"];
						}
						$res2 = "---";
						$res3 = "---";
					}else{
						if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
							$v7 = '---';
							$v8 = '---';
						}else{
							$v7 = $v7;
							$v8 = $v8;
						}

						/*$query3 = $db->select("SELECT Respuesta_N1 FROM  Nivel1 WHERE id = $r1");
						foreach($query3 as $q3){
							$res1 = utf8_encode($q3["Respuesta_N1"]);
						}
						$query5 = $db->select("SELECT Respuesta_N2 FROM  Nivel2 WHERE id = $r2");
						foreach($query5 as $q5){
							$res2 = utf8_encode($q5["Respuesta_N2"]);
						}
						$query6 = $db->select("SELECT Respuesta_N3 FROM  Nivel3 WHERE id = $r3");
						foreach($query6 as $q6){
							$res3 = utf8_encode($q6["Respuesta_N3"]);
						}*/
						$res1 = utf8_encode($r1);
						$res2 = utf8_encode($r2);
						$res3 = utf8_encode($r3);
					}

					$arreglo = array();
					
					$arreglo['fecha_gestion'] 	= $v2;
					$arreglo['ejecutivo'] 		= $v5;
					$arreglo['fono_discado'] 	= $v4;
					$arreglo['n1'] 				= $res1;
					$arreglo['n2'] 				= $res2;
					$arreglo['n3'] 				= $res3;
					$arreglo['compromiso'] 		= $v7;
					$arreglo['monto'] 			= $v8;
					$arreglo['observacion'] 	= $v10;
					$arreglo['canales'] 		= $canales;
					$arreglo['gestion'] 		= $idGestion;

					array_push($response, $arreglo);
				}
			}
		}
		return $response;
	}

	public function mostrarGestionCorreo($rut)
	{
		$db 		= new DB();
		$response 	= array();
		$query = "	SELECT 
						fecha_gestion, hora_gestion, correos, facturas, nombre_ejecutivo, estado
					FROM 
						gestion_correo 
					WHERE 
						rut_cliente = '".$rut."' 
					AND
						(estado != 4 && estado != 5)
					ORDER BY 
						fecha_gestion 
					DESC
					LIMIT 20";
		$correos = $db->select($query);
		if($correos){
			foreach($correos as $correo){
				$fecha_gestion = new DateTime($correo["fecha_gestion"]);
				$hora_gestion = new DateTime($correo["hora_gestion"]);
				$fecha_gestion = $fecha_gestion->format("d-m-Y");
				$hora_gestion = $hora_gestion->format("H:i:s");
				$estado = $correo["estado"];

				if($estado == '1'){
					$estado = 'RECIBIDO';
				}else if($estado == '2'){
					$estado = 'REBOTADO';
				}else{
					$estado = 'ABIERTO';
				}

				$arreglo = array();
				$arreglo['fecha_gestion'] 	= $fecha_gestion;
				$arreglo['hora_gestion'] 	= $hora_gestion;
				$arreglo['ejecutivo'] 		= $correo["nombre_ejecutivo"];
				$arreglo['correo'] 			= $correo["correos"];
				$arreglo['factura'] 		= $correo["facturas"];
				$arreglo['estado'] 			= $estado;

				array_push($response, $arreglo);
			}
		}

		return $response;
	}

	public function mostrarGestionSMS($rut)
	{
		$db 		= new DB();
		$this->rut 	= $rut;
		$response 	= array();
		$query = "	SELECT 
						e.fechaHora, d.fono, d.estado, u.nombre as ejecutivo
					FROM 
						detalle_envio_sms d 
					INNER JOIN 
						envio_sms e 
					ON 
						d.id_envio_sms = e.id
					LEFT JOIN 
						Usuarios u 
					ON 
						e.id_usuario = u.id
					WHERE 
						d.rut = '" . $this->rut . "' 
					ORDER BY 
						e.fechaHora DESC
					LIMIT 20";
		$mensajes = $db->select($query);
		if($mensajes){
			foreach($mensajes as $sms)
			{
				$arreglo = array();
				$dt = new DateTime($sms["fechaHora"]);
				$fecha_gestion = $dt->format("d-m-Y");
				$hora_gestion = $dt->format("H:i:s");
				if($sms["estado"] != '0'){
					$estado = $sms["estado"];
				}else{
					$estado = "EN PROCESO DE ENVIO";
				}

				$arreglo['fecha_gestion'] 	= $fecha_gestion;
				$arreglo['hora_gestion'] 	= $hora_gestion;
				$arreglo['ejecutivo'] 		= $sms["ejecutivo"];
				$arreglo['fono'] 			= $sms["fono"];
				$arreglo['estado'] 			= $estado;

				array_push($response, $arreglo);
			}
		}

		return $response;
	}

	public function mostrarGestionIvr($rut)
	{
		$db 		= new DB();
		$response 	= array();
		$query = "	SELECT 
						fecha, hora, Fono, duracion, estado
					FROM 
						gestion_ivr 
					WHERE 
						Rut = '".$rut."' 
					ORDER BY 
						fecha 
					DESC
					LIMIT 20";
		$ivrs = $db->select($query);
		if($ivrs){
			foreach($ivrs as $ivr){
				$estado = $ivr["estado"];

				if($estado == '1'){
					$estado = 'IVR CONTESTADO';
				}else if($estado == '2'){
					$estado = 'POSIBLE BUZON DE VOZ';
				}else if($estado == '0'){
					$estado = 'IVR NO CONTESTADO';
				}else{
					$estado = 'PENDIENTE';
				}

				$arreglo = array();
				$arreglo['fecha_gestion'] 	= $ivr["fecha"];
				$arreglo['hora_gestion'] 	= $ivr["hora"];
				$arreglo['fono'] 			= $ivr["Fono"];
				$arreglo['duracion'] 		= $ivr["duracion"];
				$arreglo['estado'] 			= $estado;

				array_push($response, $arreglo);
			}
		}

		return $response;
	}

	public function mostrarGestionesExternas($rut){
		$ToReturn = array();
		$ToReturn["Gestiones"] = array();
		$ToReturn["Columnas"] = array();
		$db = new DB();

		$SqlColumnasGestionesExternas = "select COLUMN_NAME as Columna from information_schema.COLUMNS where TABLE_SCHEMA='foco' AND TABLE_NAME='GE_".$_SESSION["cedente"]."' and COLUMN_NAME not in ('id','Rut') order by COLUMN_NAME";
		$ColumnasGestionesExternas = $db->select($SqlColumnasGestionesExternas);
		if(count($ColumnasGestionesExternas) > 0){
			$Columnas = array();

			foreach($ColumnasGestionesExternas as $Columna){
				$Columna = $Columna["Columna"];
				array_push($Columnas,$Columna);
				$ArrayTmp = array();
				$ArrayTmp["data"] = $Columna;
				array_push($ToReturn["Columnas"],$ArrayTmp);
			}

			$ColumnasImplode = implode(",",$Columnas);

			$query = "	SELECT 
							".$ColumnasImplode."
						FROM 
							GE_".$_SESSION["cedente"]." 
						WHERE 
							Rut = '".$rut."'
						LIMIT 20";
			$Gestiones = $db->select($query);
			if(count($Gestiones) > 0){
				$ToReturn["Gestiones"] = $Gestiones;
			}
		}

		return $ToReturn;
	}

	public function mostrarGestionTotal($rut)
	{
		$db 		= new DB();
		$this->rut 	= $rut;
		$response 	= array();

		$q = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE rut_cliente = '" . $this->rut ."'");

		if(count($q) > 0)
		{
			$query1 = $db->select("SELECT 
										id_gestion, rut_cliente, fecha_gestion, resultado, fono_discado, 
										nombre_ejecutivo, cedente, fec_compromiso, monto_comp, Id_TipoGestion, 
										origen, n1, n2, n3, observacion, status_name, factura 
									FROM 
										gestion_ult_trimestre 
									WHERE 
										rut_cliente = '" . $this->rut . "' 
									ORDER BY fechahora DESC 
									LIMIT 20");
			if($query1){
				foreach($query1 as $q1)
				{
					$v1 	= $q1["rut_cliente"];
					$v2 	= $q1["fecha_gestion"];
					$v3 	= $q1["resultado"];
					$v4 	= $q1["fono_discado"];
					$v5 	= $q1["nombre_ejecutivo"];
					$v6 	= $q1["cedente"];
					$v7 	= $q1["fec_compromiso"];
					$v8 	= $q1["monto_comp"];
					$v9 	= $q1["Id_TipoGestion"];
					$v10 	= $q1["observacion"];
					$r1 	= $q1["n1"];
					$r2 	= $q1["n2"];
					$r3 	= $q1["n3"];

					$idGestion 	= $q1["id_gestion"];
					$origen 	= $q1["origen"];
					$statusName = $q1["status_name"];
					$factura 	= $q1["factura"];

					$res1 = "";
					$res2 = "";
					$res3 = "";

					if($origen==1)
					{
						if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
							$v7 = '---';
							$v8 = '---';
						}
						else
						{
							$v7 = $v7;
							$v8 = $v8;
						}
						$res1 = $r1;
						$res2 = $r2;
						$res3 = $r3;
						/*$query5 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = '$r1'");
						foreach($query5 as $q5)
						{
							$res1 = $q5["Gestion_Nivel_1"];
						}
						$res2 = "---";
						$res3 = "---";*/
					}
					else
					{
						if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
							$v7 = '---';
							$v8 = '---';
						}
						else
						{
							$v7 = $v7;
							$v8 = $v8;
						}

						$res1 = $r1;
						$res2 = $r2;
						$res3 = $r3;
					}

					$arreglo = array();
					
					$arreglo['fecha_gestion'] 	= $v2;
					$arreglo['fono_discado'] 	= $v4;
					$arreglo['ejecutivo'] 		= $v5;
					$arreglo['compromiso'] 		= $v7;
					$arreglo['monto'] 			= $v8;
					$arreglo['observacion'] 	= $v10;
					$arreglo['n1'] 				= $res1;
					$arreglo['n2'] 				= $res2;
					$arreglo['n3'] 				= $res3;
					$arreglo['status_name'] 	= $statusName;
					$arreglo['factura'] 		= $factura;
					$arreglo['gestion'] 		= $idGestion;

					array_push($response, $arreglo);
				}
			}
		}
		return $response;
	}

	public function mostrarGestionDiaria($rut)
	{
		$db 		= new DB();
		$this->rut 	= $rut;
		$response 	= array();

		$query = "	SELECT 
						id_gestion, rut_cliente, fecha_gestion, resultado, fono_discado, 
						nombre_ejecutivo, cedente, fec_compromiso, monto_comp, Id_TipoGestion, 
						origen, n1, n2, n3, observacion, status_name, factura 
					FROM 
						gestion_ult_trimestre 
					WHERE 
						rut_cliente = '".$this->rut."' 
					AND 
						fecha_gestion = '".date("Y-m-d")."' 
					ORDER BY 
						fechahora DESC";
		$gestiones = $db->select($query);

		if(count($gestiones) > 0){
			if($gestiones){
				foreach($gestiones as $gestion)
				{
					$v1 	= $gestion["rut_cliente"];
					$v2 	= $gestion["fecha_gestion"];
					$v3 	= $gestion["resultado"];
					$v4 	= $gestion["fono_discado"];
					$v5 	= $gestion["nombre_ejecutivo"];
					$v6 	= $gestion["cedente"];
					$v7 	= $gestion["fec_compromiso"];
					$v8 	= $gestion["monto_comp"];
					$v9 	= $gestion["Id_TipoGestion"];
					$v10	= $gestion["observacion"];
					$r1 	= $gestion["n1"];
					$r2 	= $gestion["n2"];
					$r3 	= $gestion["n3"];

					$origen 	= $gestion["origen"];
					$idGestion 	= $gestion["id_gestion"];
					$statusName	= $gestion["status_name"];
					$factura 	= $gestion["factura"];
					
					$idCanales = $db->select("SELECT canales FROM omnicanalidad WHERE rut = '$this->rut'");
					$canales = "";
					if (count($idCanales)){
						$nombresCanales = $db->select("SELECT canal FROM canales_omnicanalidad WHERE id IN (".$idCanales[0]['canales'].")");
						$canales = utf8_encode(implode(', ', array_column($nombresCanales, 'canal')));
					}
					
					if($origen==1)
					{
						if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
							$v7 = '---';
							$v8 = '---';
						}else{
							$v7 = $v7;
							$v8 = $v8;
						}
					}
					else
					{
						if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
							$v7 = '---';
							$v8 = '---';
						}else{
							$v7 = $v7;
							$v8 = $v8;
						}
					}

					$arreglo = array();
					
					$arreglo['fecha_gestion'] 	= $v2;
					$arreglo['fono_discado'] 	= $v4;
					$arreglo['ejecutivo'] 		= $v5;
					$arreglo['compromiso'] 		= $v7;
					$arreglo['monto'] 			= $v8;
					$arreglo['observacion'] 	= $v10;
					$arreglo['n1'] 				= $r1;
					$arreglo['n2'] 				= $r2;
					$arreglo['n3'] 				= $r3;
					$arreglo['status_name'] 	= $statusName;
					$arreglo['factura'] 		= $factura;
					$arreglo['canales'] 		= $canales;
					$arreglo['gestion'] 		= $idGestion;

					array_push($response, $arreglo);
				}
			}
		}
		return $response;
	}

	public function mostrarPagosRut($rut)
	{
		$db 		= new DB();
		$this->rut 	= $rut;
		$response 	= array();

		$rows = $db->select("SELECT * FROM pagos_deudas WHERE Rut = '" . $this->rut. "' and Id_Cedente='".$_SESSION["cedente"]."'");
		
		if(count($rows) > 0){
			if($rows){
				foreach($rows as $row)
				{
					$arreglo = array();
					
					$arreglo['rut'] 		= $row["Rut"];
					$arreglo['fecha'] 		= $row["Fecha_Pago"];
					$arreglo['monto'] 		= $row["Monto"];
					$arreglo['operacion'] 	= $row["Numero_Operacion"];

					array_push($response, $arreglo);
				}
			}
		}
		return $response;
	}

	public function mostrarCuentasRut($rut)
	{
		$db = new DB();
		$response = array();
		$query = "SELECT * FROM Deuda WHERE Rut = '" . $rut . "'";
		$rows = $db->select($query);

		$Grupo = '';
		$Sociedad = '';
		$Deudor = '';
		$Cuenta = '';
		$Dia = '';

		$Cuentas = array();

		foreach($rows as $row){

				$fecha_documento = new DateTime($row["Fecha_Documento"]);
				$fecha_vencimiento = new DateTime($row["Fecha_Vencimiento"]);

				$arreglo = array();

				$arreglo['rut'] 				= $row["Rut"];
	        	$arreglo['soc'] 				= $row["Soc"];
	        	$arreglo['numero_documento']	= $row["Numero_Factura"];
	        	$arreglo['clase'] 				= $row["Clase"];
	        	$arreglo['asignacion'] 			= $row["Asignacion"];
	        	$arreglo['referencia'] 			= $row["Referencia"];
	        	$arreglo['div'] 				= $row["Div1"];
	        	$arreglo['cebe'] 				= $row["CeBe"];
	        	$arreglo['fecha_documento'] 	= $fecha_documento->format('d-m-Y');
	        	$arreglo['dia'] 				= $row["Dia"];
	        	$arreglo['fecha_vencimiento'] 	= $fecha_vencimiento->format('d-m-Y');
	        	$arreglo['mora'] 				= $row["Mora"];
	        	$arreglo['importe'] 			= $row["Importe"];
	        	$arreglo['observacion'] 		= $row["Observacion"];

				array_push($Cuentas, $arreglo);

				$Grupo = $row["Grupo"];
				$Sociedad = $row["Soc"];
				$Deudor = $row["Resp_Deuda"];
				$Cuenta = $row["Cuenta_Deudor"];
				$Dia = $row["Dia"];
			
		}

		$response['Cuentas'] = $Cuentas;
 
		$Cliente = array();

		$query = "SELECT Nombre_Completo FROM Persona WHERE Rut = '" . $rut . "'";
		$Persona = $db->select($query);

		if($Persona){
			$Cliente['Nombre'] = $Persona[0]['Nombre_Completo'];
		}else{
			$Cliente['Nombre'] = '';
		}


		$query = "SELECT numero_telefono as Telefono FROM fono_cob WHERE Rut = '" . $rut . "'";
		$Fono = $db->select($query);

		if($Fono){
			$Cliente['Nota'] = $Fono[0]['Telefono'];
			$Cliente['Nota'] = '+56' . $Fono[0]['Telefono'];
		}else{
			$Cliente['Nota'] = '';
			$Cliente['Telefono'] = '';
		}

		$Cliente['Rut'] = $rut;
		$Cliente['Grupo'] = $Grupo;
		$Cliente['Sociedad'] = $Sociedad;
		$Cliente['Deudor'] = $Deudor;
		$Cliente['Cuenta'] = $Cuenta;
		$Cliente['Condicion'] = 'D_'.$Dia;

		$response['Cliente'] = $Cliente;
 
		return $response;
	}

	public function mostrandoFonos($datos)
	{

		$db = new DB();
		$rut = $datos['rut'];
		$WherePrioridades = "";
		$WhereColores = "";
		if(isset($datos['cola'])){
			$idCola = $datos['cola'];
			$query  = "SELECT color, Prioridad_Fono FROM SIS_Querys_Estrategias WHERE id = '".$idCola."'";
			$SIS_Querys_Estrategias = $db->select($query);
			if($SIS_Querys_Estrategias){
				$colores = $SIS_Querys_Estrategias[0]['color'];
				$Prioridades_Fonos = $SIS_Querys_Estrategias[0]['Prioridad_Fono'];
				$WherePrioridades = " AND (f.Prioridad_Fono IN (".$Prioridades_Fonos.") OR f.color = 35)";
				$WhereColores = " AND (f.color IN (".$colores.") OR f.color = 35)";
			}else{
				$colores = '';
				$Prioridades_Fonos = '';
			}
		}else{
			$SIS_Querys_Estrategias = '';
			$colores = '';
			$Prioridades_Fonos = '';
		}
		$WhereExclusiones = " AND f.formato_subtel NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 2 AND Id_Cedente = '".$_SESSION['cedente']."' AND Fecha_Term >= CURDATE())";

		echo '<div class="table-responsive">';
        echo '<table id="tablaTelefonos" class="table table-striped table-bordered" cellspacing="0" width="100%">';
		echo '<thead>';
		switch($_SESSION['tipoSistema']){
			case "1":
			case "2":
				echo '<th class="text-sm"></th>';
			break;
		}
        echo '<th class="text-sm"><center>Color</center></th>';
        echo '<th class="text-sm">Comentario</th>';
		echo '<th class="text-sm">Numeros</th>';
		echo '<th class="text-sm">Prioridad</th>';
		echo '<th class="text-sm"><center>Llamar</center></th>';
		echo '<th class="text-sm"><center>Detalle</center></th>';
        echo '</tr></thead><tbody>';

		// si colores viene vacio da error

		$ColumnasFonoCob = "f.formato_subtel as fono, f.color as color, f.fecha_carga as fechaCarga, f.cedente as Cedente, c.mundo as Mundo, c.prioridad as Prioridad, f.id_fono as id, f.Nombre,f.Cargo, f.Observacion, f.Prioridad_Fono as prioridad";
		if ($SIS_Querys_Estrategias != ''){
			if(($Prioridades_Fonos != "") && ($Prioridades_Fonos != "undefined")){
				$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $rut AND f.color = c.color ".$WherePrioridades." AND c.mundo = 1 AND f.vigente = 1 ".$WhereExclusiones." GROUP BY f.formato_subtel, f.color ORDER BY c.prioridad ASC LIMIT 10";
				$rows = $db->select($Sql);
				if (count($rows) == 0){
					$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $rut AND f.color = c.color AND c.mundo = 1 AND f.vigente = 1 ".$WhereExclusiones." GROUP BY f.formato_subtel, f.color ORDER BY c.prioridad ASC LIMIT 3";
					$rows = $db->select($Sql);
				}
			}else{
				$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $rut AND f.color = c.color ".$WhereColores." AND c.mundo = 1 AND f.vigente = 1 ".$WhereExclusiones." GROUP BY f.formato_subtel, f.color ORDER BY c.prioridad ASC LIMIT 10";
				$rows = $db->select($Sql);
				if (count($rows) == 0){
					$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $rut AND f.color = c.color AND c.mundo = 1 AND f.vigente = 1 ".$WhereExclusiones." GROUP BY f.formato_subtel, f.color ORDER BY c.prioridad ASC LIMIT 3";
					$rows = $db->select($Sql);
				}
			}
		}else{
			/*if($Prioridades_Fonos != ""){
				$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut AND f.color = c.color ".$WherePrioridades." AND c.mundo = 1 AND f.vigente = 1 ORDER BY c.prioridad ASC LIMIT 10";
				$rows = $db->select($Sql);
				if (count($rows) == 0){
					$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut AND f.color = c.color AND c.mundo = 1 AND f.vigente = 1 ORDER BY c.prioridad ASC LIMIT 10";
					$rows = $db->select($Sql);
				}
			}else{
				$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut AND f.color = c.color AND c.mundo = 1 AND f.vigente = 1 ORDER BY c.prioridad ASC LIMIT 10";
				$rows = $db->select($Sql);
			}*/
			$Sql = "SELECT ".$ColumnasFonoCob." FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $rut AND f.color = c.color AND c.mundo = 1 AND f.vigente = 1 ".$WhereExclusiones." GROUP BY f.formato_subtel, f.color ORDER BY c.prioridad ASC LIMIT 10";
			$rows = $db->select($Sql);
		}
		$i=1;
		if($rows){
			foreach($rows as $row){
				$fono 			= $row["fono"];
				$id_color 		= $row["color"];
				$id 			= $row["id"];
				$prioridad 		= $row["prioridad"];
				$Nombre 		= $row["Nombre"];
				$Cargo 			= $row["Cargo"];
				$Observacion 	= $row["Observacion"];

				$query = "SELECT color, comentario FROM SIS_Colores WHERE id = $id_color";
				$SIS_Colores = $db->select($query);

				foreach($SIS_Colores as $SIS_Color){

					$color = $SIS_Color["color"];
					$comentario = $SIS_Color["comentario"];
					echo "<tr id='$i' class='".$id."'>";
					switch($_SESSION['tipoSistema']){
						case "1":
						case "2":
							if(substr($fono, 0, 1) == 9){
								echo '<td class="text-sm" style="text-align: center;"><label class="form-checkbox form-normal form-primary inputCheckFono" style="margin: 10px 0px;"><input type="checkbox"></label></td>';
							}else{
								echo '<td class="text-sm" style="text-align: center;"></td>';
							}
						break;
					}
					echo "<td class='text-sm' style='padding-top: 15px;'><center><i color='$id_color' class='fa fa-flag fa-lg icon-lg' style='color:$color'></i> </center></td>";
					echo "<td class='text-sm' style='padding-top: 15px;'> $comentario </td>";
					echo "<td class='text-sm'><input type='hidden' id='telefono$i' value='$fono' name='telefono$i'><input type='text' class='telefono_cambiar text6 telefono SoloNumeros' value='".$fono."'></td>";
					echo "<td class='text-sm' style='padding-top: 15px;'> $prioridad </td>";
					echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-success btn-icon icon-lg fa fa-phone Llamar'  value='Llamar'> </button> </center></td>";
					echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-primary btn-icon icon-lg fa fa-search VerFono'  value='VerFono'> </button> </center></td>";

					echo '</tr>';
					$i++;
				}
			}
		}
        echo '</tbody></table></div>';
	}

	public function mostrarDetalleFono($id_fono)
	{

		$db = new DB();
		$this->id_fono = $id_fono;
		
		echo '<div class="table-responsive">';
        echo '<table id="tablaDetalleTelefono" class="table table-striped table-bordered" cellspacing="0" width="100%">';
		echo '<thead>';
        echo '<th class="text-sm"><center>Color</center></th>';
        echo '<th class="text-sm">Comentario</th>';
		echo '<th class="text-sm">Numero</th>';
		echo '<th class="text-sm">Nombre</th>';
		echo '<th class="text-sm">Cargo</th>';
		echo '<th class="text-sm">Observación</th>';
		echo '<th class="text-sm">Prioridad</th>';
        echo '</tr></thead><tbody>';

		$query = "	SELECT 
						f.formato_subtel as fono, 
						f.color as color, 
						f.id_fono as id, 
						f.Nombre,f.Cargo,
						f.Observacion, 
						f.Prioridad_Fono as prioridad 
					FROM 
						fono_cob f
					WHERE 
						f.id_fono = '".$this->id_fono."'";

		$fono_cob = $db->select($query);
		if($fono_cob){
			foreach($fono_cob as $row){
				$fono 			= $row["fono"];
				$id_color 		= $row["color"];
				$id 			= $row["id"];
				$prioridad 		= $row["prioridad"];
				$Nombre 		= $row["Nombre"];
				$Cargo 			= $row["Cargo"];
				$Observacion 	= $row["Observacion"];

				$query = "SELECT color, comentario FROM SIS_Colores WHERE id = $id_color";
				$SIS_Colores = $db->select($query);

				foreach($SIS_Colores as $SIS_Color){

					$color = $SIS_Color["color"];
					$comentario = $SIS_Color["comentario"];
					echo "<tr class='".$id."'>";
					echo "<td class='text-sm' style='padding-top: 15px;'><center><i color='$id_color' class='fa fa-flag fa-lg icon-lg' style='color:$color'></i> </center></td>";
					echo "<td class='text-sm' style='padding-top: 15px;'> $comentario </td>";
					echo "<td class='text-sm'><input type='hidden' id='telefono' value='$fono' name='telefono'><input type='text' class='telefono_cambiar text6 telefono SoloNumeros' value='".$fono."'></td>";
					echo "<td class='text-sm'><input type='hidden' id='Nombre' value='".$Nombre."' name='Nombre'><input type='text' class='telefono_cambiar text6 nombre' value='".$Nombre."'></td>";
					echo "<td class='text-sm'><input type='hidden' id='Cargo' value='".$Cargo."' name='Cargo'><input type='text' class='telefono_cambiar text6 cargo' value='".$Cargo."'></td>";				
					echo "<td class='text-sm'><input type='hidden' id='Observacion' value='".$Observacion."' name='Observacion'><input type='text' class='telefono_cambiar text6 observacion' value='".$Observacion."'></td>";	
					echo "<td class='text-sm'>$prioridad</td>";

					echo '</tr>';
				}
			}
		}
        echo '</tbody></table></div>';
	}

	public function mostrarFono($rut,$fono)
	{
		$db = new DB();
		$this->rut=$rut;
		echo '<div class="table-responsive">';
        echo '<table id="tablaTelefonos" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<th class="text-sm"><center>Color</center></th>';
        echo '<th class="text-sm">Comentario</th>';
		echo '<th class="text-sm">Numero</th>';
		echo '<th class="text-sm">Nombre</th>';
		echo '<th class="text-sm">Cargo</th>';
		echo '<th class="text-sm">Observacion</th>';
        echo '<th class="text-sm">Fecha Carga</th>';
		echo '<th class="text-sm"><center>Llamada</center></th>';
        //echo '<th class="text-sm">Origen</th>';
        //echo '<th class="text-sm"><center>Fono Gestión</center></th>';
        echo '<th class="text-sm"><center>Llamar</center></th></tr>';
        echo '</thead><tbody>';

		$rows = $db->select("SELECT f.formato_subtel as fono,f.color as color,f.fecha_carga as fechaCarga,f.cedente as cedente, c.mundo, c.prioridad, f.id_fono,f.Nombre,f.Cargo,f.Observacion FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.formato_subtel = $fono AND  f.Rut = $this->rut AND f.color = c.color AND c.mundo = 1 AND f.vigente = 1 LIMIT 1");
		   $i=1;
   		foreach($rows as $row)
    	{
    		$f1 = $row["fono"];
    		$c = $row["color"];
    		$g1 = $row["fechaCarga"];
			$g2 = $row["cedente"];
			$Nombre = $row["Nombre"];
			$Cargo = $row["Cargo"];
			$Observacion = $row["Observacion"];
    		if($g2=='')
    		{
    			$g2 = "Soporte";
    		}
    		else
    		{
    			$g2 = $g2;
    		}
    		$colores = $db->select("SELECT color,comentario  FROM SIS_Colores WHERE id = $c  ");
       		foreach($colores as $color)
        	{

			   	$color1 = $color["color"];
			   	$comentario = $color["comentario"];
			    echo "<tr id='$i'>";
			    echo "<td class='text-sm'><center><i class='fa fa-flag fa-lg icon-lg' style='color:$color1'></i> </center></td>";
			    echo "<td class='text-sm'>$comentario</td>";
				echo "<td class='text-sm'><input type='hidden' id='telefono$i' value='$f1' name='telefono$i'>$f1</td>";
				
			    echo "<td class='text-sm'><input type='hidden' id='Nombre$i' value='".$Nombre."' name='Nombre$i'>".$Nombre."</td>";
			    echo "<td class='text-sm'><input type='hidden' id='Cargo$i' value='".$Cargo."' name='Cargo$i'>".$Cargo."</td>";				
			    echo "<td class='text-sm'><input type='hidden' id='Observacion$i' value='".$Observacion."' name='Observacion$i'>".$Observacion."</td>";				

			    echo "<td class='text-sm'>$g1</td>";
				echo "<td class='text-sm'><center><input type='checkbox' disabled  class='fono_gestion' name='llamado$i' value='llamado$i' id='llamado$i' ></center></td>";
			    //echo "<td class='text-sm'>$g2</td>";
			   // echo "<td class='text-sm'><center><input type='checkbox' class='fono_gestion' name='fg$i' value='fg$i' id='fg$i' ></center></td>";
			    echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-danger btn-icon icon-lg fa fa-phone CortarPredictivo'  value='Cortar'> </button> </center></td>";

			    echo '</tr>';
			    $i++;
			}
	    }
        echo '</tbody></table></div>';
	}

	public function insertarFonos($rut,$fono,$Nombre,$Cargo,$Observacion,$cola,$i)
	{
		$db = new DB();
		$fecha_carga = date("Y-m-d");
		$id_color = 35;
		$prioridad = 0;
		$query = "INSERT INTO fono_cob(Rut,formato_subtel,color,formato_dial,numero_telefono,Nombre,Cargo,Observacion,fecha_carga,cedente) VALUES ('$rut','$fono','$id_color','$fono','$fono','".$Nombre."','".$Cargo."','".$Observacion."','$fecha_carga','foco') ON DUPLICATE KEY UPDATE color = '$id_color', fecha_carga = '$fecha_carga', cedente = 'foco'";
		$id = $db->insert($query);
		if($id){
			$query = "SELECT color, comentario FROM SIS_Colores WHERE id = '$id_color'";
			$SIS_Colores = $db->select($query);
			if($SIS_Colores){
				$color = $SIS_Colores[0]["color"];
				$comentario = $SIS_Colores[0]["comentario"];
				echo "<tr id='$i' class='".$id."'>";
					switch($_SESSION['tipoSistema']){
						case "1":
						case "2":
							if(substr($fono, 0, 1) == 9){
								echo '<td class="text-sm" style="text-align: center;"><label class="form-checkbox form-normal form-primary inputCheckFono" style="margin: 10px 0px;"><input type="checkbox"></label></td>';
							}else{
								echo '<td class="text-sm" style="text-align: center;"></td>';
							}
						break;
					}
					echo "<td class='text-sm' style='padding-top: 15px;'><center><i color='$id_color' class='fa fa-flag fa-lg icon-lg' style='color:$color'></i> </center></td>";
					echo "<td class='text-sm' style='padding-top: 15px;'> $comentario </td>";
					echo "<td class='text-sm'><input type='hidden' id='telefono$i' value='$fono' name='telefono$i'><input type='text' class='telefono_cambiar text6 telefono SoloNumeros' value='".$fono."'></td>";
					echo "<td class='text-sm' style='padding-top: 15px;'> $prioridad </td>";
					echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-success btn-icon icon-lg fa fa-phone Llamar'  value='Llamar'> </button> </center></td>";
					echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-primary btn-icon icon-lg fa fa-search VerFono'  value='VerFono'> </button> </center></td>";
				echo '</tr>';
			}
		}
	}

	public function insertarFonoCola($idCola, $fono, $rut){
		$db = new DB();
		$sql = "SELECT Asterisk_Discador_Cola.Cola as cola, Asterisk_All_Queues.Queue as queue FROM Asterisk_Discador_Cola INNER JOIN Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id WHERE Asterisk_Discador_Cola.id = '".$idCola."'";
		$resultado = $db->select($sql);
		$cola = $resultado[0]['cola'];
		$queue = $resultado[0]['queue'];
		$colaDR = "DR_".$queue.$cola;
		$Sql = "INSERT INTO ".$colaDR."(Fono,Rut,Cedente) VALUES ('".$fono."','".$rut."','".$_SESSION['cedente']."')";
		$db -> query($Sql);
		// $Sql = "UPDATE ".$Asignacion." set orden = '".$contador."' WHERE id='".$id."'";
	}


	public function eliminarBridge(){
		$db = new DB();
		$anexo = $_SESSION['anexo_foco'];
		$sql = "DELETE FROM Asterisk_Bridge WHERE Anexo = '".$anexo."'";
		$db -> query($sql);
	}

	public function insertarDireccion($rut,$direccion_nuevo)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->direccion_nuevo=$direccion_nuevo;
		$db->query("INSERT INTO Direcciones(Rut,Direccion) VALUES ('$this->rut','$this->direccion_nuevo')");
		echo '<div class="table-responsive">';
        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><tr>';
        echo '<th class="text-sm"><center>Direccion</center></th></tr>';
        echo '</thead><tbody>';
        $rows = $db->select("SELECT Direccion  FROM  Direcciones WHERE Rut = $this->rut ");
   		$i = 1;
		foreach($rows as $row)
    	{
    		$d1 = $row["Direccion"];
		    echo "<tr id='$i'>";
		    echo "<td class='text-sm'>$d1</td>";
		    echo '</tr>';
			$i++;
	    }
        echo '</tbody></table></div>';

	}
	public function verCargo()
	{
		$db = new DB();
		echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo '<form class="form-horizontal">';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nombre</label>';
		echo '<div class="col-md-4" lateral>';
		echo '<input id="nombre" name="nombre" type="text" class="form-control input-md lateral2"/>';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nuevo Correo</label>';
		echo '<div class="col-md-4">';
		echo '<input id="correo_nuevo" name="name" type="text" placeholder="" class="form-control input-md" >';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Cargo</label>';
		echo '<div class="col-md-4 ">';
		echo "<select class='selectpicker col-md-4 lateral' id='cargo' name='cargo' data-live-search='true' data-width='100%'>";
        $rows=$db->select("SELECT id,Cargo FROM Mail_Cargo");
		   echo "<option value='0'>Seleccione</option>";
		if($rows){
			foreach($rows as $row)
			{
				echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Cargo"]); echo "</option>";
			}
		}
        echo "</select>";
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Tipo Uso</label>';
		echo '<div class="col-md-4">';
		echo '<select class="selectpicker" multiple title="Seleccione los items..."  name="uso" id="uso" data-live-search="true" data-width="100%">';
		$rows=$db->select("SELECT id,Uso FROM Mail_Uso");
		if($rows){
			foreach($rows as $row)
			{
				echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Uso"]); echo "</option>";
			}
		}
        echo '</select>';
		echo '</div>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
	public function verCargo2()
	{
		$db = new DB();
		echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo '<form class="form-horizontal">';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nombre</label>';
		echo '<div class="col-md-4" lateral>';
		echo '<input id="nombre_cc" name="nombre_cc" type="text" class="form-control input-md lateral2"/>';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nuevo Correo</label>';
		echo '<div class="col-md-4">';
		echo '<input id="correo_nuevo_cc" name="name" type="text" placeholder="" class="form-control input-md" >';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Cargo</label>';
		echo '<div class="col-md-4 ">';
		echo "<select class='selectpicker col-md-4 lateral' id='cargo_cc' name='cargo' data-live-search='true' data-width='100%'>";
		$rows=$db->select("SELECT id,Cargo FROM Mail_Cargo");
		echo "<option value='0'>Seleccione</option>";
		if($rows){
			foreach($rows as $row)
			{
				echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Cargo"]); echo "</option>";
			}
		}
        echo "</select>";
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Tipo Uso</label>';
		echo '<div class="col-md-4">';
		echo '<select class="selectpicker" multiple title="Seleccione los items..."  name="uso" id="uso_cc" data-live-search="true" data-width="100%">';
		$rows=$db->select("SELECT id,Uso FROM Mail_Uso");
		if($rows){
			foreach($rows as $row)
			{
				echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Uso"]); echo "</option>";
			}
		}
        echo '</select>';
		echo '</div>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
	public function insertarCorreo($rut,$correo_nuevo,$cargo,$uso,$nombre,$Queue)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->correo_nuevo=$correo_nuevo;
		$this->cargo=$cargo;
		$this->uso=$uso;
		$this->nombre=$nombre;
		$query = "INSERT INTO Mail(rut,correo_electronico,Cargo,Tipo_Uso,Nombre) VALUES ('$this->rut','$this->correo_nuevo','$this->cargo','$this->uso','$this->nombre')";
		$db->query($query);
		if(!$Queue){
			$this->mostrarCorreoRut($this->rut);
		}else{
			$dbDiscador = new DB("discador");
			$query = "INSERT INTO ".$Queue."_Mail(rut,correo_electronico,Cargo,Tipo_Uso,Nombre) VALUES ('$this->rut','$this->correo_nuevo','$this->cargo','$this->uso','$this->nombre')";
			$dbDiscador->query($query);
			$this->mostrarCorreoRutPredictivo($this->rut,$Queue);
		}
	}
	public function insertarCorreocc($rut,$correo_nuevo,$cargo,$uso,$nombre)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->correo_nuevo=$correo_nuevo;
		$this->cargo=$cargo;
		$this->uso=$uso;
		$this->nombre=$nombre;

		$db->query("INSERT INTO Mail_CC(rut,correo_electronico,Cargo,Tipo_Uso,Nombre) VALUES ('$this->rut','$this->correo_nuevo','$this->cargo','$this->uso','$this->nombre')");
		$this->mostrarCorreoRutcc($this->rut);

	}
	public function mostrarDireccionRut($rut){
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT Id_Direccion, Direccion, Comuna FROM Direcciones WHERE Rut = $this->rut ");
		if(!$q)
		{
			echo "Rut no registra Direcciones , Haga Click en el Boton <b>+ </b>Para Agregar una.";
		}
		else
		{
			echo '<div class="table-responsive">';
				echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
					echo '<thead>';
						echo '<tr>';
							echo '<th class="text-sm">Direccion</th>';
							echo '<th class="text-sm">Comuna</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
						//$q1 = $db->select("SELECT Direccion FROM  Direcciones WHERE Rut = $this->rut ");
						$i = 1;
						foreach($q as $row)
						{
							$Id_Direccion = $row["Id_Direccion"];
							$Direccion = $row["Direccion"];
							$Comuna = $row["Comuna"];

							echo "<tr id='$i' class='".$Id_Direccion."'>";
								echo "<td class='text-sm'><input type='text' class='direccion_cambiar text6 direccion' value='".$Direccion."'></td>";
								echo "<td class='text-sm'><input type='text' class='direccion_cambiar text6 comuna' value='".$Comuna."'></td>";
							echo '</tr>';
							$i++;
						}
					echo '</tbody>';
				echo '</table>';
			echo '</div>';
    	}

	}
	public function mostrarDireccionRutPredictivo($rut,$Queue){
		$db = new DB("discador");
		$this->rut=$rut;
		$q = $db->select("SELECT Direccion FROM ".$Queue."_Direcciones WHERE Rut = $this->rut ");
		if(count($q)==0)
		{
			echo "Rut no registra Direcciones , Haga Click en el Boton <b>+ </b>Para Agregar una.";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';

	        echo '<th class="text-sm">Direccion</th></tr>';
	        echo '</thead><tbody>';
		    //$q1 = $db->select("SELECT Direccion FROM  Direcciones WHERE Rut = $this->rut ");
		    $i = 1;
			foreach($q as $row)
	        {
	        	$v1 = $row["Direccion"];

			    echo "<tr id='$i'>";
			    echo "<td class='text-sm'>$v1</td>";
			    echo '</tr>';
				$i++;
	    	}
	    	echo '</tbody></table></div>';
    	}

	}

	public function mostrarCorreoRut($rut){
		$db = new DB();
		$query = "	SELECT
						correo_electronico,
						Cargo,
						Tipo_Uso,
						id_mail,
						Nombre,
						Observacion 
					FROM
						Mail 
					WHERE
						rut = '".$rut."' 
						AND correo_electronico NOT IN ( SELECT Dato FROM Exclusiones WHERE Tipo = 3 AND Id_Cedente = '".$_SESSION['cedente']."' AND Fecha_Term >= '".date('Y-m-d ')."')";
		$correos = $db->select($query);
		if($correos){
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
			switch($_SESSION['tipoSistema']){
				case "1":
				case "2":
					echo '<th class="text-sm"></th>';
				break;
			}
	        echo '<th class="text-sm">Correo</th>';
	        echo '<th class="text-sm">Nombre</th>';
	        echo '<th class="text-sm">Observación</th>';
	        echo '<th class="text-sm"><center>Cargo</center></th>';
	        echo '<th class="text-sm"><center>Tipo Uso</center></th>';
	        //echo '<!--<th class="text-sm"><center>Enviar</center></th>-->';
	        echo '</tr>';
	        echo '</thead><tbody>';
	        $i=1;
			foreach($correos as $correo){
				$d1 = $correo["correo_electronico"];
				if($d1){
					$d2 = $correo["Cargo"];
					$d3 = $correo["Tipo_Uso"];
					$d4 = $correo["id_mail"];
					$d5 = $correo["Nombre"];
					$d6 = $correo["Observacion"];
					echo "<tr id='$i' class='$d4'>";
					switch($_SESSION['tipoSistema']){
						case "1":
						case "2":
							echo '<td class="text-sm" style="text-align: center;"><label class="form-checkbox form-normal form-primary inputCheckCorreo" style="margin: 10px 0px;"><input type="checkbox"></label></td>';
						break;
					}
					echo "<td class='text-sm'><input type='text' class='correo_cambiar text6 NombreCorreo' value='$d1' id='correo$i'></td>";
					if($d2 != ""){
						$query2 = $db->select("SELECT Cargo FROM  Mail_Cargo WHERE id = $d2");
						if($query2){
							foreach($query2 as $q2)
							{
								$c1 = $q2["Cargo"];
							}
						}else{
							$c1 = '';
						}
					}else{
						$c1 = "";
					}
					if($d3 != ""){
						$query3 = "SELECT Uso FROM  Mail_Uso WHERE FIND_IN_SET(id,'".$d3."')";
						$rows = $db->select($query3);
						if($rows){
							foreach($rows as $q3)
							{
								$c2 = $q3["Uso"];
							}
						}else{
							$c2 = "";
						}
					}else{
						$c2 = "";
					}
					echo "<td class='text-sm'><center><input type='text' class='correo_cambiar text6' value='$d5' id='nombre$i'></center></td>";
					echo "<td class='text-sm'><center><input type='text' class='correo_cambiar text6' value='$d6' id='obs$i'></center></td>";
					echo "<td class='text-sm'><center>$c1</center></td>";
					echo "<td class='text-sm'><center>$c2</center></td>";
					//echo "<!--<td class='text-sm'><center><input type='checkbox' class='adjuntar' name='l$i' value='l$i' id='l$i' ></center></td>-->";
					echo '</tr>';
					$i++;
				}
			}
	        echo '</tbody></table></div><!--<button class="btn btn-primary adjuntar_boton" disabled = "disabled"  id="enviar_factura">Enviar</button>-->';
    	}else{
			echo "Rut no registra Correos Electrónicos , Haga Click en el Boton <b>+ </b>Para Agregar uno nuevo.";
		}
	}
	public function mostrarCorreoRutPredictivo($rut,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$this->rut=$rut;
		$q = $dbDiscador->select("SELECT correo_electronico,Cargo,Tipo_Uso,id_mail,Nombre,Observacion FROM ".$Queue."_Mail WHERE rut = $this->rut ");
		if(count($q)==0)
		{
			echo "Rut no registra Correos Electrónicos , Haga Click en el Boton <b>+ </b>Para Agregar uno nuevo.";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
			switch($_SESSION['tipoSistema']){
				case "1":
				case "2":
					echo '<th class="text-sm"></th>';
				break;
			}
	        echo '<th class="text-sm">Correo</th>';
	        echo '<th class="text-sm">Nombre</th>';
	        echo '<th class="text-sm">Observación</th>';
	        echo '<th class="text-sm"><center>Cargo</center></th>';
	        echo '<th class="text-sm"><center>Tipo Uso</center></th>';
	        //echo '<!--<th class="text-sm"><center>Enviar</center></th>-->';
	        echo '</tr>';
	        echo '</thead><tbody>';
	        $i=1;
			//$query1 = $db->select("SELECT correo_electronico,Cargo,Tipo_Uso,id_mail,Nombre,Observacion FROM ".$Queue."_Mail WHERE rut = $this->rut ");
			if($q){
				foreach($q as $q1)
				{
					$d1 = $q1["correo_electronico"];
					$d2 = $q1["Cargo"];
					$d3 = $q1["Tipo_Uso"];
					$d4 = $q1["id_mail"];
					$d5 = $q1["Nombre"];
					$d6 = $q1["Observacion"];
					echo "<tr id='$i' class='$d4'>";
					switch($_SESSION['tipoSistema']){
						case "1":
						case "2":
							echo '<td class="text-sm" style="text-align: center;"><label class="form-checkbox form-normal form-primary inputCheckCorreo" style="margin: 10px 0px;"><input type="checkbox"></label></td>';
						break;
					}
					echo "<td class='text-sm'><input type='text' class='text6 NombreCorreo' value='$d1' id='correo$i'></td>";
					if($d2 != ""){
						$query2 = $db->select("SELECT Cargo FROM  Mail_Cargo WHERE id = $d2");
						if($query2){
							foreach($query2 as $q2)
							{
								$c1 = $q2["Cargo"];
							}
						}else{
							$c1 = "";
						}
					}else{
						$c1 = "";
					}
					if($d3 != ""){
						$query3 = $db->select("SELECT Uso FROM  Mail_Uso WHERE id = $d3");
						if($query3){
							foreach($query3 as $q3)
							{
								$c2 = $q3["Uso"];
							}
						}else{
							$c2 = "";
						}
					}else{
						$c2 = "";
					}
					echo "<td class='text-sm'><center><input type='text' class='text6' value='$d5' id='nombre$i'></center></td>";
					echo "<td class='text-sm'><center><input type='text' class='text6' value='$d6' id='obs$i'></center></td>";
					echo "<td class='text-sm'><center>$c1</center></td>";
					echo "<td class='text-sm'><center>$c2</center></td>";
					//echo "<!--<td class='text-sm'><center><input type='checkbox' class='adjuntar' name='l$i' value='l$i' id='l$i' ></center></td>-->";
					echo '</tr>';
					$i++;
				}
			}
	        echo '</tbody></table></div><!--<button class="btn btn-primary adjuntar_boton" disabled = "disabled"  id="enviar_factura">Enviar</button>-->';
    	}

	}
	public function mostrarCorreoRutcc($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT correo_electronico FROM  Mail_CC ");
		if(count($q)==0)
		{
			echo "Rut no registra Correos Electrónicos , Haga Click en el Boton <b>+ </b>Para Agregar uno nuevo.";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Correo</th>';
	        echo '<th class="text-sm">Nombre</th>';
	        echo '<th class="text-sm">Observación</th>';
	        echo '<th class="text-sm"><center>Cargo</center></th>';
	        echo '<th class="text-sm"><center>Tipo Uso</center></th>';
	        echo '<th class="text-sm"><center>Enviar</center></th>';
	        echo '</tr>';
	        echo '</thead><tbody>';
	        $k=1;
	        $rows = $db->select("SELECT correo_electronico,Cargo,Tipo_Uso,id_mail,Nombre,Observacion FROM  Mail_CC ");
	   		foreach($rows as $row)
	    	{
	    		$d1 = $row["correo_electronico"];
	    		$d2 = $row["Cargo"];
	    		$d3 = $row["Tipo_Uso"];
	    		$d4 = $row["id_mail"];
	    		$d5 = $row["Nombre"];
	    		$d6 = $row["Observacion"];
			    echo "<tr id='$k' class='$d4'>";
			    echo "<td class='text-sm'><input type='text' class='correo_cambiar_cc text6' value='$d1' id='correo_cc$k'></td>";
			    $rowsCargo = $db->select("SELECT Cargo FROM  Mail_Cargo WHERE id = $d2");
				foreach($rowsCargo as $rowCargo)
			    {
			       	$c1 = $rowCargo["Cargo"];
			    }
			    $rowsUso = $db->select("SELECT Uso FROM  Mail_Uso WHERE id = $d3");
				foreach($rowsUso as $rowUso)
			    {
			       	$c2 = $rowUso["Uso"];

			    }
			    echo "<td class='text-sm'><center><input type='text' class='correo_cambiar_cc text6' value='$d5' id='nombre_cc$k'></center></td>";
			    echo "<td class='text-sm'><center><input type='text' class='correo_cambiar_cc text6' value='$d6' id='obs_cc$k'></center></td>";
			    echo "<td class='text-sm'><center>$c1</center></td>";
			    echo "<td class='text-sm'><center>$c2</center></td>";
			    echo "<td class='text-sm'><center><input type='checkbox' class='adjuntar_cc' name='l_cc$k' value='l_cc$k' id='l_cc$k' ></center></td>";
			    echo '</tr>';
			    $k++;
		    }
	        echo '</tbody></table></div>';
    	}

	}
	public function mostrarDirecciones($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		echo '<div class="table-responsive">';
        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><tr>';
        echo '<th class="text-sm">Direccion</th>';
        echo '<th class="text-sm"><center></center></th>';
        echo '<th class="text-sm"><center></center></th></tr>';
        echo '</thead><tbody>';
	    $rows = $db->select("SELECT Direccion FROM Direcciones  WHERE Rut = $this->rut");
	    $i = 1;
		foreach($rows as $row)
	   	{
	        $d = $row["Direccion"];
            echo "<tr id='$i'>";
            echo "<td class='text-sm'>$d</td>";
            echo "<td class='text-sm'><center></center></td>";
            echo "<td class='text-sm'><center></center></td></td>";
            echo '</tr>';
			$i++;
		}
        echo '</tbody></table></div>';

	}
	public function nivel_rapido($cedente)
	{
		$db = new DB();
		$this->cedente=$cedente;
		echo "<select class='selectpicker' id='respuesta' name='respuesta' data-live-search='true' data-width='100%'>";
        $rows=$db->select("SELECT n3.Respuesta_N3 as Respuesta_Rapida, n3.id as respuesta_n3
							 FROM Nivel3 n3, Respuesta_Rapida r
							 WHERE r.Respuesta_Nivel3 = n3.id 
							 AND FIND_IN_SET('".$this->cedente."',r.Id_Cedente)");
       	echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["respuesta_n3"]."'>"; echo utf8_encode($row["Respuesta_Rapida"]); echo "</option>";
        }
        echo "</select>";
	}
	public function nivel1($datos)
	{
		$db = new DB();
		$this->cedente=$datos['cedente'];

		if ($_SESSION['inbound'] == 0){ // no muestro los inbound
			$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('".$this->cedente."',Id_Cedente) AND Respuesta_N1 != 'INBOUND' ");
		}else{
			if (isset($datos['busqueda'])) { // si entra aca estoy en manual
				if ($datos['busqueda'] == 2){ // si entra aca estoy buscando por rut
					$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('".$this->cedente."',Id_Cedente) ");
				}else{
					$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('".$this->cedente."',Id_Cedente) AND Respuesta_N1 != 'INBOUND' ");
				}

			}else{
				// predictivo
				$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('".$this->cedente."',Id_Cedente) AND Respuesta_N1 != 'INBOUND' ");
			}
		}


		echo "<select class='selectpicker' id='seleccione_nivel1' name='seleccione_nivel1' data-live-search='true' data-width='100%'>";
       	echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["Id"]."'>"; echo utf8_encode($row["Respuesta_N1"]); echo "</option>";
        }
        echo "</select>";
	}
	public function nivel2($nivel2)
	{
		$db = new DB();
		$this->nivel2=$nivel2;
		echo "<select class='selectpicker' id='seleccione_nivel2' name='seleccione_nivel2' data-live-search='true' data-width='100%'>";
        $rows=$db->select("SELECT Id,Respuesta_N2 FROM Nivel2 WHERE Id_Nivel1 = $this->nivel2 ");
       	echo '<option value="0">Seleccione</option>';
       	if($rows){
	        foreach($rows as $row)
	        {
	        	echo "<option value='".$row["Id"]."'>"; echo utf8_encode($row["Respuesta_N2"]); echo  "</option>";

	        }
        }
        echo "</select>";

	}
	public function nivel3($nivel3)
	{
		$db = new DB();
		$this->nivel3=$nivel3;

		echo "<select class='selectpicker' id='seleccione_nivel3' name='seleccione_nivel3' data-live-search='true' data-width='100%'>";
        $rows=$db->select("SELECT id,Respuesta_N3 FROM Nivel3 WHERE $this->nivel3 = Id_Nivel2 ");
       	echo '<option value="0">Seleccione</option>';
        foreach($rows as $row)
        {
        	echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Respuesta_N3"]); echo "</option>";
        }
        echo "</select>";
	}
	public function nivel4($nivel_3_id,$cortar_valor,$rut)
	{
		$db = new DB();
		$this->nivel_3_id=$nivel_3_id;
		$this->cortar_valor=$cortar_valor;
		$cedente = $db->select("SELECT * FROM Cedente WHERE Id_Cedente = '" . $_SESSION["cedente"] . "'");
		$cedente = $cedente[0];
		$Nivel3 = $db->select("SELECT * FROM Nivel3 WHERE id = '" . $nivel_3_id. "'");
		if($Nivel3){
			$id_tipo = $Nivel3[0]['Id_TipoGestion'];
		}else{
			$id_tipo = '';
		}

		echo "<input type='hidden' id='tipo_gestion_final' name='tipo_gestion_final' value='".$id_tipo."'>";
		if($cedente["compromiso"] == 1 && $id_tipo == 5){
			echo '<div class="col-sm-4">';
				echo '<div class="form-group" id="fecha_compromiso_">';
					echo '<label class="control-label">Fecha Compromiso</label>';
					echo '<input id="fecha_compromiso" name="fecha_compromiso" placeholder="2017-07-17 19:00:00" class="form-control datetimepicker">';
				echo '</div>';
			echo '</div>';
			
			/*echo '<div class="col-sm-4">';
				echo '<div class="form-group"  id="monto_compromiso_">';
					echo '<label class="control-label">Monto Compromiso</label>';
					echo '<input type="number" class="selectpicker form-control SoloNumeros" id="monto_compromiso" name="monto_compromiso" data-live-search="true" data-width="100%">';
				echo '</div>';
			echo '</div>';
			$ocultar_agendamiento = 1;*/
		}
		if($cedente["facturas"] == 1){
			echo '<div class="col-sm-4">';
				echo '<div class="form-group" id="noperacion_">';
					echo '<label class="control-label">Operacion y/o Factura</label>';
					echo '<select class="form-control selectpicker" title="Seleccione" data-live-search="true" multiple id="facturas" name="facturas" data-live-search="true" data-width="100%">';
						switch($_SESSION['tipoSistema']){
							case "1":
								$FieldNOperacion = "Numero_Factura";
							break;
							default:
								$FieldNOperacion = "Numero_Operacion";
							break;
						}
						$query = "SELECT ".$FieldNOperacion." as NOperacion FROM Deuda WHERE Id_Cedente = '".$_SESSION["cedente"]."' AND Rut = '".$rut."' GROUP BY ".$FieldNOperacion." ORDER BY ".$FieldNOperacion;
						$Deudas = $db->select($query);
						if($Deudas){
							foreach($Deudas as $Deuda){
								if($Deuda["NOperacion"]){
									echo '<option value="'.$Deuda["NOperacion"].'">'.$Deuda["NOperacion"].'</option>';
								}
							}
						}
					echo '</select>';
				echo '</div>';
			echo '</div>';
		}
		if($cedente["compromiso"] == 1 && $id_tipo == 5){
			/*echo '<div class="col-sm-4">';
				echo '<div class="form-group" id="fecha_compromiso_">';
					echo '<label class="control-label">Fecha Compromiso</label>';
					echo '<input id="fecha_compromiso" name="fecha_compromiso" placeholder="2017-07-17 19:00:00" class="form-control datetimepicker">';
				echo '</div>';
			echo '</div>';*/
			
			echo '<div class="col-sm-4">';
				echo '<div class="form-group"  id="monto_compromiso_">';
					echo '<label class="control-label">Monto Compromiso</label>';
					echo '<input type="number" class="selectpicker form-control SoloNumeros" id="monto_compromiso" name="monto_compromiso" data-live-search="true" data-width="100%">';
				echo '</div>';
			echo '</div>';
			$ocultar_agendamiento = 1;
		}
		if($cedente["agendamiento"] == 1 && !isset($ocultar_agendamiento)){
			echo '<div class="col-sm-4">';
				echo '<div class="form-group" id="fecha_agendamiento_">';
					echo '<label class="control-label">Fecha de Agendamiento</label>';
					echo '<input id="fecha_agendamiento" name="fecha_agendamiento" placeholder="2017-07-17 19:00:00" class="form-control datetimepicker">';
				echo '</div>';
			echo '</div>';
		}
		/*
			if($cedente["facturas"] == 1){
				echo '<div class="col-sm-4">';
					echo '<div class="form-group" id="noperacion_">';
						echo '<label class="control-label">Operacion y/o Factura</label>';
						echo '<select class="form-control selectpicker" title="Seleccione" data-live-search="true" multiple id="facturas" name="facturas" data-live-search="true" data-width="100%">';
							switch($_SESSION['tipoSistema']){
								case "1":
									$FieldNOperacion = "Numero_Factura";
								break;
								default:
									$FieldNOperacion = "Numero_Operacion";
								break;
							}
							$query = "SELECT ".$FieldNOperacion." as NOperacion FROM Deuda WHERE Id_Cedente = '".$_SESSION["cedente"]."' AND Rut = '".$rut."' GROUP BY ".$FieldNOperacion." ORDER BY ".$FieldNOperacion;
							$Deudas = $db->select($query);
							if($Deudas){
								foreach($Deudas as $Deuda){
									if($Deuda["NOperacion"]){
										echo '<option value="'.$Deuda["NOperacion"].'">'.$Deuda["NOperacion"].'</option>';
									}
								}
							}
						echo '</select>';
					echo '</div>';
				echo '</div>';
			}
		*/			
		if($cedente["omnicanalidad"] == 1){
			
			// 	/******************************************************************
			// 	** CARGAR VALORES PARA LLENAR EL COMBO (SELECT) DE OMNICANALIDAD **
			// 	******************************************************************/
			$canales = $db->select("SELECT * FROM canales_omnicanalidad");
			echo '<div class="col-sm-12">';
				echo '<label class="control-label">Omnicanalidad</label>';
				$count = count($canales);
				foreach($canales as $canal){
					echo '<form class="form-inline">';
						echo '<div class="form-group col-sm-12">';
							echo '<div class="checkbox col-sm-2">';
								echo "<label><input type='checkbox' id='omnicanal".$canal['id']."' name='omnicanal[]' value='" . $canal['id'] . "'/>  " . utf8_encode($canal['canal']) . "</label>";
							echo '</div>';
							echo '<div class="form-group col-sm-4">';
								echo "<select class='form-control prioridad' id='prioridad_".$canal['id']."' name='prioridad_".$canal['id']."' disabled>";
									echo "<option value=''>Seleccione</option>";
									for ($j = 0 ; $j < $count; $j++ ){
										echo "<option value='". ($j+1) ."'>Prioridad ". ($j+1) ."</option>";
									}
								echo "</select>";
							echo '</div>';
						echo "</div>";
					echo "</form>";
				}
			echo '</div>';
		}

		echo '<div class="col-sm-12">';
			echo '<div class="form-group" id="comentario_">';
				echo '<label class="control-label">Observación</label>';
				echo '<textarea id="comentario" name="comentario" class="form-control"></textarea>';
			echo '</div>';
		echo '</div>';
		echo '<br>';
		$ConfCamposGestion = new ConfCamposGestion;
		$Campos = $ConfCamposGestion->getOrdenCampos($nivel_3_id);

		foreach($Campos as $Campo){
			$idCampo = $Campo["idCampo"];
			$Codigo = $Campo["Codigo"];
			$Titulo = $Campo["Titulo"];
			$ValorEjemplo = $Campo["ValorEjemplo"];
			$ValorPredeterminado = $Campo["ValorPredeterminado"];
			$Tipo = $Campo["Tipo"];
			$Dinamico = $Campo["Dinamico"];
			$Mandatorio = $Campo["Mandatorio"];
			$Deshabilitado = $Campo["Deshabilitado"];
			$Anchura = $Campo["Anchura"];
			$CampoDB = $Campo["CampoDB"];
			
			$Mandatorio = $Mandatorio == "1" ? " RequiredField" : "";
			$Deshabilitado = $Deshabilitado == "1" ? " disabled " : "";
			echo "<div class='col-md-".$Anchura."'>";
				echo "<div class='form-group'>";
					echo "<label class='control-label'>".$Titulo."</label>";
					switch($Tipo){
						case "1":
							echo "<input type='text' class='".$Mandatorio." DinamicField form-control' id='".$Codigo."' value='".$ValorPredeterminado."' placeholder='".$ValorEjemplo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>";
						break;
						case "2":
							echo "<textarea class='".$Mandatorio." DinamicField form-control' id='".$Codigo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>".$ValorPredeterminado."</textarea>";
						break;
						case "3":
							$ToReturn = "";
							$Opciones = $ConfCamposGestion->getOpcionesCampo($idCampo);
							foreach($Opciones as $Opcion){
								$Nombre = $Opcion["Nombre"];
								$Seleccionado = $Opcion["Seleccionado"];
								$Selected = $Seleccionado == "1" ? "selected" : "";
								$ToReturn .= "<option ".$Selected." value='".$Nombre."' >".$Nombre."</option>";
							}
							echo "<select class='".$Mandatorio." DinamicField selectpicker form-control' title='Seleccione' data-live-search='true' data-width='100%' id='".$Codigo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>".$ToReturn."</select>";
						break;
						case "4":
							$ToReturn = "";
							$Opciones = $ConfCamposGestion->getOpcionesCampo($idCampo);
							foreach($Opciones as $Opcion){
								$Nombre = $Opcion["Nombre"];
								$Seleccionado = $Opcion["Seleccionado"];
								$Selected = $Seleccionado == "1" ? "selected" : "";
								$ToReturn .= "<option ".$Selected." value='".$Nombre."' >".$Nombre."</option>";
							}
							echo "<select class='".$Mandatorio." DinamicField selectpicker form-control' title='Seleccione' data-live-search='true' data-width='100%' id='".$Codigo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."' multiple>".$ToReturn."</select>";
						break;
						case "5":
							echo "<input type='text' class='".$Mandatorio." DinamicField form-control datetimepicker' id='".$Codigo."' placeholder='".$ValorEjemplo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>";
						break;
					}
				echo "</div>";
			echo "</div>";
		}

		$qrn3= "SELECT Respuesta_Nivel3 as n3 FROM campos_gestion WHERE Titulo = 'Derivacion' ";
		$derivacion = $db->select($qrn3);
		//303,304,306,307,308,313
		if(strpos($derivacion[0]['n3'],$this->nivel_3_id) !== false){

			echo '<div class="col-sm-6" style="padding-left: 0 !important; padding-right: 0 !important;">';
				echo '<div class="col-sm-12">';
					echo '<div class="form-group">';
						echo '<label class="control-label">Elija un Archivo</label>';
						echo '<label for="file" class="control-label">Subir Archivo</label>';
						echo '<input type="file" id="file" name="file" accept="image/jpeg, image/jpg, image/png,application/pdf">';
					echo '</div>';
				echo '</div>';
			echo '</div>';

			echo '<div class="col-sm-6" style="padding-left: 0 !important; padding-right: 0 !important;">';
				echo '<div class="col-sm-12">';
					echo '<div class="form-group">';
						echo '<label class="control-label">Guardar Gestión</label>';
						echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		}else{

			echo '<div class="col-sm-12" style="padding-left: 0 !important; padding-right: 0 !important;">';
				echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
						echo '<label class="control-label">Guardar Gestión</label>';
						echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		}

	}

	public function datosNivel($idNivel,$nivel){
		$db = new DB();
		switch ($nivel) {
    		case 1:
        	$sql = "SELECT Respuesta_N1 FROM Nivel1 WHERE Id = '$idNivel'";
			break;
    		case 2:
        	$sql = "SELECT Respuesta_N2 FROM Nivel2 WHERE id = '$idNivel'";
			break;
    		case 3:
        	$sql = "SELECT Respuesta_N3, P1, P2, P3, P4, Id_TipoGestion, Ponderacion,Peso FROM Nivel3 WHERE id = '$idNivel'";
			break;
		}

		$row = $db->select($sql);
		return $row[0];
	}

	public function sumarSegundoFecha($fecha){
		$fecha = date($fecha);
		$fecha = strtotime($fecha) + 1;
		return date('Y-m-d H:i:s',$fecha);
	}

	public function colorFono($fono, $rut, $tipoGestion){
		$db = new DB();
		$sql = "SELECT f.color, c.prioridad, c.tipo_contacto FROM fono_cob as f, SIS_Categoria_Fonos as c WHERE f.formato_subtel = '".$fono."' AND f.Rut = '".$rut."' AND c.color = f.color";
		$result = $db -> select($sql);
		// prioridad actual del fono
		if($result){
			$prioridadFono = $result[0]["prioridad"];
		}else{
			$prioridadFono = 0;
		}
		$sqlNuevoContacto = "SELECT prioridad, color FROM SIS_Categoria_Fonos WHERE tipo_contacto = '".$tipoGestion."'";
		$resultContacto = $db -> select($sqlNuevoContacto);
		// prioridad del tipo gestion
		if($resultContacto){
			$prioridadGestion = $resultContacto[0]["prioridad"];
			$color = $resultContacto[0]['color'];
		}else{
			$prioridadGestion = 0;
			$color = '0';
		}
		
		if ($prioridadFono > $prioridadGestion){
			// cambio color
			$updateColorFono = "UPDATE fono_cob SET color = '".$color."' WHERE formato_subtel = '".$fono."' AND Rut = '".$rut."'";
			$db -> query($updateColorFono);
		}
	}

	public function insertar1($nivel1,$nivel2,$nivel3,$comentario,$rut,$fono_discado,$tipo_gestion,$cedente,$usuario_foco,$lista,$fechaCompromiso,$montoCompromiso,$tiempoLlamada,$NombreGrabacion,$asignacion,$origen,$facturas,$fechaAgendamiento,$Habla,$UrlGrabacion,$canales,$prioridades,$ArrayCampos, $file, $derivacion, $refacturar, $monto,$montoAgregado)
	{
		$Explode = explode("/",$UrlGrabacion);
		/*if(count($Explode) > 1){
			//http://192.168.1.9/Records/00003-Soporte/205/20170921/psantana/20170921-121247_993084924_205_psantana-all.wav
			$CedenteUrl = $Explode[5];
			$FechaUrl = $Explode[6];
			$UsuarioUrl = $Explode[7];
			$Prefix = "/var/www/html/Records/Tmp";
			$PrefixCedente = $Prefix."/".$CedenteUrl;
			$PrefixCedenteFecha = $Prefix."/".$CedenteUrl."/".$FechaUrl;
			$PrefixCedenteFechaUsuario = $Prefix."/".$CedenteUrl."/".$FechaUrl."/".$UsuarioUrl;
		
			if(file_exists($PrefixCedente)){
			//echo "No Hacer Nada";
			}
			else{
			shell_exec("mkdir $PrefixCedente");
			}
		
			if(file_exists($PrefixCedenteFecha)){
			//echo "No Hacer Nada";
			}
			else{
			shell_exec("mkdir $PrefixCedenteFecha");
			}
			if(file_exists($PrefixCedenteFechaUsuario)){
			//echo "No Hacer Nada";
			}
			else{
			shell_exec("mkdir $PrefixCedenteFechaUsuario");
			}
			shell_exec("wget '$UrlGrabacion' -P '$PrefixCedenteFechaUsuario'");
		}*/
		$file_url = "";
		if(isset($file) && !empty($file["file"]["type"])){
            $extension = explode("/",$file['file']['type']);

            $ext = $extension[1];

            $fileName = date("Ymdhis").'-'.$rut.'-'.$fono_discado.'.'.$ext;
            
            $dir = "/var/www/html/file/archivos/";
            //$dir = "../../file/archivos/";

            if(!file_exists($dir)){
                shell_exec("mkdir $dir");
                //mkdir($dir);
            }

            $destino = $dir.$fileName;
            $file_url = "http://disal.mibot.cl//file/archivos/".$fileName;

            move_uploaded_file($file['file']['tmp_name'], $destino);
		}
		
		$db = new DB();
        $lastID=0;
		$this->usuario_foco=$usuario_foco;
		$new_user = $this->usuario_foco;
		$this->nivel1=$nivel1;
		$this->nivel2=$nivel2;
		$this->nivel3=$nivel3;
		$this->comentario=$comentario;
		$this->rut=$rut;
		$this->fono_discado=$fono_discado;
		$this->tipo_gestion=$tipo_gestion;
		$this->cedente=$cedente;
		$this->lista= $lista == "" ? "0" : $lista;
		$this->hora_gestion = $this->sumarSegundoFecha(date("H:i:s"));
		$cedente = $db->select("SELECT * FROM Cedente WHERE Id_Cedente = '".$_SESSION["cedente"]."'");
		$cedente = $cedente[0];
		if($cedente['agendamiento'] == 1){
			if($fechaAgendamiento){
				$fechaAgenda = $fechaAgendamiento;
			}else{
				$fechaAgenda = "";
			}
		}else{
			$fechaAgenda = "";
		}
		if($cedente['compromiso'] == 1 && $this->tipo_gestion == 5){
			$fechaCom = date("Y-m-d",strtotime($fechaCompromiso));
			$CompromisoArray = explode(' ', $fechaCom);
			$this->fechaCompromiso = $CompromisoArray[0];
			//$this->horaCompromiso = $CompromisoArray[1];
			$this->montoCompromiso = $montoCompromiso;
			$fechaAgenda = "";
		}else{
			$this->fechaCompromiso = "";
			//$this->horaCompromiso = null;
			$this->montoCompromiso = 0;
			$fechaCom = "";
		}

		if($fechaAgenda != ""){
			$fechaRellamar = $fechaAgenda;
		}else{
			$fechaRellamar = $fechaCom;
		}

		/*if($Habla != ''){
			$Habla = str_replace("undefined", "", $Habla);
			$query = "INSERT INTO Transcripciones(Rut, Fecha, Hora,Transcripcion,Usuario) VALUES ('".$rut."',NOW(),NOW(),'".$Habla."','".$usuario_foco."')";
			$transcripcion = $db->query($query);
		}*/

		$rowNivel1 = $this->datosNivel($this->nivel1,1);
		$rowNivel2 = $this->datosNivel($this->nivel2,2);
		$rowNivel3 = $this->datosNivel($this->nivel3,3);

		/* $query = "SELECT id_gestion FROM gestion_ult_trimestre with(nolock) WHERE url_grabacion = '".$UrlGrabacion."'";
		$gestion = $db->select($query);
		if($gestion && $UrlGrabacion != ''){
			$id_gestion = $gestion[0]["id_gestion"];
		}else{
			$query = "SELECT IDENT_CURRENT('gestion_ult_trimestre') as id_gestion";
            $gestion = $db->select($query);
            if($gestion){
                $id_gestion = $gestion[0]["id_gestion"];
            }else{
                $id_gestion = 0;
            }
		} */
		$saldo_agregado = 0;
		
		if( ($montoCompromiso-$montoAgregado) != 0){
			$saldo_agregado = ($montoCompromiso-$montoAgregado);
		}
		if($cedente['facturas'] == 1){
			$Arrayfacturas = explode(",",$facturas);
			$arrayFacturasComp = explode(",",$facturas);
            
			foreach($Arrayfacturas as $numFactura){
				/*$query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,Ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,origen,factura,fechaAgendamiento,url_grabacion,file_url) VALUES ('".$this->nivel1."','".$this->nivel2."','".$this->nivel3."','".$this->comentario."',NOW(),'".$this->hora_gestion."','".$this->rut."',NOW(),'".$this->fono_discado."','".$this->lista."','".$_SESSION["MM_Username"]."','".$rowNivel3["Id_TipoGestion"]."','".$this->cedente."','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','".$this->fechaCompromiso."','".$this->montoCompromiso."','".$tiempoLlamada."','".$rowNivel3["Peso"]."','".$NombreGrabacion."','".$origen."','".$numFactura."','".$fechaAgenda."','".$UrlGrabacion."','".$file_url."')";*/
				$query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,Ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,origen,factura,fechaAgendamiento,url_grabacion,file_url) VALUES ('".$this->nivel1."','".$this->nivel2."','".$this->nivel3."','".$this->comentario."',NOW(),'".$this->hora_gestion."','".$this->rut."',NOW(),'".$this->fono_discado."','".$this->lista."','".$_SESSION["MM_Username"]."','".$rowNivel3["Id_TipoGestion"]."','".$this->cedente."','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','".$this->fechaCompromiso."','".$this->montoCompromiso."','".$tiempoLlamada."','".$rowNivel3["Peso"]."','".$NombreGrabacion."','".$origen."','".$numFactura."','".$fechaAgenda."','".$UrlGrabacion."','".$file_url."')";
				$id_gestion = $db->insert($query);
                if((int)$id_gestion>0){
                    $lastID = $id_gestion;
                }
				if ($this->tipo_gestion == 5 && $cedente['compromiso'] == 1){
					$query = "INSERT INTO Agendamiento_Compromiso(Rut, FechaCompromiso, MontoCompromiso, NumeroFactura, Id_Cedente, fechahora, id_gestion) VALUES ('".$this->rut."',".$fechaCom.",'".$this->montoCompromiso."','".$numFactura."','".$this->cedente."',NOW(),'".$id_gestion."')";
					$agendamiento = $db->query($query);
				}
			}

            if ($this->tipo_gestion == 5){
                foreach($arrayFacturasComp as $value){
                    $queryComp = "INSERT INTO tbl_gestiones_compromisos (id_gestion,numero_factura,saldo_agregado) VALUES ('".$lastID."','".$value."',(SELECT Saldo_ML FROM Deuda WHERE Rut = '".$this->rut."' AND Numero_Factura = '".$value."' AND Id_Cedente = '".$this->cedente."'))";
                    $db->insert($queryComp);
                }
            }
		}else{
			/*$query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,Ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,origen,factura,fechaAgendamiento,url_grabacion,file_url) VALUES ('".$this->nivel1."','".$this->nivel2."','".$this->nivel3."','".$this->comentario."',NOW(),'".$this->hora_gestion."','".$this->rut."',NOW(),'".$this->fono_discado."','".$this->lista."','".$_SESSION["MM_Username"]."','".$rowNivel3["Id_TipoGestion"]."','".$this->cedente."','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','".$this->fechaCompromiso."','".$this->montoCompromiso."','".$tiempoLlamada."','".$rowNivel3["Peso"]."','".$NombreGrabacion."','".$origen."','','".$fechaAgenda."','".$UrlGrabacion."','".$file_url."')";*/
			$query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,Ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,origen,factura,fechaAgendamiento,url_grabacion,file_url) VALUES ('".$this->nivel1."','".$this->nivel2."','".$this->nivel3."','".$this->comentario."',NOW(),'".$this->hora_gestion."','".$this->rut."',NOW(),'".$this->fono_discado."','".$this->lista."','".$_SESSION["MM_Username"]."','".$rowNivel3["Id_TipoGestion"]."','".$this->cedente."','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','".$this->fechaCompromiso."','".$this->montoCompromiso."','".$tiempoLlamada."','".$rowNivel3["Peso"]."','".$NombreGrabacion."','".$origen."','','".$fechaAgenda."','".$UrlGrabacion."','".$file_url."')";
			$id_gestion = $db->insert($query);
			if ($this->tipo_gestion == 5 && $cedente['compromiso'] == 1){
				$query = "INSERT INTO Agendamiento_Compromiso(Rut, FechaCompromiso, MontoCompromiso, NumeroFactura, Id_Cedente, fechahora, id_gestion) VALUES ('".$this->rut."','".$fechaCom."','".$this->montoCompromiso."','0','".$this->cedente."',NOW(),'".$id_gestion."')";
				$agendamiento = $db->query($query);
			}
		}
		
		if($refacturar=='Si'){
			$query = "INSERT INTO gestion_refacturada (id_gestion, monto) VALUES (".$id_gestion.",".$monto.")";
			$db->query($query);
		}
		
		//Inicio de Envio de correo
		if( $nivel3 == 303 || $nivel3 == 304 || $nivel3 == 307 || $nivel3 == 313 ){
			$query = "INSERT INTO cases(id_gestion, cerrado,supervisor,accion,accionado_por) VALUES ('".$id_gestion."',0, '".$derivacion."','Ejecutivo Deriva a Supervisor','".$_SESSION["MM_Username"]."')";
			$db->insert($query);
			$email = new Email();
			
			$email_list = "";
		
			if($derivacion != ''){
				$whereNombre="";
				$nombres = explode(',',$derivacion);
				$contador = count($nombres);
				for ($i=0; $i < $contador; $i++) { 
					if($i == 0){
						$whereNombre .= "WHERE nombre = '".$nombres[$i]."'";
					}else{
						$whereNombre .= "OR nombre = '".$nombres[$i]."'";
					}
				}
				$sqlGetEmails = "SELECT corrreo FROM opciones_campos_gestion $whereNombre";
				$rEmail = $db->select($sqlGetEmails);

				if(count($rEmail) > 0){
					$mail_sup = ($value['correo_supervisor'] != '' || $value['correo_supervisor']!= null) ? $value['correo_supervisor'].";" : '' ;
					foreach($rEmail as $value){
						$email_list .= $value['corrreo'].";".$mail_sup;
					}	
				}else{
					$email_list = "bzambrano@mibot.cl;";
				}

			}else{
				$email_list = "bzambrano@mibot.cl;";
			}

			$html = "
						<p>Estimados:</p>
						<p>El siguiente caso ha sido designado a su usuario.</p>
						<p>Favor gestionar según motivo del reclamo.</p>
					";
            $subject = "DERIVACIÓN FACTURA RECLAMADA // FOCO";
    
    
            $email->SendMailFile($html,$subject,$email_list); 
        }
		//Fin de Envio de correo

		if($canales !== ""){
			if($cedente['omnicanalidad'] == 1){
				$query = "INSERT INTO omnicanalidad (rut, id_gestion, canales, prioridades) VALUES ('".$this->rut."','".$id_gestion."','".$canales."','".$prioridades."')";
				$omnicanalidad = $db->query($query);
			}
		}
		if($origen == "1"){
			if(($rowNivel3["Id_TipoGestion"] == "1") || ($rowNivel3["Id_TipoGestion"] == "5")){
				$dbDiscador = new DB("discador");
				$SqlQueue = "SELECT Queue FROM Asterisk_Agentes WHERE Agente = 'SIP/".$_SESSION["anexo_foco"]."'";
				$Queue = $dbDiscador->select($SqlQueue);
				if(count($Queue) > 0){
					$Queue = $Queue[0]["Queue"];
					$SqlCola = "SELECT Asterisk_Discador_Cola.Cola AS Cola FROM Asterisk_All_Queues INNER JOIN Asterisk_Discador_Cola ON Asterisk_Discador_Cola.id = Asterisk_All_Queues.id_discador WHERE Asterisk_All_Queues.Queue = '".$Queue."'";
					$Cola = $db->select($SqlCola);
					if(count($Cola) > 0){
						$Cola = $Cola[0]["Cola"];
						$Table = "DR_".$Queue."_".$Cola;

						$SqlUpdateRut = "UPDATE ".$Table." SET llamado = '1' WHERE Rut = '".$this->rut."'";
						$UpdateRut = $dbDiscador->query($SqlUpdateRut);
					}
				}
			}
		}

		if($cedente['agendamiento'] == 1 && ($fechaCom == "") && ($fechaAgenda != "")){
			$query = "SELECT * FROM Agendamiento WHERE Rut = '".$this->rut."' AND Id_Cedente = '".$this->cedente."'";
			$Agendamiento = $db->select($query);
			if (count($Agendamiento) > 0){
				$updateAgendamiento = "UPDATE Agendamiento SET FechaAgenda = '".$fechaAgenda."', fechahora = NOW(), id_gestion = '".$id_gestion."' WHERE Rut = '".$this->rut."' AND Id_Cedente = '".$this->cedente."'";
				$db->query($updateAgendamiento);
			}else{
				$insertAgendamiento = "INSERT INTO Agendamiento(Rut, FechaAgenda, Id_Cedente, fechahora, id_gestion) VALUES ('".$this->rut."','".$fechaAgenda."','".$this->cedente."',NOW(),'".$id_gestion."')";
				$db->query($insertAgendamiento);
			}
		}
		
		$this->actualizaUltimaGestion($this->rut,$rowNivel3["Id_TipoGestion"],$this->comentario,$new_user,date("Y-m-d H:i:s"),$this->fono_discado,date("Y-m-d H:i:s"),"");

		if($origen != 1){
			$this->getActualizaEstadoAsignacion($asignacion,$rowNivel3["Id_TipoGestion"],$this->rut,date("Y-m-d H:i:s"),$fechaRellamar);
		}

		// $this->colorFono($this->fono_discado, $this->rut, $rowNivel3["Id_TipoGestion"]);

		if($origen == 1){
			$sqlCedente = "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$this->cedente."'";
			$resultCedente = $db -> select($sqlCedente);
			$cartera = $resultCedente[0]["Nombre_Cedente"];
			$anexo = "SIP/".$_SESSION['anexo_foco'];
			$sqlCantidad = "SELECT id, cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' AND cartera = '".$cartera."'";
			$resultCantidad = $db -> select($sqlCantidad);
			if (count($resultCantidad) > 0){
				$acumCantidad = 0;
				$idCantidad = $resultCantidad[0]["id"];
				$cantidad = $resultCantidad[0]["cantidad"];
				$acumCantidad = $cantidad + 1;
				$sqlUpdateCant = "UPDATE cantidadGestionesPredictivo SET cantidad = '".$acumCantidad."' WHERE id = '".$idCantidad."'";
				$db -> query($sqlUpdateCant);
			}else{
				$cantidad = 1;
				$sqlCantidadGestiones = "INSERT INTO cantidadGestionesPredictivo(anexo,cartera,cantidad) VALUES ('".$anexo."','".$cartera."','".$cantidad."')";
				$db -> query($sqlCantidadGestiones);
			}
		}

		if($ArrayCampos){
			$ConfCamposGestion = new ConfCamposGestion;
			$Campos = json_decode($ArrayCampos, true);
			foreach($Campos as $Campo){
				$Codigo = $Campo["Codigo"];
				$Valor = $Campo["Valor"];
				$ConfCamposGestion->RegistrarRespuestasCampos($Codigo,$Valor,$id_gestion);
			}
		}

		$SqlConfigInhabilitacionFonos = "select * from config_inhabilitacionFonos where Id_Cedente='".$this->cedente."'";
		$ConfigInhabilitacionFonos = $db->select($SqlConfigInhabilitacionFonos);
		if(count($ConfigInhabilitacionFonos) > 0){
			$ConfigInhabilitacionFonos = $ConfigInhabilitacionFonos[0];
			$Nivel = $ConfigInhabilitacionFonos["id_nivel3"];
			if($Nivel == $this->nivel3){
				$SqlUpdateFono = "update fono_cob set vigente = '0' where Rut = '".$this->rut."' and formato_subtel = '".$this->fono_discado."'";
				$UpdateFono = $db->query($SqlUpdateFono);
				$SqlUpdateFono = "update fono_cob_historico set vigente = '0' where Rut = '".$this->rut."' and formato_subtel = '".$this->fono_discado."'";
				$UpdateFono = $db->query($SqlUpdateFono);
			}
		}

		if($origen != 1){
			echo $this->getProgressAsignacion($asignacion);
		}
	}

	public function actualizaUltimaGestion($rut,$idTipoGestion,$observacion,$nombreEjecutivo,$fechaHora,$fono,$fechaGestion,$statusName){
		$db = new DB();
		$resultado = $db->select("SELECT * FROM Ultima_Gestion_Historica WHERE Rut = '$rut'");
		if (count($resultado) > 0){
			// update
			$db->query("UPDATE Ultima_Gestion_Historica SET fecha_gestion = '".$fechaGestion."', Id_TipoGestion = '".$idTipoGestion."', observacion = '".$observacion."', nombre_ejecutivo = '".$nombreEjecutivo."', fechahora = '".$fechaHora."', fono_discado = '".$fono."', status_name = '".$statusName."' WHERE Rut = '".$rut."'");
		}else{
			// insert
			$db->query("INSERT INTO Ultima_Gestion_Historica(Rut,fecha_gestion,Id_TipoGestion,observacion,nombre_ejecutivo,fechahora,fono_discado) VALUES ('$rut','$fechaGestion','$idTipoGestion','$observacion','$nombreEjecutivo','$fechaHora','$fono')");
		}
	}

	public function insertar3($nivel1,$rut,$fono_discado,$tipo_gestion,$cedente,$duracion_llamada,$usuario_foco,$lista,$tiempoLlamada,$NombreGrabacion,$asignacion,$origen,$UrlGrabacion)
	{
		/*$Explode = explode("/",$UrlGrabacion);
		if(count($Explode) > 1){
			//http://192.168.1.9/Records/00003-Soporte/205/20170921/psantana/20170921-121247_993084924_205_psantana-all.wav
			$CedenteUrl = $Explode[5];
			$FechaUrl = $Explode[6];
			$UsuarioUrl = $Explode[7];
			$Prefix = "/var/www/html/Records/Tmp";
			$PrefixCedente = $Prefix."/".$CedenteUrl;
			$PrefixCedenteFecha = $Prefix."/".$CedenteUrl."/".$FechaUrl;
			$PrefixCedenteFechaUsuario = $Prefix."/".$CedenteUrl."/".$FechaUrl."/".$UsuarioUrl;
		
			if(file_exists($PrefixCedente)){
			//echo "No Hacer Nada";
			}
			else{
			shell_exec("mkdir $PrefixCedente");
			}
		
			if(file_exists($PrefixCedenteFecha)){
			//echo "No Hacer Nada";
			}
			else{
			shell_exec("mkdir $PrefixCedenteFecha");
			}
			if(file_exists($PrefixCedenteFechaUsuario)){
			//echo "No Hacer Nada";
			}
			else{
			shell_exec("mkdir $PrefixCedenteFechaUsuario");
			}
			shell_exec("wget '$UrlGrabacion' -P '$PrefixCedenteFechaUsuario'");
		}*/
		
		$db = new DB();
		$this->usuario_foco=$usuario_foco;
		$new_user = $this->usuario_foco;
		$this->nivel1=$nivel1;
		$this->rut=$rut;
		$this->fono_discado=$fono_discado;
		$this->tipo_gestion=$tipo_gestion;
		$this->cedente=$cedente;
		$this->duracion_llamada=$duracion_llamada;
		$this->lista= $lista == "" ? "0" : $lista;

		$query = "	SELECT 
						n1.Id as respuesta_n1, 
						n2.id respuesta_n2, n3.id as respuesta_n3
					FROM 
						Nivel3 n3, 
						Nivel2 n2, 
						Nivel1 n1, 
						Respuesta_Rapida r
					WHERE 
						FIND_IN_SET('".$cedente."',r.Id_Cedente)
					AND 
						n3.Id_Nivel2 = n2.id 
					AND 
						n2.Id_Nivel1 = n1.Id 
					AND 
						r.Respuesta_Nivel3 = n3.id 
					AND 
						r.Respuesta_Nivel3 = '$this->nivel1'";
		$row = $db->select($query);
        $n1 = $row[0]["respuesta_n1"];
        $n2 = $row[0]["respuesta_n2"];
        $n3 = $row[0]["respuesta_n3"];

		$rowNivel1 = $this->datosNivel($n1,1);
		$rowNivel2 = $this->datosNivel($n2,2);
		$rowNivel3 = $this->datosNivel($n3,3);
		$this->hora_gestion = $this->sumarSegundoFecha(date("H:i:s"));

		$cedente = $db->select("SELECT * FROM Cedente WHERE Id_Cedente = '" . $this->cedente . "'");
		if($cedente[0]['facturas'] == 1){
	
			$FieldNOperacion = "";
			switch($_SESSION['tipoSistema']){
				case "1":
					$FieldNOperacion = "Numero_Factura";
				break;
				default:
					$FieldNOperacion = "Numero_Operacion";
				break;
			}
			$SqlDeudas = "SELECT DISTINCT ".$FieldNOperacion." as NOperacion FROM Deuda WHERE Id_Cedente = '".$this->cedente."' AND Rut = '".$this->rut."'";
			$Deudas = $db->select($SqlDeudas);

			foreach($Deudas as $DeudaOperacion){
				$factura = $DeudaOperacion["NOperacion"];
				$query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,duracion,origen,n1,n2,n3,p1,p2,p3,p4,Ponderacion,nombre_grabacion,url_grabacion,factura) VALUES ('$n1','$n2','$n3',NOW(),'$this->hora_gestion','$this->rut',NOW(),'$this->fono_discado','$this->lista','".$_SESSION["MM_Username"]."','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$tiempoLlamada','$origen','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$NombreGrabacion','$UrlGrabacion','$factura')";
				$db->query($query);
			}
		}else{
			$query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,duracion,origen,n1,n2,n3,p1,p2,p3,p4,Ponderacion,nombre_grabacion,url_grabacion,factura) VALUES ('$n1','$n2','$n3',NOW(),'$this->hora_gestion','$this->rut',NOW(),'$this->fono_discado','$this->lista','".$_SESSION["MM_Username"]."','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$tiempoLlamada','$origen','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$NombreGrabacion','$UrlGrabacion','')";
			$db->query($query);
		}

		if($origen == "1"){
			if(($rowNivel3["Id_TipoGestion"] == "1") || ($rowNivel3["Id_TipoGestion"] == "5")){
				$dbDiscador = new DB("discador");
				$SqlQueue = "SELECT Queue FROM Asterisk_Agentes WHERE Agente = 'SIP/".$_SESSION["anexo_foco"]."'";
				$Queue = $dbDiscador->select($SqlQueue);
				if(count($Queue) > 0){
					$Queue = $Queue[0]["Queue"];
					$SqlCola = "SELECT Asterisk_Discador_Cola.Cola as Cola FROM Asterisk_All_Queues INNER JOIN Asterisk_Discador_Cola ON Asterisk_Discador_Cola.id = Asterisk_All_Queues.id_discador WHERE Asterisk_All_Queues.Queue = '".$Queue."'";
					$Cola = $db->select($SqlCola);
					if(count($Cola) > 0){
						$Cola = $Cola[0]["Cola"];
						$Table = "DR_".$Queue."_".$Cola;

						$SqlUpdateRut = "UPDATE ".$Table." SET llamado = '1' WHERE Rut = '".$this->rut."'";
						$UpdateRut = $dbDiscador->query($SqlUpdateRut);
					}
				}
			}
		}

		$this->actualizaUltimaGestion($this->rut,$rowNivel3["Id_TipoGestion"],'',$new_user,date("Y-m-d H:i:s"),$this->fono_discado,date("Y-m-d H:i:s"),"");
		$this->getActualizaEstadoAsignacion($asignacion,$rowNivel3["Id_TipoGestion"],$this->rut,date("Y-m-d H:i:s"));

		// $this->colorFono($this->fono_discado, $this->rut, $rowNivel3["Id_TipoGestion"]);

		if($origen == 1){
			$sqlCedente = "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$this->cedente."'";
			$resultCedente = $db -> select($sqlCedente);
			$cartera = $resultCedente[0]["Nombre_Cedente"];
			$anexo = "SIP/".$_SESSION['anexo_foco'];
			$sqlCantidad = "SELECT id, cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' AND cartera = '".$cartera."'";
			$resultCantidad = $db -> select($sqlCantidad);
			if (count($resultCantidad) > 0){
				$acumCantidad = 0;
				$idCantidad = $resultCantidad[0]["id"];
				$cantidad = $resultCantidad[0]["cantidad"];
				$acumCantidad = $cantidad + 1;
				$sqlUpdateCant = "UPDATE cantidadGestionesPredictivo SET cantidad = '".$acumCantidad."' WHERE id = '".$idCantidad."'";
				$db->query($sqlUpdateCant);
			}else{
				$cantidad = 1;
				$sqlCantidadGestiones = "INSERT INTO cantidadGestionesPredictivo(anexo,cartera,cantidad) VALUES ('".$anexo."','".$cartera."','".$cantidad."')";
				$db->query($sqlCantidadGestiones);
			}
		}
		
		echo $this->getProgressAsignacion($asignacion);
	}
	public function limpiarSeleccion()
	{
		$db = new DB();
		$db->query("UPDATE Deuda SET Marca_Factura=0 WHERE Marca_Factura=1");
		$db->query("UPDATE Mail SET Marca=0 WHERE Marca=1");
	}

	public function mostrarScript($idCedente){
		$db = new Db();
		$sql = "SELECT script FROM script_cedente WHERE id_cedente = '".$idCedente."'";
		$resultado = $db->select($sql);
		if($resultado){
			$query = "SHOW COLUMNS FROM Deuda LIKE 'Carrera'";
			$Educational = $db->select($query);
			if(!$Educational){
				$script = html_entity_decode($resultado[0]['script']);
			}else{
				$script = $resultado[0]['script'];
			}
			$script = str_ireplace("[Nombre]", "<b><span class='nombre_cliente'></span></b>", $script);
		}else{
			$script = '';
		}
		return $script;
	}

	public function mostrarScriptCompleto($idCedente){
		$db = new Db();
		$sql = "SELECT script FROM script_completo_cedente WHERE id_cedente = '".$idCedente."'";
		$resultado = $db->select($sql);
		if($resultado){
			$script = html_entity_decode($resultado[0]['script']);
			$script = str_ireplace("[Nombre]", "<b><span class='nombre_cliente'></span></b>", $script);
		}else{
			$script = '';
		}
		return $script;
	}

	public function mostrarPoliticas($idCedente){
		$db = new Db();
		$sql = "SELECT politica FROM politica_cedente WHERE id_cedente = '".$idCedente."'";
		$resultado = $db->select($sql);
		if($resultado){
			$politica = html_entity_decode($resultado[0]['politica']);
		}else{
			$politica = '';
		}
		return $politica;
	}

	public function mostrarMediosPago($idCedente){
		$db = new Db();
		$sql = "SELECT medio_pago FROM medio_pago_cedente WHERE id_cedente = '".$idCedente."'";
		$resultado = $db->select($sql);
		if($resultado){
			$medio_pago = html_entity_decode($resultado[0]['medio_pago']);
		}else{
			$medio_pago = '';
		}
		return $medio_pago;
	}

	public function mostrarDeudas($rut,$cedente){
		$idTableDeuda = 2;
		$db = new DB();
		$ToReturn = array();
		$ToReturn["result"] = false;
		$ToReturn["Table"] = "";

		$Sql = "SELECT
					SIS_Columnas_Estrategias.columna as columna,SIS_Columnas_Estrategias.tipo_dato,SIS_Columnas_Estrategias.suma,Columnas_Asignacion_CRM.destacar
				FROM
					SIS_Columnas_Estrategias
				INNER JOIN 
					Columnas_Asignacion_CRM 
				ON 
					Columnas_Asignacion_CRM.columna = SIS_Columnas_Estrategias.columna
				WHERE
					Columnas_Asignacion_CRM.Id_Cedente ='".$cedente."' 
				AND 
					SIS_Columnas_Estrategias.id_tabla = '".$idTableDeuda."'
				ORDER BY
					Columnas_Asignacion_CRM.prioridad";
		$columnas = $db -> select($Sql);
		if($columnas){
	    	// total columnas por cedente $columnasDeudaTodas
	  	 	$ArrayColumnas = array();
			$ArrayColumnasTmp = array();
			$ArrayColumnasSuma = array(); // Array que contiene todas las columnas que muestran el total (Suma) al final
			$resultado = array();
			foreach($columnas as $columna){
				array_push($ArrayColumnas,$columna["columna"]."|".$columna["tipo_dato"]."|".$columna["destacar"]);
				array_push($ArrayColumnasTmp,$columna["columna"]);
				if ($columna["suma"] == 1){
					$Array = array();
					$Array[$columna["columna"]] = 0;
					$ArrayColumnasSuma = array_merge($ArrayColumnasSuma,$Array);
				}
			}
			$columnasDeudaTodas = implode(",",$ArrayColumnasTmp);
	    	$SqlDeuda = "SELECT ".$columnasDeudaTodas." FROM Deuda WHERE Rut ='".$rut."' AND Id_Cedente = '".$cedente."'";
			$deudas = $db -> select($SqlDeuda);
	    	// total columnas con valores $arrayColumnasConData

			$arrayColumnasConData = array();
			$arrayColumnasConDataTmp = array();
			foreach($ArrayColumnas as $Columna){
				$Col = explode("|",$Columna);
	  			if($this->getCamposMostrar($deudas,$Col[0])){
	    			array_push($arrayColumnasConData,$Columna);
					array_push($arrayColumnasConDataTmp,$Col[0]);
	  			}
			}

			$columnasDeudaFinal = implode(",",$arrayColumnasConDataTmp);
			if(count($arrayColumnasConDataTmp) > 0){
				$ToReturn["result"] = true;
			}
	 		$ToReturn["Table"] .= '<div class="table-responsive">';
				$ToReturn["Table"] .= '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
					$ToReturn["Table"] .= '<thead>';
						$ToReturn["Table"] .= '<tr>';
						foreach($arrayColumnasConDataTmp as $columna){
							$ToReturn["Table"] .= '<th>'.$columna.'</th>';
						}
						if($_SESSION["tipoSistema"] == "1"){
							$ToReturn["Table"] .= '<th>Fecha_UG</th>';
							$ToReturn["Table"] .= '<th>Hora_UG</th>';
							$ToReturn["Table"] .= '<th>Teléfono_UG</th>';
							$ToReturn["Table"] .= '<th>Gestión_UG</th>';
							$ToReturn["Table"] .= '<th>Fecha_MG</th>';
							$ToReturn["Table"] .= '<th>Hora_MG</th>';
							$ToReturn["Table"] .= '<th>Teléfono_MG</th>';
							$ToReturn["Table"] .= '<th>Gestión_MG</th>';
						}
						$ToReturn["Table"] .= '</tr>';
					$ToReturn["Table"] .= '</thead>';

					$SqlDeuda = "SELECT ".$columnasDeudaFinal." FROM Deuda WHERE Rut ='".$rut."' AND Id_Cedente = '".$cedente."' AND Deuda > 0";
					$deudas = $db -> select($SqlDeuda);
					$ContadorDeColumnas = 0; // 10296535 Deuda Saldo_Insoluto
					$acumMontoMora = 0;
					$acumSaldoInsoluto = 0;
					$camposSuma = 0; // 0 me indica que no tenemos totales en al menos un campo
					if(count($arrayColumnasConDataTmp) <= 0){
						$deudas = [];
					}
					if($deudas){
						foreach($deudas as $deuda){
							$ToReturn["Table"] .= '<tr>';
							$FacturaActual = "";
							for($i=0;$i<=count($arrayColumnasConData) - 1;$i++){
								$CheckNumeroFactura = "";
								$ColumnaArray = explode("|",$arrayColumnasConData[$i]);
								$Columna = $ColumnaArray[0];
								$Destacar = $ColumnaArray[2];
								if($Destacar == 1){
									$Color = "style='background-color:#ffff00'";
								}else{
									$Color = "";
								}
								$Value = $deuda[$Columna];
								// saco el total de todos los campos que se suman (acumulador)
								foreach ($ArrayColumnasSuma as $clave => $valor){
									if ($Columna == $clave){
										$camposSuma = 1;
										$ArrayColumnasSuma[$clave] = $ArrayColumnasSuma[$clave] + $Value;
									}
								}
								if (is_numeric($Value)){
									$decimales = strrpos($Value, '.');
									$monto = floatval($Value);
									
									if (is_numeric($decimales)){
										//es decimal;
										$decimales = strrpos($monto, '.00');
										if (is_numeric($decimales)){
											$monto = $ColumnaArray[1] == "0" ? number_format($monto, 0, '', '.') : $monto;
										}else{
											$monto = $ColumnaArray[1] == "0" ? number_format($monto, 2, ',', '.') : $monto;
										}
										$Value = $monto;
									}else{
										$Value = $ColumnaArray[1] == "0" ? number_format(floatval($Value), 0, '', '.') : utf8_encode($Value);
									}
								
									switch($Columna){
										case "Deuda":
											$Value = str_replace(".","",$Value);
											$Value = floatval($Value);
											$acumMontoMora = $acumMontoMora + $Value;
											$Value = number_format($Value,0,"",".");
										break;
										case "Saldo_Insoluto":
											$Value = str_replace(".","",$Value);
											$Value = floatval($Value);
											$acumSaldoInsoluto = $acumSaldoInsoluto + $Value; // number_format($número, 2, ',', ' ');
											$Value = number_format($Value,0,"",".");
										break;
										case "Numero_Factura":
											if($_SESSION['tipoSistema'] == "1"){
												$FacturaActual = $Value;
												//$File = "facturas/".$_SESSION['mandante']."/".$_SESSION['cedente']."/".$rut."/".$Value.".pdf";
												if($_SESSION['cedente']!=4){
													$File = "facturas/96824110/".str_replace(".", "", $Value).".pdf";
												}else{
													$File = "facturas/78829890/".str_replace(".", "", $Value).".pdf";
												}
												
												
												$Color = "";
												$Disabled = "";
												if(!file_exists("../../".$File)){
													$Color = "color: #CCCCCC;";
													$Disabled = "Disabled";
												}
												$CheckNumeroFactura = "<label class='form-checkbox form-normal form-primary inputCheckFactura' style='margin-left: 10px;'><input type='checkbox'></label><i class='fa fa-download DownloadFactura ".$Disabled."' style='float: right;font-size: 20px;cursor: pointer;".$Color."' href='".$File."' number='".$Value."'></i>";
											}
										break;
									}
								}else{
									$Value = utf8_encode($Value);
								}
								/* if ($Columna == 'Deuda'){
									$acumMontoMora = $acumMontoMora + $Value;
								}
								if ($Columna == 'Saldo_Insoluto'){
									$acumSaldoInsoluto = $acumSaldoInsoluto + $Value; // number_format($número, 2, ',', ' ');
								} */

								//$Value = $ColumnaArray[1] == "0" ? number_format($Value, 0, '', '.') : $Value;
								if ($Columna == "Numero_Factura"){
									$ToReturn["Table"] .= '<td '.$Color.' id="' . $Value . '"><span class="verFactura" style="cursor: pointer;">'.str_replace(".", "", $Value).'</span>'.$CheckNumeroFactura.'</td>';
								}else{
									$ToReturn["Table"] .= '<td '.$Color.'><span>'.$Value."</span>".$CheckNumeroFactura.'</td>';
								}						
								$ContadorDeColumnas++;
							}
							if($_SESSION["tipoSistema"] == "1"){
								$lalanhdsa = $FacturaActual;
								$SqlUltimaGestion = "SELECT * FROM Ultima_Gestion_Historica INNER JOIN Tipo_Contacto ON (Tipo_Contacto.Id_TipoContacto = Ultima_Gestion_Historica.Id_TipoGestion) WHERE Rut ='".$rut."' AND factura='".$FacturaActual."' LIMIT 1";
								$UltimaGestion = $db -> select($SqlUltimaGestion);

								if (count($UltimaGestion) > 0){
									foreach($UltimaGestion as $gestion){
										$Fecha = strtotime($gestion['fechahora']);

										$ToReturn["Table"] .= '<td>'.date("d-m-Y",$Fecha).'</td>';
										$ToReturn["Table"] .= '<td>'.date("H:i:s",$Fecha).'</td>';
										$ToReturn["Table"] .= '<td>'.$gestion['fono_discado'].'</td>';
										$ToReturn["Table"] .= '<td>'.$gestion['Nombre'].'</td>';
									}
								}else{
									$ToReturn["Table"] .= '<td></td>';
									$ToReturn["Table"] .= '<td></td>';
									$ToReturn["Table"] .= '<td></td>';
									$ToReturn["Table"] .= '<td></td>';
								}

								$SqlMejorGestion = "SELECT * FROM Mejor_Gestion_Historica INNER JOIN Tipo_Contacto ON (Tipo_Contacto.Id_TipoContacto = Mejor_Gestion_Historica.Id_TipoGestion) WHERE Rut ='".$rut."' AND factura='".$FacturaActual."' LIMIT 1";
								$MejorGestion = $db-> select($SqlMejorGestion);

								if (count($MejorGestion) > 0){
									foreach($MejorGestion as $gestion){
										$Fecha = strtotime($gestion['fechahora']);

										$ToReturn["Table"] .= '<td>'.date("d-m-Y",$Fecha).'</td>';
										$ToReturn["Table"] .= '<td>'.date("H:i:s",$Fecha).'</td>';
										$ToReturn["Table"] .= '<td>'.$gestion['fono_discado'].'</td>';
										$ToReturn["Table"] .= '<td>'.$gestion['Nombre'].'</td>';
									}
								}else{
									$ToReturn["Table"] .= '<td></td>';
									$ToReturn["Table"] .= '<td></td>';
									$ToReturn["Table"] .= '<td></td>';
									$ToReturn["Table"] .= '<td></td>';
								}
							}
							$ToReturn["Table"] .= '</tr>';
						}
					}
					// Si tengo columnas Suma (Muestra totales)
					if ($camposSuma == 1){
						$ToReturn["Table"] .= '<tfoot>';
						$ToReturn["Table"] .= '<tr style="background-color:#CCFFFF">';
						foreach($arrayColumnasConDataTmp as $columna)
						{
							$Value = "";
							foreach ($ArrayColumnasSuma as $clave => $valor){
								if ($columna == $clave){
									if($valor == 0){
										$Value = "";
									}else{
										$decimales = strrpos($valor, '.');
										if (is_numeric($decimales)){
											//es decimal;
											$decimales = strrpos($valor, '.00');
											if (is_numeric($decimales)){
												$Value = $ColumnaArray[1] == "0" ? number_format($valor, 0, '', '.') : $Value;
											}else{
												$Value = $ColumnaArray[1] == "0" ? number_format($valor, 2, ',', '.') : $Value;
											}
										}else{
											$Value = number_format($valor, 0, '', '.');
										}
									}
								}
							}

							$ToReturn["Table"] .= '<th>'.$Value.'</th>';
						}
						$ToReturn["Table"] .= '</tr>';
						$ToReturn["Table"] .= '</tfoot>';
					}
				$ToReturn["Table"] .= '</table>';
			$ToReturn["Table"] .= '</div>';
		}

		return $ToReturn;
	}
	public function mostrarDeudasPredictivo($rut,$cedente,$Queue){
		$idTableDeuda = 2;
		$db = new DB();
		$ToReturn = array();
		$ToReturn["result"] = false;
		$ToReturn["Table"] = "";
		$dbDiscador = new DB("discador");
		$Sql = "SELECT * FROM ".$Queue."_Columnas_Asignacion_CRM ORDER BY prioridad";
		$columnas = $dbDiscador -> select($Sql);
		if($columnas){
			// total columnas por cedente $columnasDeudaTodas
			$ArrayColumnas = array();
			$ArrayColumnasTmp = array();
			$ArrayColumnasSuma = array(); // Array que contiene todas las columnas que muestran el total (Suma) al final
			$resultado = array();
			foreach($columnas as $columna){
				array_push($ArrayColumnas,$columna["columna"]."|".$columna["tipo_dato"]."|".$columna["destacar"]);
				array_push($ArrayColumnasTmp,$columna["columna"]);
				if ($columna["suma"] == 1){
					$Array = array();
					$Array[$columna["columna"]] = 0;
					$ArrayColumnasSuma = array_merge($ArrayColumnasSuma,$Array);
				}
			}
			
			$columnasDeudaTodas = implode(",",$ArrayColumnasTmp);
			$SqlDeuda = "SELECT ".$columnasDeudaTodas." FROM ".$Queue."_Deuda WHERE Rut ='".$rut."' AND Id_Cedente = '".$cedente."'";
			$deudas = $dbDiscador -> select($SqlDeuda);
			// total columnas con valores $arrayColumnasConData

			$arrayColumnasConData = array();
			$arrayColumnasConDataTmp = array();
			foreach($ArrayColumnas as $Columna){
				$Col = explode("|",$Columna);
				if($this->getCamposMostrar($deudas,$Col[0])){
					array_push($arrayColumnasConData,$Columna);
					array_push($arrayColumnasConDataTmp,$Col[0]);
				}
			}

			$columnasDeudaFinal = implode(",",$arrayColumnasConDataTmp);
			if(count($arrayColumnasConDataTmp) > 0){
				$ToReturn["result"] = true;
			}
			$ToReturn["Table"] .= '<div class="table-responsive">';
				$ToReturn["Table"] .= '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
					$ToReturn["Table"] .= '<thead>';
						$ToReturn["Table"] .= '<tr>';
						foreach($arrayColumnasConDataTmp as $columna){
							$ToReturn["Table"] .= '<th>'.$columna.'</th>';
						}
						if($_SESSION["tipoSistema"] == "1"){
							$ToReturn["Table"] .= '<th>Fecha_UG</th>';
							$ToReturn["Table"] .= '<th>Hora_UG</th>';
							$ToReturn["Table"] .= '<th>Teléfono_UG</th>';
							$ToReturn["Table"] .= '<th>Gestión_UG</th>';
							$ToReturn["Table"] .= '<th>Fecha_MG</th>';
							$ToReturn["Table"] .= '<th>Hora_MG</th>';
							$ToReturn["Table"] .= '<th>Teléfono_MG</th>';
							$ToReturn["Table"] .= '<th>Gestión_MG</th>';
						}
						$ToReturn["Table"] .= '</tr>';
					$ToReturn["Table"] .= '</thead>';

					$SqlDeuda = "SELECT ".$columnasDeudaFinal." FROM ".$Queue."_Deuda WHERE Rut ='".$rut."' AND Id_Cedente = '".$cedente."' AND Deuda > 0";
					$deudas = $dbDiscador -> select($SqlDeuda);
					$ContadorDeColumnas = 0; // 10296535 Deuda Saldo_Insoluto
					$acumMontoMora = 0;
					$acumSaldoInsoluto = 0;
					$camposSuma = 0; // 0 me indica que no tenemos totales en al menos un campo
					if(count($arrayColumnasConDataTmp) <= 0){
						$deudas = [];
					}
					if($deudas){
						foreach($deudas as $deuda){
							$ToReturn["Table"] .= '<tr>';
							$FacturaActual = "";
							for($i=0;$i<=count($arrayColumnasConData) - 1;$i++){
								$CheckNumeroFactura = "";
								$ColumnaArray = explode("|",$arrayColumnasConData[$i]);
								$Columna = $ColumnaArray[0];
								$Destacar = $ColumnaArray[2];
								if($Destacar == 1){
									$Color = "style='background-color:#ffff00'";
								}else{
									$Color = "";
								}
								$Value = $deuda[$Columna];
								// saco el total de todos los campos que se suman (acumulador)
								foreach ($ArrayColumnasSuma as $clave => $valor){
									if ($Columna == $clave){
										$camposSuma = 1;
										$ArrayColumnasSuma[$clave] = $ArrayColumnasSuma[$clave] + $Value;
									}
								}
								if (is_numeric($Value)){
									$decimales = strrpos($Value, '.');
									$monto = floatval($Value);
									
									if (is_numeric($decimales)){
										//es decimal;
										$decimales = strrpos($monto, '.00');
										if (is_numeric($decimales)){
											$monto = $ColumnaArray[1] == "0" ? number_format($monto, 0, '', '.') : $monto;
										}else{
											$monto = $ColumnaArray[1] == "0" ? number_format($monto, 2, ',', '.') : $monto;
										}
										$Value = $monto;
									}else{
										$Value = $ColumnaArray[1] == "0" ? number_format(floatval($Value), 0, '', '.') : utf8_encode($Value);
									}
								
									switch($Columna){
										case "Deuda":
											$Value = str_replace(".","",$Value);
											$Value = floatval($Value);
											$acumMontoMora = $acumMontoMora + $Value;
											$Value = number_format($Value,0,"",".");
										break;
										case "Saldo_Insoluto":
											$Value = str_replace(".","",$Value);
											$Value = floatval($Value);
											$acumSaldoInsoluto = $acumSaldoInsoluto + $Value; // number_format($número, 2, ',', ' ');
											$Value = number_format($Value,0,"",".");
										break;
										case "Numero_Factura":
											if($_SESSION['tipoSistema'] == "1"){
												$FacturaActual = $Value;
												$File = "facturas/".$_SESSION['mandante']."/".$_SESSION['cedente']."/".$rut."/".$Value.".pdf";
												$Color = "";
												$Disabled = "";
												if(!file_exists("../../".$File)){
													$Color = "color: #CCCCCC;";
													$Disabled = "Disabled";
												}
												$CheckNumeroFactura = "<label class='form-checkbox form-normal form-primary inputCheckFactura' style='margin-left: 10px;'><input type='checkbox'></label><i class='fa fa-download DownloadFactura ".$Disabled."' style='float: right;font-size: 20px;cursor: pointer;".$Color."' href='".$File."' number='".$Value."'></i>";
											}
										break;
									}
								}else{
									$Value = utf8_encode($Value);
								}
								/* if ($Columna == 'Deuda'){
									$acumMontoMora = $acumMontoMora + $Value;
								}
								if ($Columna == 'Saldo_Insoluto'){
									$acumSaldoInsoluto = $acumSaldoInsoluto + $Value; // number_format($número, 2, ',', ' ');
								} */

								//$Value = $ColumnaArray[1] == "0" ? number_format($Value, 0, '', '.') : $Value;
								if ($Columna == "Numero_Factura"){
									$ToReturn["Table"] .= '<td '.$Color.' id="' . $Value . '"><span class="verFactura" style="cursor: pointer;">'.str_replace(".", "", $Value).'</span>'.$CheckNumeroFactura.'</td>';
								}else{
									$ToReturn["Table"] .= '<td '.$Color.'><span>'.$Value."</span>".$CheckNumeroFactura.'</td>';
								}						
								$ContadorDeColumnas++;
							}
							if($_SESSION["tipoSistema"] == "1"){
									$lalanhdsa = $FacturaActual;
									$SqlUltimaGestion = "SELECT * FROM Ultima_Gestion_Historica INNER JOIN Tipo_Contacto ON (Tipo_Contacto.Id_TipoContacto = Ultima_Gestion_Historica.Id_TipoGestion) WHERE Rut ='".$rut."' AND factura='".$FacturaActual."' LIMIT 1";
									$UltimaGestion = $db -> select($SqlUltimaGestion);

									if (count($UltimaGestion) > 0){
										foreach($UltimaGestion as $gestion){
											$Fecha = strtotime($gestion['fechahora']);

											$ToReturn["Table"] .= '<td>'.date("d-m-Y",$Fecha).'</td>';
											$ToReturn["Table"] .= '<td>'.date("H:i:s",$Fecha).'</td>';
											$ToReturn["Table"] .= '<td>'.$gestion['fono_discado'].'</td>';
											$ToReturn["Table"] .= '<td>'.$gestion['Nombre'].'</td>';
										}
									}else{
										$ToReturn["Table"] .= '<td></td>';
										$ToReturn["Table"] .= '<td></td>';
										$ToReturn["Table"] .= '<td></td>';
										$ToReturn["Table"] .= '<td></td>';
									}

									$SqlMejorGestion = "SELECT * FROM Mejor_Gestion_Historica INNER JOIN Tipo_Contacto ON (Tipo_Contacto.Id_TipoContacto = Mejor_Gestion_Historica.Id_TipoGestion) WHERE Rut ='".$rut."' AND factura='".$FacturaActual."' LIMIT 1";
									$MejorGestion = $db-> select($SqlMejorGestion);

									if (count($MejorGestion) > 0){
										foreach($MejorGestion as $gestion){
											$Fecha = strtotime($gestion['fechahora']);

											$ToReturn["Table"] .= '<td>'.date("d-m-Y",$Fecha).'</td>';
											$ToReturn["Table"] .= '<td>'.date("H:i:s",$Fecha).'</td>';
											$ToReturn["Table"] .= '<td>'.$gestion['fono_discado'].'</td>';
											$ToReturn["Table"] .= '<td>'.$gestion['Nombre'].'</td>';
										}
									}else{
										$ToReturn["Table"] .= '<td></td>';
										$ToReturn["Table"] .= '<td></td>';
										$ToReturn["Table"] .= '<td></td>';
										$ToReturn["Table"] .= '<td></td>';
									}
								}
							$ToReturn["Table"] .= '</tr>';
						}
					}
					// Si tengo columnas Suma (Muestra totales)
					if ($camposSuma == 1){
						$ToReturn["Table"] .= '<tfoot>';
						$ToReturn["Table"] .= '<tr style="background-color:#CCFFFF">';
						foreach($arrayColumnasConDataTmp as $columna)
						{
							$Value = "";
							foreach ($ArrayColumnasSuma as $clave => $valor){
								if ($columna == $clave){
									if($valor == 0){
										$Value = "";
									}else{
										$decimales = strrpos($valor, '.');
										if (is_numeric($decimales)){
											//es decimal;
											$decimales = strrpos($valor, '.00');
											if (is_numeric($decimales)){
												$Value = $ColumnaArray[1] == "0" ? number_format($valor, 0, '', '.') : $Value;
											}else{
												$Value = $ColumnaArray[1] == "0" ? number_format($valor, 2, ',', '.') : $Value;
											}
										}else{
											$Value = number_format($valor, 0, '', '.');
										}
									}
								}
							}

							$ToReturn["Table"] .= '<th>'.$Value.'</th>';
						}
						$ToReturn["Table"] .= '</tr>';
						$ToReturn["Table"] .= '</tfoot>';
					}
				$ToReturn["Table"] .= '</table>';
			$ToReturn["Table"] .= '</div>';
		}

		return $ToReturn;
	}
	function mostrarUltimaGestionPredictivo($Rut,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$SqlUltimaGestion = "SELECT * FROM ".$Queue."_Ultima_Gestion_Historica WHERE Rut='".$Rut."' ORDER BY fechahora DESC";
		$UltimaGestion = $dbDiscador->select($SqlUltimaGestion);
		echo "<div class='table-responsive'>";
			echo "<table class='table table-striped table-bordered' cellspacing='0' width='100%'>";
				echo '<thead>';
					echo '<tr>';
						echo '<th>Fono</th>';
						echo '<th>Fecha</th>';
						echo '<th>Gerstión</th>';
						echo '<th>Ejecutivo</th>';
						echo '<th>Observación</th>';
						echo '<th>status_name</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($UltimaGestion as $Row){
						$SqlGestion = "SELECT * FROM Tipo_Contacto WHERE Id_TipoContacto='".$Row["Id_TipoGestion"]."'";
						$Gestion = $db->select($SqlGestion);
						if(count($Gestion) > 0){
							$Gestion = $Gestion[0]["Nombre"];
						}else{
							$Gestion = "";
						}
						echo '<tr>';
							echo '<td>'.$Row["Rut"].'</td>';
							echo '<td>'.$Row["fecha_gestion"].'</td>';
							echo '<td>'.$Gestion.'</td>';
							echo '<td>'.$Row["nombre_ejecutivo"].'</td>';
							echo '<td>'.$Row["observacion"].'</td>';
							echo '<td>'.$Row["status_name"].'</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo "</table>";
		echo "<div>";
	}
	function mostrarMejorGestionPredictivo($Rut,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$SqlMejorGestion = "SELECT * FROM ".$Queue."_Mejor_Gestion_Historica WHERE Rut='".$Rut."' ORDER BY fechahora DESC";
		$MejorGestion = $dbDiscador->select($SqlMejorGestion);
		echo "<div class='table-responsive'>";
			echo "<table class='table table-striped table-bordered' cellspacing='0' width='100%'>";
				echo '<thead>';
					echo '<tr>';
						echo '<th>Fono</th>';
						echo '<th>Fecha</th>';
						echo '<th>Gerstión</th>';
						echo '<th>Ejecutivo</th>';
						echo '<th>Observación</th>';
						echo '<th>status_name</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($MejorGestion as $Row){
						$SqlGestion = "SELECT * FROM Tipo_Contacto WHERE Id_TipoContacto='".$Row["Id_TipoGestion"]."'";
						$Gestion = $db->select($SqlGestion);
						if(count($Gestion) > 0){
							$Gestion = $Gestion[0]["Nombre"];
						}else{
							$Gestion = "";
						}
						echo '<tr>';
							echo '<td>'.$Row["Rut"].'</td>';
							echo '<td>'.$Row["fecha_gestion"].'</td>';
							echo '<td>'.$Gestion.'</td>';
							echo '<td>'.$Row["nombre_ejecutivo"].'</td>';
							echo '<td>'.$Row["observacion"].'</td>';
							echo '<td>'.$Row["status_name"].'</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo "</table>";
		echo "<div>";
	}

	public function getCamposMostrar($Deudas,$Columna){
  		$ToReturn = false;
  		if($Deudas){
	  		foreach($Deudas as $Deuda){
	    		if($Deuda[$Columna] != ""){
	      			if($Deuda[$Columna] != "0"){
	        			$ToReturn = true;
	        			break;
	      			}
	    		}
	  		}
  		}else{
  			$ToReturn = true;
  		}
  		return $ToReturn;
	}

	public function BuscarEnDirectorio($path,$num_factura)
	{
	    $this->path=$path;
	    $this->num_factura=$num_factura;

	    $dir = opendir($this->path);
	    $files = array();
	    $nombreArchivo = "";
	    while ($current = readdir($dir)){
	        if( $current != "." && $current != "..") {
	            if(is_dir($this->path.$current)) {
	                //showFiles($path.$current.'/');
	            }
	            else {
	                $files[] = $current;
	                $pos = strpos($current, $this->num_factura);
	                if ($pos !== false) {
					     return $current;
					}
	            }
	        }
	    }
	    return "0";

	}
	public function mostrarAsignacion($Cola){
		$db = new DB();
		$this->Cola=$Cola;
		$Cola2 = $this->Cola."_";

		echo "<select class='selectpicker' id='seleccione_asignacion' name='seleccione_cedente' data-live-search='true' data-width='100%'>";
		$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME LIKE '%".$this->Cola."%' GROUP BY TABLE_NAME";
		$rows=$db->select($query);
       	echo '<option value="0">Seleccione</option>';
       	if($rows){
	        foreach($rows as $row)
	        {
	        	$Cola = $row["TABLE_NAME"];
				$ParteA = explode("_", $Cola);
				if(isset($ParteA[7])){
					if($ParteA[7]==1){
						$Id_Entidad = $ParteA[4];
						switch ($ParteA[3]) {
							case 'E':
								$Tipo = 'Ejecutivo';
								$Entidades  = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = '".$Id_Entidad."' LIMIT 1");
								if($Entidades){
									foreach($Entidades as $Entidad){
										$Nombre = $Entidad["Nombre"];
									}
								}else{
									$Nombre = '';
								}
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";

								break;
							case 'S':
								$Tipo = 'Supervisor';
								$Entidades  = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = '".$Id_Entidad."' LIMIT 1");
								if($Entidades){
									foreach($Entidades as $Entidad){
										$Nombre = $Entidad["Nombre"];
									}
								}else{
									$Nombre = '';
								}
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";

								break;
							case 'G':
								$Tipo = 'Grupo';
								$Entidades  = $db->select("SELECT Nombre FROM grupos WHERE IdGrupo = '".$Id_Entidad."' LIMIT 1");
								if($Entidades){
									foreach($Entidades as $Entidad){
										$Nombre = $Entidad["Nombre"];
									}
								}else{
									$Nombre = '';
								}
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";

								break;
							case 'EE':
								$Tipo = 'Empresa Externa';
								$Entidades  = $db->select("SELECT Nombre FROM empresa_externa WHERE IdEmpresaExterna = '".$Id_Entidad."' LIMIT 1");
								if($Entidades){
									foreach($Entidades as $Entidad){
										$Nombre = $Entidad["Nombre"];
									}
								}else{
									$Nombre = '';
								}
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";
								break;
							case 'XA':
								$Tipo = 'Sistema';
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " +1 Millón en Deuda</option>";
								break;
							case 'XB':
								$Tipo = 'Sistema';
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " Sin Contacto</option>";
								break;
							case 'XC':
								$Tipo = 'Sistema';
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " Sin Gestion</option>";
								break;
							case 'XD':
								$Tipo = 'Sistema';
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " En Deuda</option>";
									break;
							case 'XF':
								$Tipo = 'Sistema';
								echo "<option value='$Cola'>"."Asignacion " . $Tipo . " Total Cartera</option>";
									break;	
						}
					}
				}
	        }
    	}

        echo "</select>";

	}

	function insertEstrategiaColaAsignacion($Asignacion, $estrategia){
		$db = new DB();
		$arreglo = array();

		$sql = "SELECT id, id_cola FROM asignacion_cola WHERE asignacion = '" . $Asignacion . "'";
		$cola = $db->select($sql);
		if($cola){
			$sqlVal = "SELECT id FROM estrategias_cola_asignacion WHERE id_estrategia = '" . $estrategia ."' AND id_cola = '" . $cola[0]["id_cola"] ."' AND id_asignacion = '" . $cola[0]["id"] ."'";
			$result = $db->select($sqlVal);

			if (count($result) == 0){
				$insert = "INSERT INTO estrategias_cola_asignacion (id_estrategia, id_cola, id_asignacion, id_usuario) VALUES ('" . $estrategia . "', '" . $cola[0]["id_cola"] ."', '" . $cola[0]["id"] ."', '" . $_SESSION['id_usuario'] . "')";
				$db->query($insert);
			}
		}
	}

	function getActualizaEstadoAsignacion($Asignacion,$tipoGestion,$rut,$fechaGestion,$fechaRellamar = null){
		$db = new DB();
		switch ($tipoGestion){
			case 3:
			case 4:
			// 3,4 SC
			$estatus = 1;
			break;
			case 1:
			case 2:
			case 5:
			// 1,2,5 C
			$estatus = 2;
			break;
			default:
			// sin historica
			$estatus = 0;
		}
		if ($fechaRellamar && trim($fechaRellamar) != ''){
			$estado_cola = 1;
			/* $fechaRellamar = explode(" ",$fechaRellamar);
			$fechaRellamar = $fechaRellamar[0]."T".$fechaRellamar[1]; */
		}else{
			$fechaRellamar = '';
			$estado_cola = 3;
		}
		/* $fechaGestion = explode(" ",$fechaGestion);
		$fechaGestion = $fechaGestion[0]."T".$fechaGestion[1]; */
		$query = "UPDATE ".$Asignacion." SET estado = '".$estatus."', fechaGestion = '".$fechaGestion."', llamado = '1', estado_cola = '".$estado_cola."', fechaRellamar = '".$fechaRellamar."' WHERE Rut = '".$rut."'";
		$db->query($query);
	}

	function mostrarInforme($Mandante,$Cedente){
		$WhereCedente = $Cedente == "" ? "" : " Cedente.Id_Cedente = '".$Cedente."' AND ";
		$WhereMandante = $Mandante == "" ? "" : " Cedente.Id_Cedente IN (SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = '".$Mandante."') AND ";
		$dbDiscador = new DB('discador');
		$this->updateTiempoTranscurrido();
		$sql = "SELECT
					ro.anexo,
					ro.ejecutivo,
					ro.estatus,
					ro.pausa,
					ro.tiempo,
					ro.cartera
				FROM
					reportOnLine
					INNER JOIN Cedente on Cedente.Nombre_Cedente = ro.cartera
				WHERE
					-- ".$WhereCedente."
					-- ".$WhereMandante."
					ro.activo = 1";
		$resultado = $dbDiscador->select($sql);
		echo '<table id="demo-foo-filtering" class="table table-bordered table-hover toggle-circle" data-page-size="12">
				<thead>
					<tr>
						<th data-toggle="true">Anexo</th>
						<th>Ejecutivo</th>
						<th data-hide="phone, tablet">Estatus</th>
						<th data-hide="phone, tablet">Pausa</th>
						<th data-hide="phone, tablet">MM:SS</th>
						<th data-hide="phone, tablet">Cartera</th>
						<th data-hide="phone, tablet">Cantidad</th>
					</tr>
				</thead>
				<div class="pad-btm form-inline">
				<tbody>';
				foreach($resultado as $result){ // anexo, ejecutivo, estatus, pausa, tiempo, cartera

					$anexo = $result['anexo'];
					$ejecutivo = utf8_encode($result['ejecutivo']);
					$estatus = utf8_encode($result['estatus']);
					$pausa = utf8_encode($result['pausa']);
					$tiempo = $result['tiempo'];
					$cartera = utf8_encode($result['cartera']);
					$sqlCantidad = "SELECT cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' AND cartera = '".$cartera."'";
					$resu = $db -> select($sqlCantidad);
					if(count($resu)>0){
						$cantidad = $resu[0]["cantidad"];
					}else{
						$cantidad = 0;
					}

					switch ($estatus){
						case 'DISPONIBLE': // esperando llamada
							$colorEstatus = 'label-success';
							$colorFila = 'background-color: #E9FCED';
						break;
						case 'EN LLAMADA': // esta hablando

							$colorEstatus = 'label-danger';
							$colorFila = 'background-color: #FAE2D1';
						break;
						case 'PAUSADO': // esta en pausa
							$colorEstatus = 'label-warning';
							$colorFila = $this->colorFilaEstatus($tiempo,"PAUSADO");
						break;
						//case 'DEAD': // colgo y esta en tiempo de guardar la gestion
						case 'MUERTO': // colgo y esta en tiempo de guardar la gestion
							$colorEstatus = 'label-dark';
							$colorFila = 'background-color: #FFFFFF';
						break;
					}

					switch ($pausa){
						case 'Cafe': // esperando llamada
							$colorPausa = 'label-info';
						break;
						case 'Bano': // esta hablando
							$colorPausa = 'label-danger';
						break;
						case 'Soporte': // esta en pausa
							$colorPausa = 'label-warning';
						break;
						case 'Office': // esta en pausa
							$colorPausa = 'label-mint';
						break;
						case 'Capacitacion': // esta en pausa
							$colorPausa = 'label-purple';
						break;
						case 'Reunion': // esta en pausa
							$colorPausa = 'label-success';
						break;
						default:
						$colorPausa = '';

					}

					echo '<tr style="'.$colorFila.'">
						<td>'.$anexo.'</td>
						<td>'.$ejecutivo.'</td>
						<td><span class="label label-table '.$colorEstatus.'">'.$estatus.'</span></td>
						<td><span class="label label-table '.$colorPausa.'">'.$pausa.'</span></td>
						<td>'.$tiempo.'</td>
						<td>'.$cartera.'</td>
						<td>'.$cantidad.'</td>
					</tr>';
				}

				echo '</tbody>
				<!--<tfoot>
					<tr>
						<td colspan="6">
							<div class="text-right">
								<ul class="pagination"></ul>
							</div>
						</td>
					</tr>
				</tfoot>-->
			</table>';
	}

	function nuevoEstatusReporteOnline($datos){
		// activo nuevo estatus
		$dbDiscador = new DB('discador');
		$estatus = $datos['estatus'];
		$pausa = $datos['pausa'];
		$anexo = "SIP/".$_SESSION['anexo_foco'];
		$fechaActual = date("Y-m-d");
		$idCedente = $_SESSION['cedente'];
		$nombreCedente = $_SESSION['cedente'];

		if ($estatus <> ''){
			//$sqlCedente= "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$idCedente."'";
			//$resultCedente = $db -> select($sqlCedente);
			$cartera = $nombreCedente;//$resultCedente[0]["Nombre_Cedente"];

			// antes de guardar guardo el acumulativo busco el status activo (puede que no este ninguno activo en este caso
			// el ejecutivo esta fuera de linea y esta entrando por primera vez a disponible)
			$sqlEstatusActivo = "SELECT tiempo, estatus, cartera, id_reporte FROM reporteOnLine WHERE anexo = '".$anexo."' AND activo = 1";
			$result = $dbDiscador->select($sqlEstatusActivo);
			if ($result){
				// sumo el tiempo acumulado y lo almaceno en la tabla historica
				$tiempo = $result[0]["tiempo"];
				$estatusHis = $result[0]["estatus"];
				$cartera = $result[0]["cartera"];
				$idReporte = $result[0]["id_reporte"];


				$sqlTiempo = "SELECT tiempo, id_reporte FROM reporteOnLineHistorico WHERE anexo = '".$anexo."' AND estatus = '".$estatusHis."' AND fecha = '".$fechaActual."' AND cartera = '".$cartera."'";
				$tiempoHis = 0;
				$resultTiempo = $dbDiscador->select($sqlTiempo);
				if($resultTiempo){
					$tiempoHis = $resultTiempo[0]["tiempo"];
					$idReporte = $resultTiempo[0]["id_reporte"];
				}else{
					$tiempoHis = 0;
					$idReporte = 0;
				}
				
				// OJOOOOOO SUMAR TIEMPOS (ACUMULADO)
				$tiempoAcumulado = $tiempoHis + $tiempo;

				$sqlTiempoHistorico = "UPDATE reporteOnLineHistorico SET tiempo = '".$tiempoAcumulado."' WHERE id_reporte = '".$idReporte."'";
				$dbDiscador->query($sqlTiempoHistorico);
			}

			// inactivo el ultimo estatus
			$sqlActivo = "UPDATE reporteOnLine SET activo = 0 WHERE anexo = '".$anexo."'";
			$dbDiscador->query($sqlActivo);

			// verifico si el estatus ya esta creado, si es asi solo lo actualizo si no lo creo
			$sqlEstatus = "SELECT id_reporte FROM reporteOnLine WHERE anexo = '".$anexo."' AND estatus = '".$estatus."'";
			$result = $dbDiscador->select($sqlEstatus);
			$inicio = date("H:i:s");
			$termino = date("H:i:s");
			$tiempo = 0;
			$ejecutivo = $_SESSION['nombreUsuario'];

			if (count($result) > 0){
				$idReporte = $result[0]["id_reporte"];
				// activo el nuevo estatus
				$sql = "UPDATE reporteOnLine SET inicio = '".$inicio."', termino = '".$termino."', activo = 1, tiempo = 0, cartera = '".$cartera."', pausa = '".$pausa."' WHERE id_reporte = '".$idReporte."'";
				$dbDiscador->query($sql);
			}else{
				$activo = 1;
				$sqlCreaRegistro = "INSERT INTO reporteOnLine(anexo,ejecutivo,estatus,pausa,inicio,termino,tiempo,cartera,activo) VALUES ('".$anexo."','".$ejecutivo."','".$estatus."','".$pausa."','".$inicio."','".$termino."','".$tiempo."','".$cartera."','".$activo."')";
				$dbDiscador->query($sqlCreaRegistro);
				$sqlCreaRegistroHisto = "INSERT INTO reporteOnLineHistorico(anexo,ejecutivo,estatus,pausa,tiempo,cartera,fecha) VALUES ('".$anexo."','".$ejecutivo."','".$estatus."','".$pausa."','".$tiempo."','".$cartera."','".$fechaActual."')";
				$dbDiscador->query($sqlCreaRegistroHisto);
			}
		}else{
			// el ejecutivo salio de la cola por lo tanto no quedan status activos
			// inactivo el ultimo estatus
			$sqlActivo = "UPDATE reporteOnLine SET activo = 0 WHERE anexo = '".$anexo."'";
			//$sqlActivo = "DELETE FROM reportOnLine WHERE anexo = '".$anexo."'";
			$dbDiscador->query($sqlActivo);
		}
	}

	function insertarNivelCola($id_cola, $rut, $nivel1, $nivel2, $nivel3, $fecha_hora){
		$db = new DB();

		if($nivel1 == ""){
			$sqlNiveles = "SELECT n1.id AS nivel1, n2.id AS nivel2, n3.id AS nivel3, n3.Id_TipoGestion AS tipoGestion
							FROM 
								Nivel3 AS n3
								INNER JOIN Nivel2 AS n2 ON (n2.id = n3.Id_Nivel2)
								INNER JOIN Nivel1 AS n1 ON (n1.Id = n2.Id_Nivel1)
							WHERE n3.id = '" . $nivel3 . "'";

			$niveles = $db->select($sqlNiveles);

			$sql = "INSERT INTO 
						titular_niveles_cola (id_cola, rut, nivel1, nivel2, nivel3, id_tipo_gestion, fecha_hora) 
					VALUES 
						('". $id_cola ."', '". $rut ."', '". 
							$niveles[0]['nivel1'] ."', '". $niveles[0]['nivel2'] ."', '". $nivel3 ."', '". 
							$niveles[0]['tipoGestion'] ."', '". $fecha_hora ."')";

		}else{
			$sqlTipoGestion = "SELECT Id_TipoGestion AS tipoGestion FROM Nivel3 WHERE id = '" . $nivel3 . "'";

			$tipoGestion = $db->select($sqlTipoGestion);

			$sql = "INSERT INTO 
				titular_niveles_cola (id_cola, rut, nivel1, nivel2, nivel3, id_tipo_gestion, fecha_hora) 
			VALUES 
				('". $id_cola ."', 
				'". $rut ."', 
				'". $nivel1 ."', 
				'". $nivel2 ."', 
				'". $nivel3 ."', 
				'". $tipoGestion[0]['tipoGestion'] ."', 
				'". $fecha_hora ."')";
		}
		$db->query($sql);
	}

	function insertarNivelColaPredictivo($cola, $rut, $nivel1, $nivel2, $nivel3, $fecha_hora){
		$db = new DB();

		$sqlCola = "SELECT Cola FROM Asterisk_Discador_Cola WHERE id = '" . $cola . "'";
		$cola = $db->select($sqlCola);

		$queue = explode("_", $cola[0]['Cola']);

		if($nivel1 == ""){
			$sqlNiveles = "SELECT n1.id AS nivel1, n2.id AS nivel2, n3.id AS nivel3, n3.Id_TipoGestion AS tipoGestion
							FROM 
								Nivel3 AS n3
								INNER JOIN Nivel2 AS n2 ON (n2.id = n3.Id_Nivel2)
								INNER JOIN Nivel1 AS n1 ON (n1.Id = n2.Id_Nivel1)
							WHERE n3.id = '" . $nivel3 . "'";

			$niveles = $db->select($sqlNiveles);

			$sql = "INSERT INTO 
						titular_niveles_cola (id_cola, rut, nivel1, nivel2, nivel3, id_tipo_gestion, fecha_hora) 
					VALUES 
						('". $queue[2] ."', '". $rut ."', '". 
							$niveles[0]['nivel1'] ."', '". $niveles[0]['nivel2'] ."', '". $nivel3 ."', '". 
							$niveles[0]['tipoGestion'] ."', '". $fecha_hora ."')";

		}else{
			$sqlTipoGestion = "SELECT Id_TipoGestion AS tipoGestion FROM Nivel3 WHERE id = '" . $nivel3 . "'";

			$tipoGestion = $db->select($sqlTipoGestion);

			$sql = "INSERT INTO 
				titular_niveles_cola (id_cola, rut, nivel1, nivel2, nivel3, id_tipo_gestion, fecha_hora) 
			VALUES 
				('". $queue[2] ."', 
				'". $rut ."', 
				'". $nivel1 ."', 
				'". $nivel2 ."', 
				'". $nivel3 ."', 
				'". $tipoGestion[0]['tipoGestion'] ."', 
				'". $fecha_hora ."')";
		}
		$db->query($sql);
	}

	function diferenciaEntreHoras($PrimeraFecha,$UltimaFecha){
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

	function pausaPredictivo(){
		$db = new DB("discador");
		$DiscadorClass = new Discador();
		$Anexo = "SIP/".$_SESSION['anexo_foco'];
		$row = $db->select("SELECT Queue FROM Asterisk_Agentes WHERE Agente = '$Anexo' LIMIT 1");
		$queues = $row[0]["Queue"];
		$DiscadorClass->Pause_Predictivo($queues,$Anexo);
		//shell_exec("php /var/www/html/produccion/discador/AGI/Pause.php '$queues' '$Anexo'");
	}

	function capturaHangup(){
		$dbDiscador = new DB("discador");
		$anexo = $_SESSION['anexo_foco'];
		$sql = "SELECT anexo FROM Asterisk_Hangup WHERE anexo = '".$anexo."'";
		$resultado = $dbDiscador -> select($sql);
		if (count($resultado) > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function updateTiempoTranscurrido(){
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

	function getGestionesFactura($rut, $factura){
		$db = new DB();
		$this->rut = $rut;
		$this->factura = $factura;
		$response = array();

		$query1 = $db->select("SELECT 
										id_gestion, rut_cliente, fecha_gestion, resultado, fono_discado, 
										nombre_ejecutivo, cedente, fec_compromiso, monto_comp, Id_TipoGestion, 
										origen, n1, n2, n3, observacion, status_name, factura 
									FROM 
										gestion_ult_trimestre 
									WHERE 
										rut_cliente = '" . $this->rut ."' 
										AND factura = '" . $this->factura . "'
									ORDER BY fechahora DESC
									LIMIT 20");

		foreach($query1 as $q1)
		{
			$v1 	= $q1["rut_cliente"];
			$v2 	= $q1["fecha_gestion"];
			$v3 	= $q1["resultado"];
			$v4 	= $q1["fono_discado"];
			$v5 	= $q1["nombre_ejecutivo"];
			$v6 	= $q1["cedente"];
			$v7 	= $q1["fec_compromiso"];
			$v8 	= $q1["monto_comp"];
			$v9 	= $q1["Id_TipoGestion"];
			$v10	= $q1["observacion"];
			$r1 	= $q1["n1"];
			$r2 	= $q1["n2"];
			$r3 	= $q1["n3"];

			$origen 	= $q1["origen"];
			$idGestion 	= $q1["id_gestion"];
			$factura 	= $q1["factura"];
			
			if($origen==1)
			{
				if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
					$v7 = '---';
					$v8 = '---';
				}else{
					$v7 = $v7;
					$v8 = $v8;
				}
			}
			else
			{
				if($v7=='' OR $v7=='0000-00-00' OR $v7=='1900-01-01' OR $v7=='1970-01-01'){
					$v7 = '---';
					$v8 = '---';
				}else{
					$v7 = $v7;
					$v8 = $v8;
				}
			}

			$arreglo = array();
			
			$arreglo['fecha_gestion'] 	= $v2;
			$arreglo['fono_discado'] 	= $v4;
			$arreglo['ejecutivo'] 		= $v5;
			$arreglo['compromiso'] 		= $v7;
			$arreglo['monto'] 			= $v8;
			$arreglo['observacion'] 	= $v10;
			$arreglo['n1'] 				= $r1;
			$arreglo['n2'] 				= $r2;
			$arreglo['n3'] 				= $r3;
			$arreglo['status_name'] 	= $statusName;
			$arreglo['factura'] 		= $factura;
			$arreglo['gestion'] 		= $idGestion;

			array_push($response, $arreglo);
		}
		return $response;
	}

	function colorFilaEstatus($tiempo,$tipoEstatus){
		$array = explode(":", $tiempo);
		if(($array[0] == 0) && ($array[1] == 0)){
  			if($array[2] < 10){
    			if ($tipoEstatus == "PAUSADO"){
					$color = 'background-color: #F7F6E7';
				}else{
					if ($tipoEstatus == "EN LLAMADA"){
						$color = 'background-color: #FAE2D1';
					}
				}
  			}else{
				if ($tipoEstatus == "PAUSADO"){
					$color = 'background-color: #F7EF81';
				}else{
					if ($tipoEstatus == "EN LLAMADA"){
						$color = 'background-color: #CCFEF9';
					}
				}

			}
		}else{
			if ($array[0] > 0){
				if ($tipoEstatus == "PAUSADO"){
					$color = 'background-color: #FB7C7C';
				}else{
					if ($tipoEstatus == "EN LLAMADA"){
						$color = 'background-color: #FB7C7C';
					}
				}

			}else{
				if(($array[1] >= 1) && ($array[1] < 5)){
					if ($tipoEstatus == "PAUSADO"){
						$color = 'background-color: #9CC6DA';
					}else{
						if ($tipoEstatus == "EN LLAMADA"){
							$color = 'background-color: #8AF7ED';
						}
					}

				}else{
						if($array[1] >= 5){
							if ($tipoEstatus == "PAUSADO"){
						$color = 'background-color: #FB7C7C';
					}else{
						if ($tipoEstatus == "EN LLAMADA"){
							$color = 'background-color: #8AF7ED';
						}
					}

					}else{
						if ($tipoEstatus == "PAUSADO"){
							$color = 'background-color: #F7F6E7';
						}else{
							if ($tipoEstatus == "EN LLAMADA"){
								$color = 'background-color: #75C9C1';
							}
						}
					}
				}
			}
		}

		return $color;
	}
	function isAuthorizedModule($Type){
		$db = new DB();
		$ToReturn = array();
		$ToReturn["result"] = false;
		$SqlAuthorized = "SELECT * FROM AutorizacionEjecutivos WHERE Id_Usuario='".$_SESSION["id_usuario"]."' AND Id_Cedente='".$_SESSION["cedente"]."' AND tipoAutorizacion='".$Type."'";
		$Authorized = $db->select($SqlAuthorized);
		if(count($Authorized) > 0){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}
	function guardarGestionCorreo($correo, $facturas, $rut, $template, $id_envio){
		$db = new DB();
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		$EmailClass = new Email();
		$cedente = $_SESSION["cedente"];
		$nombre = $_SESSION["nombreUsuario"];
		if (is_array($facturas)){
			$facturasEn = implode(",",$facturas);
		}else{
			$facturasEn = $facturas;
		}
		if($EmailClass->checkEmail($correo)){
			$estado = '1';
		}else{
			$estado = '2';
		}
		$sqlCreaRegistro = "INSERT INTO gestion_correo(rut_cliente,fecha_gestion,hora_gestion,nombre_ejecutivo,cedente,correos,facturas,template,id_envio,estado) VALUES ('".$rut."','".$fecha."','".$hora."','".$nombre."','".$cedente."','".$correo."','".$facturasEn."','".$template."','".$id_envio."','".$estado."')";
		$db->query($sqlCreaRegistro);
		return $estado;
		
	}
	function getColumnasAgregar_ConfCRM(){
		$db = new DB();
		if(isset($_SESSION["cedente"])){
			$SqlColumas = "SELECT
								SIS_Columnas_Estrategias.columna as Columna
							FROM
								SIS_Columnas_Estrategias
							left join 
								Columnas_Asignacion_CRM on Columnas_Asignacion_CRM.columna = SIS_Columnas_Estrategias.columna 
							AND 
								FIND_IN_SET('".$_SESSION["cedente"]."',Columnas_Asignacion_CRM.Id_Cedente)
							WHERE
								SIS_Columnas_Estrategias.id_tabla='2' 
							AND 
								FIND_IN_SET('".$_SESSION["cedente"]."',SIS_Columnas_Estrategias.Id_Cedente)
							AND
								Columnas_Asignacion_CRM.id is null
							ORDER BY
								SIS_Columnas_Estrategias.columna";
			$Columnas = $db->select($SqlColumas);
		}else{
			$Columnas = '';
		}
		return $Columnas;
	}
	function saveColumna_ConfCRM($Column){
		$ToReturn = array();
		$ToReturn["result"] = false;
		if(isset($_SESSION["cedente"])){
			$db = new DB();
			$SqlInsert = "insert into Columnas_Asignacion_CRM (prioridad,columna,Id_Cedente) values (0,'".$Column."','".$_SESSION["cedente"]."')";
			$Insert = $db->query($SqlInsert);
			if($Insert){
				$ToReturn["result"] = true;
			}
		}
		return $ToReturn;
	}
	function getColumns_ConfCRM(){
		$ToReturn = array();
		$db = new DB();
		if(isset($_SESSION["cedente"])){
			$SqlColumns = "SELECT * FROM Columnas_Asignacion_CRM WHERE Id_Cedente='".$_SESSION["cedente"]."'";
			$Columns = $db->select($SqlColumns);
			foreach($Columns as $Column){
				$ArrayTmp = array();
				$ArrayTmp["Prioridad"] = $Column["prioridad"];
				$ArrayTmp["Campo"] = $Column["columna"];
				$ArrayTmp["Destacar"] = $Column["destacar"];
				$ArrayTmp["Accion"] = $Column["id"];

				array_push($ToReturn,$ArrayTmp);
			}
		}
		return $ToReturn;
	}
	function updatePrioridad_ConfCRM($Value,$ID){
		$ToReturn = array();
		$db = new DB();
		$SqlUpdate = "update Columnas_Asignacion_CRM set prioridad='".$Value."' WHERE id='".$ID."'";
		$Update = $db->query($SqlUpdate);
		if($Update){
			$ToReturn['result'] = true;
		}else{
			$ToReturn['result'] = false;
		}
		return $ToReturn;
	}
	function updateDestacar_ConfCRM($Value,$ID){
		$ToReturn = array();
		$db = new DB();
		$SqlUpdate = "update Columnas_Asignacion_CRM set destacar='".$Value."' WHERE id='".$ID."'";
		$Update = $db->query($SqlUpdate);
		if($Update){
			$ToReturn['result'] = true;
		}else{
			$ToReturn['result'] = false;
		}
		return $ToReturn;
	}
	function deleteColumn_ConfCRM($ID){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		$SqlDelete = "delete FROM Columnas_Asignacion_CRM WHERE id='".$ID."'";
		$Delete = $db->query($SqlDelete);
		if($Delete){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}
	function getMetrica(){
		$ToReturn = array();
		$db = new DB();
		$sqlQuery= "SELECT NombreMetrica FROM Palabras_speech";
		$query = $db->select($sqlQuery);
		if($query){
			foreach($query as $row){
				$dato = utf8_encode($row["NombreMetrica"]);
				array_push($ToReturn,$dato);
			}
		}
		
		return $ToReturn;
	}
	public function guardarTranscripcion($transcripcion,$palabras,$url){

		$ToReturn = array();

		$db = new DB();
		$query = "SELECT id_gestion FROM gestion_ult_trimestre with(nolock) WHERE url_grabacion = '".$url."'";
		$gestiones = $db->select($query);

		if(count($gestiones) > 0){
			$id_gestion = $gestiones[0]["id_gestion"];
		}else{

			$query = "SELECT id_gestion FROM gestion_ult_trimestre ORDER BY id_gestion DESC LIMIT 1";
			$gestiones = $db->select($query);

			if(count($gestiones) > 0){
				$id_gestion = $gestiones[0]["id_gestion"];
			}else{
				$id_gestion = 0;
			}
		}

		$query = "INSERT INTO Transcripciones_speech(Transcripcion,id_gestion,id_distribuidor) VALUES ('".$transcripcion."','".$id_gestion."','1')";
		$insert = $db->query($query);

		if($insert){

			$query = "SELECT id FROM Transcripciones_speech ORDER BY id DESC LIMIT 1";
			$select = $db->select($query);
			$id_transcripcion = $select[0]['id'];

			$explodePalabras = explode(",", $palabras);

			foreach($explodePalabras as $NombreMetrica){
				$query = "SELECT id FROM Palabras_speech WHERE NombreMetrica = '".$NombreMetrica."'";
				$palabras_speech = $db->select($query);
				if(count($palabras_speech) > 0){
					$id_palabra = $palabras_speech[0]["id"];
					$query = "INSERT INTO Palabras_Claves_Transcripcion_speech (id_palabra, id_transcripcion) VALUES ('".$id_palabra."','".$id_transcripcion."')";
					$insert_palabra = $db->query($query);
				}
			}

			$ToReturn["result"] = true;
			$ToReturn["message"] = "Transcripción generada satisfactoriamente.";

		}else{
			$ToReturn["result"] = false;
			$ToReturn["message"] = "Hubo un problema al crear la transcripción, intentelo nuevamente o comuniquese con soporte tecnico.";
		}
		
		$ToReturn["result"] = true;
		return $ToReturn;
	}
	function deleteGestion($idGestion){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		if($this->crearAuditoriaGestion($idGestion)){
			$SqlDelete = "DELETE FROM gestion_ult_trimestre WHERE id_gestion = '".$idGestion."'";
			$Delete = $db->query($SqlDelete);
			if($Delete){
				$SqlDelete = "DELETE FROM respuestas_campos_gestion WHERE id_gestion = '".$idGestion."'";
				$Delete = $db->query($SqlDelete);
				if($Delete){
					$ToReturn["result"] = true;
					$ToReturn["message"] = "Gestion eliminada satisfactoriamente.";
				}else{
					$ToReturn["message"] = "Hubo un problema al eliminar las respuestas, intentelo nuevamente o comuniquese con soporte tecnico.";
				}
			}else{
				$ToReturn["message"] = "Hubo un problema al eliminar la gestion, intentelo nuevamente o comuniquese con soporte tecnico.";
			}
		}else{
			$ToReturn["message"] = "Hubo un problema al crear la copia de auditoria, intentelo nuevamente o comuniquese con soporte tecnico.";
		}
		return $ToReturn;
	}
	function crearAuditoriaGestion($idGestion){
		$ToReturn = false;
		$db = new DB();
		$SqlGestion = "SELECT * FROM gestion_ult_trimestre WHERE id_gestion='".$idGestion."'";
		$Gestion = $db->select($SqlGestion);
		foreach($Gestion as $Row){
			$Rut = $Row["rut_cliente"];
			$FonoDiscado = $Row["fono_discado"];
			$FechaHora = $Row["fechahora"];
			$IdN1 = $Row["resultado"];
			$IdN2 = $Row["resultado_n2"];
			$IdN3 = $Row["resultado_n3"];
			$N1 = $Row["n1"];
			$N2 = $Row["n2"];
			$N3 = $Row["n3"];
			$Origen = $Row["origen"];
			$NombreEjecutivo = $Row["nombre_ejecutivo"];
			$UrlGrabacion = $Row["url_grabacion"];
			$MontoCompromiso = $Row["monto_comp"];
			$Cedente = $Row["cedente"];

			$SqlAuditoria = "insert into auditoria_gestion (Rut,Fono,fechahora,resultado,resultado_n2,resultado_n3,n1,n2,n3,origen,nombre_ejecutivo,url_grabacion,monto_comp,cedente,id_usuarioSupervisor) values ('".$Rut."','".$FonoDiscado."','".$FechaHora."','".$IdN1."','".$IdN2."','".$IdN3."','".$N1."','".$N2."','".$N3."','".$Origen."','".$NombreEjecutivo."','".$UrlGrabacion."','".$MontoCompromiso."','".$Cedente."','".$_SESSION['id_usuario']."')";
			$Auditoria = $db->query($SqlAuditoria);
			if($Auditoria){
				$ToReturn = true;
			}
		}
		return $ToReturn;
	}

	function getTemplates(){
		$ToReturn = array();
		if(isset($_SESSION["cedente"])){
			$db = new DB();
			$query = "SELECT Id, Nombre FROM EMAIL_Template WHERE id_cedente = '".$_SESSION["cedente"]."'";
			$Templates = $db->select($query);
			if($Templates){
				foreach($Templates as $Template){
					array_push($ToReturn,$Template);
				}
			}
		}
		return $ToReturn;
	}

	function getTemplatesSMS(){
		$ToReturn = array();
		if(isset($_SESSION["cedente"])){
			$db = new DB();
			$query = "SELECT id, Nombre FROM SMS_Template WHERE id_cedente = '".$_SESSION["cedente"]."'";
			$Templates = $db->select($query);
			if($Templates){
				foreach($Templates as $Template){
					array_push($ToReturn,$Template);
				}
			}
		}
		return $ToReturn;
	}
	function getTabs(){
		$ToReturn = array();
		$db = new DB();
		$Header = '';
		$Content = '';
		if(isset($_SESSION["cedente"])){
			$query = "	SELECT 
							id, tab, sistema, id_tab_sistema
						FROM 
							Tabs_Asignacion_CRM 
						WHERE 
							Id_Cedente = '".$_SESSION["cedente"]."' 
						AND 
							activo = 1 
						ORDER BY 
							prioridad";
			$Tabs = $db->select($query);
			foreach($Tabs as $Index => $Tab){
				$Th = '';
				if ($Index != 0){
					$Fade = '';
					$Active = '';
				}else{
					$Fade = 'class = "active"';
					$Active = 'active in';
				}
				$Header .= '<li '.$Fade.'>';
				$Header .= '<a data-toggle="tab" href="#tab-'.$Tab['id'].'">'.$Tab['tab'].'</a>';
				$Header .= '</li>';
				if($Tab['sistema']){
					switch($Tab['id_tab_sistema']){
						case 1:
							$Name = 'mostrar_deudas';
						break;
						case 2:
							$Name = 'mostrar_gestion_contacto';
							$Th .= '<th>Fecha Gestión</th>';
							$Th .= '<th>Nombre Ejecutivo</th>';
							$Th .= '<th>Fono Discado</th>';
							$Th .= '<th>Respuesta</th>';
							$Th .= '<th>Sub Respuesta</th>';
							$Th .= '<th>Sub Respuesta</th>';
							$Th .= '<th>Fecha Compromiso</th>';
							$Th .= '<th>Monto Compromiso</th>';
							$Th .= '<th>Observación</th>';
							$Th .= '<th>Canales</th>';
							$Th .= '<th></th>';
						break;
						case 3:
							$Name = 'mostrar_gestion_pagos';
							$Th .= '<th>RUT</th>';
							$Th .= '<th>Fecha Pago</th>';
							$Th .= '<th>Monto Pago</th>';
							$Th .= '<th>Número Factura</th>';
						break;
						case 4:
							$Name = 'mostrar_gestion_total';
							$Th .= ' <th>Fecha Gestión</th>';
							$Th .= '<th>Nombre Ejecutivo</th>';
							$Th .= '<th>Fono Discado</th>';
							$Th .= '<th>Status Name</th>';
							$Th .= '<th>Respuesta</th>';
							$Th .= '<th>Sub Respuesta</th>';
							$Th .= '<th>Sub Respuesta</th>';
							$Th .= '<th>Fecha Compromiso</th>';
							$Th .= '<th>Monto Compromiso</th>';
							$Th .= '<th>Nº Factura</th>';
							$Th .= '<th>Observación</th>';
							$Th .= '<th></th>';
						break;
						case 5:
							$Name = 'mostrar_gestion_diaria';
							$Th .= '<th>Fecha Gestión</th>';
							$Th .= '<th>Nombre Ejecutivo</th>';
							$Th .= '<th>Fono Discado</th>';
							$Th .= '<th>Status Name</th>';
							$Th .= '<th>Respuesta</th>';
							$Th .= '<th>Sub Respuesta</th>';
							$Th .= '<th>Sub Respuesta</th>';
							$Th .= '<th>Fecha Compromiso</th>';
							$Th .= '<th>Monto Compromiso</th>';
							$Th .= '<th>Nº Factura</th>';
							$Th .= '<th>Observación</th>';
							$Th .= '<th>Canales</th>';
							$Th .= '<th></th>';
						break;
						case 6:
							$Name = 'mostrar_gestion_correo';
							$Th .= '<th>Fecha Gestión</th>';
							$Th .= '<th>Hora Gestión</th>';
							$Th .= '<th>Nombre Ejecutivo</th>';
							$Th .= '<th>Correo</th>';
							$Th .= '<th>Nº Factura</th>';
							$Th .= '<th>Estado</th>';
						break;
						case 7:
							$Name = 'mostrar_gestion_sms';
							$Th .= '<th>Fecha Gestión</th>';
							$Th .= '<th>Hora Gestión</th>';
							$Th .= '<th>Nombre Ejecutivo</th>';
							$Th .= '<th>Fono</th>';
							$Th .= '<th>Estado</th>';
						break;
						case 8:
							$Name = 'mostrar_gestion_ivr';
							$Th .= '<th>Fecha Gestión</th>';
							$Th .= '<th>Hora Gestión</th>';
							$Th .= '<th>Fono</th>';
							$Th .= '<th>Duración</th>';
							$Th .= '<th>Estado</th>';
						break;
						case 9:
							$Name = 'mostrar_gestiones_externas';
							$SqlColumnasGestionesExternas = "select COLUMN_NAME as Columna from information_schema.COLUMNS where TABLE_SCHEMA='foco' AND TABLE_NAME='GE_".$_SESSION["cedente"]."' and COLUMN_NAME not in ('id','Rut') order by COLUMN_NAME";
							$ColumnasGestionesExternas = $db->select($SqlColumnasGestionesExternas);
							foreach($ColumnasGestionesExternas as $Columna){
								$Th .= '<th>'.$Columna["Columna"].'</th>';
							}
						break;
						case 9:
							$Name = 'mostrar_numeros_transferencias';
						break;
					}
				}else{
					$Name = str_replace(' ', '', $Tab['tab']);
					$query = "  SELECT 
									columna as Nombre
								FROM 
									Columnas_Tabs_Asignacion_CRM
								WHERE 
									id_tab = '".$Tab['id']."'
								ORDER BY
									prioridad";
					$Columnas = $db->select($query);
					foreach($Columnas as $Columna){
						$Th .= '<th>'.$Columna['Nombre'].'</th>';
					}
				}
				$Content .= '<div id="tab-'.$Tab['id'].'" class="tab-pane fade '.$Active.'">';
				$Content .= '<div id="'.$Name.'_ocultar">'.$Tab['tab'].'.</div>';
				$Content .= '<div id="'.$Name.'"></div>';
				if($Tab['id_tab_sistema'] != 1){
					switch($Tab['id_tab_sistema']){
						case 10:
							$Content .= "<div class='row'>";
								$Content .= "<div class='form-group col-sm-4'>";
									$Content .= "<select class='selectpicker form-control' name='NumeroTransferencia' title='Seleccione' data-live-search='true' data-width='100%'>";
										$Content .= "<option value=''>-- Seleccione --</option>";
										$SqlNumeros = "SELECT * FROM Numeros_Transferencias_CRM where Id_Cedente='".$_SESSION['cedente']."'";
										$Numeros = $db->select($SqlNumeros);
										foreach($Numeros as $Numero){
											$Descripcion = $Numero["Descripcion"];
											$Telefono = $Numero["Numero"];
											$Content .= "<option value='".$Telefono."'>".$Descripcion."</option>";
										}
									$Content .= "</select>";
								$Content .= "</div>";
							$Content .= "</div>";
						break;
						default:
							$Content .= '<div class="table-responsive">';
							$Content .= '<table id="'.$Name.'_dt" cellspacing="0" class="table table-striped table-bordered" style="width:100%">';
							$Content .= '<thead>';
							$Content .= '<tr>';
							$Content .= $Th;
						break;
					}
				}
				$Content .= '</div>';
				$Content .= '</tr>';
				$Content .= '</thead>';
				$Content .= '<tbody>';
				$Content .= '</tbody>';
				$Content .= '</table>';
				$Content .= '</div>';
				$Content .= '</div>';
				$Content .= '</div>';
			}
		}
		
		$ToReturn['Header'] = $Header;
		$ToReturn['Content'] = $Content;
		return $ToReturn;
	}
	function getTabsContent($Rut){
		$ToReturn = array();
		$db = new DB();

		$query = "	SELECT 
						id, tab
					FROM 
						Tabs_Asignacion_CRM 
					WHERE 
						Id_Cedente = '".$_SESSION["cedente"]."' 
					AND 
						activo = 1 
					AND
						sistema = 0
					ORDER BY 
						prioridad";
		$Tabs = $db->select($query);
		foreach($Tabs as $Index => $Tab){
			$ToReturn[$Index]['Tab'] = str_replace(' ', '', $Tab['tab']);
			$query = "  SELECT 
							columna as data,
							CONCAT(tabla,'.',columna) as Columna
						FROM 
							Columnas_Tabs_Asignacion_CRM
						WHERE 
							id_tab = '".$Tab['id']."'
						ORDER BY
							prioridad";
			$Columnas = $db->select($query);
			$Fields = array();
			$Columns = array();
			foreach($Columnas as $Columna){
				$ArrayTmp = array();
				$ArrayTmp["data"] = $Columna['data'];
				array_push($Columns,$Columna['Columna']);
				array_push($Fields,$ArrayTmp);
			}
			$Campos = implode(",", $Columns);
			$query = "  SELECT 
							".$Campos."
						FROM 
							Deuda
						INNER JOIN 
							Persona on Deuda.Rut = Persona.Rut 
						WHERE 
							Deuda.Rut = '".$Rut."'";
			$Contenido = $db->select($query);
			$dataSet = array();
			if($Contenido){
				foreach($Contenido as $Contenido){
					$ArrayTmp = array();
					foreach($Fields as $Field){
						$I = $Field['data'];
						$ArrayTmp[$I] = array();
						array_push($ArrayTmp[$I],$Contenido[$I]);
					}
					array_push($dataSet,$ArrayTmp);
				}
			}
			$ToReturn[$Index]['dataSet'] = $dataSet;
			$ToReturn[$Index]['Fields'] = $Fields;
		}

		return $ToReturn;
	}
	function getFonoPrefix($idPais){
		$ToReturn = array();
		$db = new DB();
		$SqlPrefijo = "select * from prefijoFonosPaises where id_pais='".$idPais."'";
		$Prefijo = $db->select($SqlPrefijo);
		if(count($Prefijo) > 0){
			$ToReturn = $Prefijo[0];
		}
		return $ToReturn;
	}

	function insertOperaciones($origen,$nombre,$cliente,$observacion,$tipificacion,$fono,$ori,$sucursal,$destino,$rut){
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		$usuario = "Demo01";
		$db = new DB();
		$sqlCreaRegistro = "INSERT INTO operaciones.`gestion`(`usuario`, `nombre`,`origen`, `cliente`, `tipificacion`, 
		`observacion`, `fecha`, `hora`,`sucursal`,`destino`,`rut`,`ori`,`fono`) VALUES ('$usuario','$nombre','$origen','$cliente','$tipificacion',
		'$observacion','$fecha','$hora','$sucursal','$destino','$rut','$ori','$fono')";
		$db->insert($sqlCreaRegistro);
	}


	public function getGestionOperacion(){
		$mysqli = new DB();
		$query = $mysqli->select("SELECT * FROM operaciones.gestion ");
		echo "<table id='tabla_mis_gestiones'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Origen</th>";
		echo "<th>Nombre Origen</th>";
		echo "<th>Destino</th>";
		echo "<th>Nombre Destino</th>";
		echo "<th>Sucursal</th>";
		echo "<th>Cliente</th>";
		echo "<th>Tipificación</th>";
		echo "<th>Fecha</th>";
		echo "<th>Hora</th>";
		echo "</tr>";
		echo "</thead>";
		foreach($query as $row){
			
			echo "<tr>";
			echo "<th>".$row['origen']."</th>";
			echo "<th>".$row['usuario']."</th>";
			echo "<th>".$row['destino']."</th>";
			echo "<th>".$row['nombre']."</th>";
			echo "<th>".$row['sucursal']."</th>";
			echo "<th>".$row['cliente']."</th>";
			echo "<th>".$row['tipificacion']."</th>";
			echo "<th>".$row['fecha']."</th>";
			echo "<th>".$row['hora']."</th>";
			echo "</tr>";	
			
		}
		echo "</table>";
		
	}

	public function origen($origen){
		$mysqli = new DB();
		if($origen == 'Tripulacion'){
			$query = $mysqli->select("SELECT * FROM operaciones.tripulacion ");
			echo "<label class='control-label'>Seleccione Tripulacion:</label>";
			echo "<select class='selectpicker' id='nombreIn2' name='nombreIn2' data-live-search='true' data-width='100%'>";
			echo "<option value='0'>Seleccione</option>";
			foreach($query as $row){
				$nombre = $row['nombre'];
				$sucursal = $row['sucursal'];
				$data = $nombre."|".$sucursal;
				echo "<option value='$data'>".$sucursal." - ".$nombre."</option>";
			}
			echo "</select>";

		}else if($origen == 'Supervision'){
			$query = $mysqli->select("SELECT * FROM operaciones.supervisor ");
			echo "<label class='control-label'>Seleccione Supervisor:</label>";

			echo "<select class='selectpicker' id='nombreIn2' name='nombreIn2' data-live-search='true' data-width='100%'>";
			echo "<option value='0'>Seleccione</option>";
			foreach($query as $row){
				$nombre = $row['nombre'];
				$sucursal = $row['sucursal'];
				$data = $nombre."|".$sucursal;
				echo "<option value='$data'>".$sucursal." - ".$nombre."</option>";
			}
			echo "</select>";

		}else{
			$query = $mysqli->select("SELECT * FROM operaciones.clientes LIMIT 50");
			echo "<label class='control-label'>Seleccione Cliente:</label>";

			echo "<select class='selectpicker' id='nombreIn2' name='nombreIn2' data-live-search='true' data-width='100%'>";
			echo "<option value='0'>Seleccione</option>";
			foreach($query as $row){
				$nombre = $row['nombre'];
				$sucursal = "N/A";
				$data = $nombre."|".$sucursal;
				echo "<option value='$data'>".$nombre."</option>";
			}
			echo "</select>";

		}
	}
	public function origenIn($origen){
		$mysqli = new DB();
		if($origen == 'Tripulacion'){
			$query = $mysqli->select("SELECT * FROM operaciones.tripulacion ");
			echo "<label class='control-label'>Seleccione Tripulacion:</label>";
			echo "<select class='selectpicker' id='nombreIn3' name='nombreIn3' data-live-search='true' data-width='100%'>";
			echo "<option value='0'>Seleccione</option>";
			foreach($query as $row){
				$nombre = $row['nombre'];
				$sucursal = $row['sucursal'];
				$data = $nombre."|".$sucursal;
				echo "<option value='$data'>".$sucursal." - ".$nombre."</option>";
			}
			echo "</select>";

		}else{
			$query = $mysqli->select("SELECT * FROM operaciones.supervisor ");
			echo "<label class='control-label'>Seleccione Supervisor:</label>";

			echo "<select class='selectpicker' id='nombreIn3' name='nombreIn3' data-live-search='true' data-width='100%'>";
			echo "<option value='0'>Seleccione</option>";
			foreach($query as $row){
				$nombre = $row['nombre'];
				$sucursal = $row['sucursal'];
				$data = $nombre."|".$sucursal;
				echo "<option value='$data'>".$sucursal." - ".$nombre."</option>";
			}
			echo "</select>";

		}
	}
	public function getInvoiceAmount($invoice){
		$mysqli = new DB();
		$assignor = $_SESSION['cedente'];
		$invoices = '';

		foreach($invoice as $value){
			$invoices .= $value.",";
		}

		$invoices = substr($invoices,0,-1);

		$query = $mysqli->select("SELECT SUM(Saldo_ML) as num1 FROM Deuda WHERE Id_Cedente ='$assignor' AND Numero_Factura IN ($invoices)");

		echo $query[0]['num1'];
	}
}
?>
