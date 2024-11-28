<?php
class Tareas
{
	public function asignarTipo($id,$id_cedente)
	{
		$this->id=$id;
		$this->id_cedente=$id_cedente;
	}
	public function mostrarTipo(){
		$db = new DB();
		/*********************************************
		****** estado = 0 -> Estrategia Activa   *****
		****** estado = 1 -> Estrategia Inactiva *****
		*********************************************/
		$ToReturn = array();
		$estrategias = $db->select("SELECT * FROM SIS_Estrategias WHERE tipo=$this->id AND Id_Cedente = '$this->id_cedente' AND estado = 0");
		if($estrategias){
			foreach($estrategias as $estrategia){

				$fecha = new DateTime($estrategia['fecha']);
				$fecha = $fecha->format('d-m-Y');
				$hora = new DateTime($estrategia['hora']);
				$hora = $hora->format('H:i:s');

				$arreglo = array();
				$arreglo['Id_Cedente'] = $estrategia['Id_Cedente'];
				$arreglo['Id_Usuario'] = $estrategia['Id_Usuario'];
				$arreglo['comentario'] = $estrategia['comentario'];
				$arreglo['fecha'] = $fecha;
				$arreglo['grupo'] = $estrategia['grupo'];
				$arreglo['hora'] = $hora;
				$arreglo['id'] = $estrategia['id'];
				$arreglo['nombre'] = $estrategia['nombre'];
				$arreglo['modo_operacion'] = $estrategia['modo_operacion'];
				$arreglo['periodicidad'] = $estrategia['periodicidad'];
				$arreglo['tipo'] = $estrategia['tipo'];
				$arreglo['usuario'] = $estrategia['usuario'];

				array_push($ToReturn,$arreglo);
				
			}
		}
		
		return $ToReturn;
	}

	public function asignarEstrategia($ide)
	{
		$this->ide=$ide;
	}
	public function mostrarEstrategia()
	{	
		$db = new DB();
		$response = array();

		$response = $db->select("SELECT 
									id, cola, cantidad, monto, prioridad, comentario, discador 
								FROM 
									SIS_Querys_Estrategias 
								WHERE  id_estrategia=$this->ide AND terminal = 1");

		return $response;
	}

	public function activarCola($id)
	{
		$this->id = $id;
		$db = new DB();

		$sel_cola = $db->select("SELECT cola, query, Id_Cedente FROM SIS_Querys_Estrategias WHERE id = $this->id");
		if($sel_cola){
			$query 		 = $sel_cola[0]["query"];
			$nombre_cola = $sel_cola[0]["cola"];
			$cedente 	 = $sel_cola[0]["Id_Cedente"];
		}

		$prefijo = "QR_".$cedente."_".$this->id;

		//$ver_prefijo = $db->select("SHOW TABLES LIKE '$prefijo'");
		$ver_prefijo = $db->select("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like '$prefijo'");

		if(count($ver_prefijo) > 0){
			echo "1";
		}else{
			$crear = "CREATE TABLE $prefijo (
							id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
							Rut int,
							llamado INT DEFAULT 0,
							INDEX(Rut))";
			$db->query($crear);
			$db->query("INSERT INTO $prefijo (Rut) $query");
			$db->query("UPDATE SIS_Querys_Estrategias SET discador = 1 WHERE id = $this->id");
		}
	}

	public function desactivarCola($id)
	{
		$db 		= new DB();
		$this->id 	= $id;
		
		$sel_cola = $db->select("SELECT cola, query, Id_Cedente FROM SIS_Querys_Estrategias WHERE id = $this->id");

		if($sel_cola){
			$nombre_cola = $sel_cola[0]["cola"];
			$query 		 = $sel_cola[0]["query"];
			$cedente 	 = $sel_cola[0]["Id_Cedente"];
		}

		$prefijo = "QR_".$cedente."_".$this->id;
		$ver_prefijo = $db->select("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like '$prefijo'");

		if (count($ver_prefijo) > 0){
			$query = "DROP TABLE $prefijo";
 			$db->query($query);
			$db->query("UPDATE SIS_Querys_Estrategias SET discador = 0 WHERE id = $this->id");
 			echo "1";
		}else{
			echo "0";
		}
 	}

 	public function actualizarCola($Cola = ""){
		$db 		= new DB();
		$WhereCola = $Cola != "" ? " and id='".$Cola."'" : "";
		$query_discador = $db->select("SELECT 
											id, query, Id_Cedente, cola 
										FROM 
											SIS_Querys_Estrategias 
										WHERE 
											discador = 1 " . $WhereCola);
		
		if($query_discador){
			foreach($query_discador as $datos){
				$id 	= $datos["id"];
				$query 	= $datos["query"];
				$cedente = $datos["Id_Cedente"];
				$nombre_cola = $datos["cola"];
				$prefijo 	= "QR_".$cedente."_".$id;

				$db->query("TRUNCATE TABLE $prefijo");

				$MysqlIndex = $db->select("SELECT case when ((SELECT COUNT(*) FROM information_schema.statistics WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '' AND INDEX_NAME = 'rut') = 0) then 'ALTER TABLE $prefijo ADD UNIQUE KEY rut (Rut)' else '' end as IndexSql");
				if($MysqlIndex){
					foreach($MysqlIndex as $index){
						if($index["IndexSql"] !== ""){
							$db->query($index["IndexSql"]);
						}
					}

					$db->query("INSERT IGNORE INTO $prefijo (Rut) $query");
					$db->query("DELETE FROM $prefijo WHERE  NOT Rut IN ($query)");
				}
				/* $MSSQLIndex = $db->select("SELECT CASE WHEN ((SELECT count(*)
				FROM sys.indexes AS i
				INNER JOIN sys.index_columns AS ic 
					ON i.object_id = ic.object_id AND i.index_id = ic.index_id
				WHERE i.object_id = OBJECT_ID('$prefijo') and COL_NAME(ic.object_id,ic.column_id) = 'Rut') = 0) THEN 'ALTER TABLE $prefijo ADD UNIQUE (Rut)' ELSE '' END as IndexSql");
				if($MSSQLIndex){
					foreach($MSSQLIndex as $index){
						if($index["IndexSql"] !== ""){
							$db->query($index["IndexSql"]);
						}
					}

					$db->query("INSERT INTO $prefijo (Rut) $query");
					$db->query("DELETE FROM $prefijo WHERE  NOT Rut IN ($query)");
				} */
			}
		}
	}
	public function actualizarColaAuto($Cola){
		$db = new DB();
		$query_discador = $db->select("SELECT id, query, Id_Cedente, cola FROM SIS_Querys_Estrategias WHERE discador = 1 AND id = $Cola");
		
		if($query_discador){
			foreach($query_discador as $datos){
				$id 	= $datos["id"];
				$query 	= $datos["query"];
				$cedente = $datos["Id_Cedente"];
				$nombre_cola = $datos["cola"];
				$prefijo 	= "QR_".$cedente."_".$id;

				$db->query("TRUNCATE TABLE $prefijo");

				$MysqlIndex = $db->select("SELECT case when ((SELECT COUNT(*) FROM information_schema.statistics WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '' AND INDEX_NAME = 'rut') = 0) then 'ALTER TABLE $prefijo ADD UNIQUE KEY rut (Rut)' else '' end as IndexSql");
				if($MysqlIndex){
					foreach($MysqlIndex as $index){
						if($index["IndexSql"] !== ""){
							$db->query($index["IndexSql"]);
						}
					}

					$db->query("INSERT IGNORE INTO $prefijo (Rut) $query");
					$db->query("DELETE FROM $prefijo WHERE  NOT Rut IN ($query)");
				}
			}
		}
	}

	function getEntidades($TipoEntidad, $Array){
		$db = new Db();
		$ToReturn = "";
		$In = "";
		switch($TipoEntidad){
			case '1':
				if($Array != ""){
					$In =  "AND empresa_externa.IdEmpresaExterna NOT IN (".$Array.")";
				}
				$sql = "SELECT * FROM empresa_externa WHERE IdCedente = '".$_SESSION['cedente']."' ".$In."";
				$Supervisores = $db->select($sql);
				foreach($Supervisores as $Supervisor){
					$ToReturn .= "<option value='EE_".$Supervisor["IdEmpresaExterna"]."'>".$Supervisor["Nombre"]."</option>";
				}
			break;
			case '2':
				if($Array != ""){
					$In =  "AND Personal.Id_Personal NOT IN (".$Array.")";
				}
				$ToReturn .= "<optgroup label='Supervisor'>";
					//echo $Array;
					//echo implode(",",$Array);
					$sql = "SELECT Personal.* FROM Usuarios INNER JOIN Personal ON Personal.Nombre_Usuario = Usuarios.usuario WHERE Usuarios.nivel = '2' ".$In."";
					$Supervisores = $db->select($sql);
					foreach($Supervisores as $Supervisor){
						$ToReturn .= "<option value='S_".$Supervisor["Id_Personal"]."'>".$Supervisor["Nombre"]."</option>";
					}
				$ToReturn .= "</optgroup>";
				$ToReturn .= "<optgroup label='Ejecutivo'>";
					$sql = "SELECT Personal.* FROM Usuarios INNER JOIN Personal ON Personal.Nombre_Usuario = Usuarios.usuario WHERE Usuarios.nivel = '4' ".$In."";
					$Supervisores = $db->select($sql);
					foreach($Supervisores as $Supervisor){
						if($Supervisor["Nombre"] != ""){
							$ToReturn .= "<option value='E_".$Supervisor["Id_Personal"]."'>".$Supervisor["Nombre"]."</option>";
						}
					}
				$ToReturn .= "</optgroup>";
			break;
			case '3':
				if($Array != ""){
					$In =  "AND grupos.IdGrupo NOT IN (".$Array.")";
				}
				$sql = "SELECT * FROM grupos WHERE IdCedente = '".$_SESSION['cedente']."' ".$In."";
				$Supervisores = $db->select($sql);
				if($Supervisores){
					foreach($Supervisores as $Supervisor){
						$ToReturn .= "<option value='G_".$Supervisor["IdGrupo"]."'>".$Supervisor["Nombre"]."</option>";
					}
				}
			break;
		}
		return $ToReturn;
	}
	function SeparateByRuts($idCola, $Rows, $DropTables = true){
		$Algoritmo = "0";
		$db = new DB();
		$Cedente = $_SESSION['cedente'];
		$SqlCola = "SELECT
						A.Rut,
						Sum(D.Deuda) AS Deuda
					FROM 
						QR_".$Cedente."_".$idCola." A 
					INNER JOIN 
						Deuda D
					ON 
						A.Rut = D.Rut
					LEFT JOIN 
						Ultima_Gestion_Historica U
					ON 
						A.Rut = U.Rut 
					WHERE
						Deuda > 0
					AND
						D.Id_Cedente = '".$Cedente."' 
					GROUP BY
						A.Rut,
						U.fechahora
					ORDER BY
						U.fechahora ASC";
		$Ruts = $db->select($SqlCola);
		$NumRuts = count($Ruts);
		$CantRutsAvailable = $NumRuts;
		$ArrayAsignacion = array();
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		if($DropTables){
			$this->DeleteTablesFromCola($Prefix);
		}
		foreach($Rows as $Row){
			$Nombre = $Row[0];
			$Porcentaje = $Row[1];
			$Porcentaje = $Porcentaje / 100;
			$Foco = 1;
			$Id = $Row[2];
			$TotalRuts = ceil($NumRuts * $Porcentaje);
			if($CantRutsAvailable <= $TotalRuts){
				$TotalRuts = $CantRutsAvailable;
			}
			$CantRutsAvailable = $CantRutsAvailable - $TotalRuts;
			$ArrayAsignacion[$Id]["Porcentaje"] = $Porcentaje * 100;
			$ArrayAsignacion[$Id]["TotalRuts"] = $TotalRuts;
			$ArrayAsignacion[$Id]["Ruts"] = array();
			$Cont = 1;
			foreach($Ruts as $Key => $Rut){
				if($Cont <= $TotalRuts){
					array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
					$Cont++;
					unset($Ruts[$Key]);
				}else{
					break;
				}
			}
			$TableName = "QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco;
			$asignacion = "QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco;
			$ArrayAsignacion[$Id]["Table"] = $asignacion;
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			$Cont = 1;
			$Cont1000 = 0;
			$Orden = 1;
			$ArrayValues = array();
			$ArrayValues[$Cont1000] = array();
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
				array_push($ArrayValues[$Cont1000],"(".$Rut.",'".$Orden."')");
				$Orden++;
				$Cont++;
				if($Cont == 1000){
					$Cont = 1;
					$Cont1000++;
					$ArrayValues[$Cont1000] = array();
				}
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			if($DropTables){
				$crear = "CREATE TABLE $TableName (
								id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
								Rut int, 
								estado INT NOT NULL DEFAULT 0, 
								orden INT, 
								fechaGestion datetime,
								llamado INT DEFAULT 0,
								id_usuario INT DEFAULT 0,
								estado_cola INT DEFAULT 2,
								fechaRellamar datetime,
								INDEX(Rut))";
				$InsertTable = $db->query($crear);
				if($InsertTable){
					/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
					$Insert = $db->query($SqlInsert); */
					foreach($ArrayValues as $Values){
						$Implode = implode(",",$Values);
						$SqlInsert = "INSERT INTO $TableName (Rut,orden) values ".$Implode;
						$Insert = $db->query($SqlInsert);
					}
				}

				//INSERT en tabla asignacion_cola
				$insert = "INSERT INTO asignacion_cola (id_cola, asignacion) VALUES ('" . $idCola . "', '" . $asignacion . "')";
				$db->query($insert);
			}else{
				/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert); */
				foreach($ArrayValues as $Values){
					$Implode = implode(",",$Values);
					$SqlInsert = "INSERT INTO $TableName (Rut) values ".$Implode;
					$Insert = $db->query($SqlInsert);
				}
			}
		}
		$Tipos = array();
		/*$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts);
			array_push($Tipos["Tipo1"],$File[$key]);
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts);
			array_push($Tipos["Tipo2"],$File[$key]);
		}*/
		$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File["fileName"] = $this->getEntidadName($key);
				$File["Table"] = $Entidad["Table"];
			array_push($Tipos["Tipo2"],$File);
		}
		echo json_encode($Tipos);
	}
	function SeparateByDeuda($idCola, $Rows, $DropTables = true){
		$Algoritmo = "1";
		$db = new DB();
		$Rows = array_sort($Rows, 1, SORT_DESC);
		$Cedente = $_SESSION['cedente'];
		$SqlCola = "SELECT 
						Rut AS Rut, 
						Sum(Deuda) AS Deuda 
					FROM 
						Deuda 
					WHERE 
						Id_Cedente = '".$Cedente."' 
					AND 
						Rut IN 	(SELECT Rut FROM QR_".$Cedente."_".$idCola.")
					AND
						Deuda > 0 
					GROUP BY 
						Rut 
					ORDER BY 
						Deuda DESC";
		$Deudas = $db->select($SqlCola);
		$CantTotalDeudas = $this->DeudaTotal($Deudas);
		$CantDeudasAvailable = $CantTotalDeudas;
		$ArrayAsignacion = array();
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		if($DropTables){
			$this->DeleteTablesFromCola($Prefix);
		}
		foreach($Rows as $Row){
			$Nombre = $Row[0];
			$Porcentaje = $Row[1];
			$Porcentaje = $Porcentaje / 100;
			$Foco = 1;
			$Id = $Row[2];
			$TotalDeudas = ($CantTotalDeudas * $Porcentaje);
			if($CantDeudasAvailable <= $TotalDeudas){
				$TotalDeudas = $CantDeudasAvailable;
			}
			$ArrayAsignacion[$Id]["Ruts"] = array();
			$SumDeuda = 0;
			foreach($Deudas as $Key => $Deuda){
				if($SumDeuda <= $TotalDeudas){
					$SumDeuda += $Deuda["Deuda"];
					array_push($ArrayAsignacion[$Id]["Ruts"],$Deuda["Rut"]);
					unset($Deudas[$Key]);
				}else{
					break;
				}
			}
			$TableName = "QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."";
			$asignacion = "QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco;
			$ArrayAsignacion[$Id]["Table"] = $asignacion;
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			$Cont = 1;
			$Orden = 1;
			$Cont1000 = 0;
			$ArrayValues = array();
			$ArrayValues[$Cont1000] = array();
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
				array_push($ArrayValues[$Cont1000],"(".$Rut.",'".$Orden."')");
				$Orden++;
				$Cont++;
				if($Cont == 1000){
					$Cont = 1;
					$Cont1000++;
					$ArrayValues[$Cont1000] = array();
				}
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			if($DropTables){
				$crear = "CREATE TABLE $TableName (
								id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
								Rut int, 
								estado INT NOT NULL DEFAULT 0, 
								orden INT, 
								fechaGestion datetime,
								llamado INT DEFAULT 0,
								id_usuario INT DEFAULT 0,
								estado_cola INT DEFAULT 2,
								fechaRellamar datetime,
								INDEX(Rut))";
				$InsertTable = $db->query($crear);
				if($InsertTable){
					/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
					$Insert = $db->query($SqlInsert); */
					foreach($ArrayValues as $Values){
						$Implode = implode(",",$Values);
						$SqlInsert = "INSERT INTO $TableName (Rut,orden) values ".$Implode;
						$Insert = $db->query($SqlInsert);
					}
				}

				//INSERT en tabla asignacion_cola
				$insert = "INSERT INTO asignacion_cola (id_cola, asignacion) VALUES ('" . $idCola . "', '" . $asignacion . "')";
				$db->query($insert);
			}else{
				/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert); */
				foreach($ArrayValues as $Values){
					$Implode = implode(",",$Values);
					$SqlInsert = "INSERT INTO $TableName (Rut) values ".$Implode;
					$Insert = $db->query($SqlInsert);
				}
			}
		}
		$Tipos = array();
		/*$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts);
			array_push($Tipos["Tipo1"],$File[$key]);
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts);
			array_push($Tipos["Tipo2"],$File[$key]);
		}*/
		$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File["fileName"] = $this->getEntidadName($key);
				$File["Table"] = $Entidad["Table"];
			array_push($Tipos["Tipo2"],$File);
		}
		echo json_encode($Tipos);
	}
	function SeparateByDeudaAuto($idCola, $Rows, $DropTables = true,$Cedente){
		$Algoritmo = "1";
		$db = new DB();
		//$Rows = array_sort($Rows, 1, SORT_DESC);
		$SqlCola = "SELECT 
						Rut AS Rut, 
						Sum(Deuda) AS Deuda 
					FROM 
						Deuda 
					WHERE 
						Id_Cedente = '".$Cedente."' 
					AND 
						Rut IN 	(SELECT Rut FROM QR_".$Cedente."_".$idCola.")
					AND
						Deuda > 0 
					GROUP BY 
						Rut 
					ORDER BY 
						Deuda DESC";
		$Deudas = $db->select($SqlCola);
		$CantTotalDeudas = $this->DeudaTotal($Deudas);
		$CantDeudasAvailable = $CantTotalDeudas;
		$ArrayAsignacion = array();
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		if($DropTables){
			$this->DeleteTablesFromCola($Prefix);
		}
		foreach($Rows as $Row){
			$Nombre = $Row['Nombre'];
			$Porcentaje = $Row['Porcentaje'];
			$Porcentaje = $Porcentaje / 100;
			$Foco = 1;
			$Id = $Row['id'];
			$TotalDeudas = ($CantTotalDeudas * $Porcentaje);
			if($CantDeudasAvailable <= $TotalDeudas){
				$TotalDeudas = $CantDeudasAvailable;
			}
			$ArrayAsignacion[$Id]["Ruts"] = array();
			$SumDeuda = 0;
			foreach($Deudas as $Key => $Deuda){
				if($SumDeuda <= $TotalDeudas){
					$SumDeuda += $Deuda["Deuda"];
					array_push($ArrayAsignacion[$Id]["Ruts"],$Deuda["Rut"]);
					unset($Deudas[$Key]);
				}else{
					break;
				}
			}
			$TableName = "QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."";
			$asignacion = "QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco;
			$ArrayAsignacion[$Id]["Table"] = $asignacion;
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			$Cont = 1;
			$Orden = 1;
			$Cont1000 = 0;
			$ArrayValues = array();
			$ArrayValues[$Cont1000] = array();
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
				array_push($ArrayValues[$Cont1000],"(".$Rut.",'".$Orden."')");
				$Orden++;
				$Cont++;
				if($Cont == 1000){
					$Cont = 1;
					$Cont1000++;
					$ArrayValues[$Cont1000] = array();
				}
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			if($DropTables){
				$crear = "CREATE TABLE $TableName (
								id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
								Rut int, 
								estado INT NOT NULL DEFAULT 0, 
								orden INT, 
								fechaGestion datetime,
								llamado INT DEFAULT 0,
								id_usuario INT DEFAULT 0,
								estado_cola INT DEFAULT 2,
								fechaRellamar datetime,
								INDEX(Rut))";
				$InsertTable = $db->query($crear);
				if($InsertTable){
					/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
					$Insert = $db->query($SqlInsert); */
					foreach($ArrayValues as $Values){
						$Implode = implode(",",$Values);
						$SqlInsert = "INSERT INTO $TableName (Rut,orden) values ".$Implode;
						$Insert = $db->query($SqlInsert);
					}
				}

				//INSERT en tabla asignacion_cola
				$insert = "INSERT INTO asignacion_cola (id_cola, asignacion) VALUES ('" . $idCola . "', '" . $asignacion . "')";
				$db->query($insert);
			}else{
				/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert); */
				foreach($ArrayValues as $Values){
					$Implode = implode(",",$Values);
					$SqlInsert = "INSERT INTO $TableName (Rut) values ".$Implode;
					$Insert = $db->query($SqlInsert);
				}
			}
		}
		$Tipos = array();
		/*$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts);
			array_push($Tipos["Tipo1"],$File[$key]);
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts);
			array_push($Tipos["Tipo2"],$File[$key]);
		}*/
		$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File["fileName"] = $this->getEntidadName($key);
				$File["Table"] = $Entidad["Table"];
			array_push($Tipos["Tipo2"],$File);
		}
		echo json_encode($Tipos);
	}
	function DeudaTotal($Deudas){
		$ToReturn = 0;
		foreach($Deudas as $Deuda){
			$ToReturn += $Deuda["Deuda"];
		}
		return $ToReturn;
	}
	function DeleteTablesFromCola($Prefix){
		$db = new DB();
		$SqlTables = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like   '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		if(count($Tables) > 0){
			foreach($Tables as $Table){
				$Tabla = $Table["TABLE_NAME"];
				$Sql = "drop table ".$Tabla."";
				$db->query($Sql);

				//DELETE FROM asignacion cola
				$sql = "DELETE FROM asignacion_cola WHERE asignacion = '" . $Tabla . "'";
				$db->query($sql);
			}
		}
	}
	
	function CrearArchivoAsignacion($fileName,$Ruts,$Cedente = "",$Cola = "",$Download = true){
		$objPHPExcel = new PHPExcel();
		$db = new DB();
		if($Cedente == ""){
			$Cedente = $_SESSION['cedente'];
		}
		$fileName = $this->getEntidadName($fileName)."_Foco";
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
		$objPHPExcel->getActiveSheet()->setTitle('Personas');

		$objPHPExcel->
		setActiveSheetIndex($NextSheet)
                ->setCellValueByColumnAndRow(0,1,"Rut")
				->setCellValueByColumnAndRow(1,1,"DV")
				->setCellValueByColumnAndRow(2,1,"Nombre Completo");

		$RutsImplode = implode(",",$Ruts);
		if($RutsImplode != ""){
			$SqlPersonas = "select * from Persona where Rut in (".$RutsImplode.")";
			$Personas = $db->select($SqlPersonas);
			
			$Cont = 2;
			foreach($Personas as $Persona){
				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,$Cont,$Persona["Rut"])
						->setCellValueByColumnAndRow(1,$Cont,$Persona["Digito_Verificador"])
						->setCellValueByColumnAndRow(2,$Cont,$Persona["Nombre_Completo"]);
				$Cont++;
			}
		}

		$NextSheet++;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Deudas');

		$objPHPExcel->
		setActiveSheetIndex($NextSheet)
                ->setCellValueByColumnAndRow(0,1,"Rut")
				->setCellValueByColumnAndRow(1,1,"Tipo Deudor")
				->setCellValueByColumnAndRow(2,1,"Producto")
				->setCellValueByColumnAndRow(3,1,"Numero Operacion")
				->setCellValueByColumnAndRow(4,1,"Segmento")
				->setCellValueByColumnAndRow(5,1,"Tramo Dias Mora")
				->setCellValueByColumnAndRow(6,1,"Fecha Vencimiento")
				->setCellValueByColumnAndRow(7,1,"Monto Mora")
				->setCellValueByColumnAndRow(8,1,"Dias Mora")
				->setCellValueByColumnAndRow(9,1,"Fecha Ingreso")
				->setCellValueByColumnAndRow(10,1,"Cuenta");

		if($RutsImplode != ""){
			$SqlDeudas = "select * from Deuda where Rut in (".$RutsImplode.") and Id_Cedente = '".$Cedente."'";
			$Deudas = $db->select($SqlDeudas);
			
			$Cont = 2;
			foreach($Deudas as $Deuda){
				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,$Cont,$Deuda["Rut"])
						->setCellValueByColumnAndRow(1,$Cont,$Deuda["Tipo_Deudor"])
						->setCellValueByColumnAndRow(2,$Cont,$Deuda["Producto"])
						->setCellValueByColumnAndRow(3,$Cont,$Deuda["Numero_Operacion"])
						->setCellValueByColumnAndRow(4,$Cont,$Deuda["Segmento"])
						->setCellValueByColumnAndRow(5,$Cont,$Deuda["Tramo_Dias_Mora"])
						->setCellValueByColumnAndRow(6,$Cont,$Deuda["Fecha_Vencimiento"])
						->setCellValueByColumnAndRow(7,$Cont,$Deuda["Deuda"])
						->setCellValueByColumnAndRow(8,$Cont,$Deuda["Dias_Mora"])
						->setCellValueByColumnAndRow(9,$Cont,$Deuda["Fecha_Ingreso"])
						->setCellValueByColumnAndRow(10,$Cont,$Deuda["Cuenta"]);
				$Cont++;
			}
		}

		$NextSheet++;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Fonos');

		$objPHPExcel->
		setActiveSheetIndex($NextSheet)
                ->setCellValueByColumnAndRow(0,1,"Rut")
				->setCellValueByColumnAndRow(1,1,"Tipo Fono")
				->setCellValueByColumnAndRow(2,1,"Fono");

		if($RutsImplode != ""){
			$SqlFonos = "select * from fono_cob where Rut in (".$RutsImplode.")";
			$Fonos = $db->select($SqlFonos);
			
			$Cont = 2;
			foreach($Fonos as $Fono){
				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,$Cont,$Fono["Rut"])
						->setCellValueByColumnAndRow(1,$Cont,$Fono["tipo_fono"])
						->setCellValueByColumnAndRow(2,$Cont,$Fono["formato_subtel"]);
				$Cont++;
			}
		}

		$NextSheet++;

		if($RutsImplode != ""){
			$SqlDirecciones = "select * from Direcciones where Rut in (".$RutsImplode.")";
			$Direcciones = $db->select($SqlDirecciones);
			if(count($Direcciones) > 0){
				$objPHPExcel->createSheet($NextSheet);
				$objPHPExcel->setActiveSheetIndex($NextSheet);
				$objPHPExcel->getActiveSheet()->setTitle('Direcciones');

				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,1,"Rut")
						->setCellValueByColumnAndRow(1,1,"Direccion")
						->setCellValueByColumnAndRow(2,1,"Codigo Postal")
						->setCellValueByColumnAndRow(3,1,"Complemento Direccion");

				$Cont = 2;
				foreach($Direcciones as $Direccion){
					$objPHPExcel->
					setActiveSheetIndex($NextSheet)
							->setCellValueByColumnAndRow(0,$Cont,$Direccion["Rut"])
							->setCellValueByColumnAndRow(1,$Cont,$Direccion["Direccion"])
							->setCellValueByColumnAndRow(2,$Cont,$Direccion["Complemento_Direccion"])
							->setCellValueByColumnAndRow(3,$Cont,$Direccion["Codigo_postal"]);
					$Cont++;
				}

				$NextSheet++;
			}
		}

		if($RutsImplode != ""){
			$SqlMails = "select * from Mail where Rut in (".$RutsImplode.")";
			$Mails = $db->select($SqlMails);
			if(count($Mails) > 0){
				$objPHPExcel->createSheet($NextSheet);
				$objPHPExcel->setActiveSheetIndex($NextSheet);
				$objPHPExcel->getActiveSheet()->setTitle('Mails');

				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,1,"Rut")
						->setCellValueByColumnAndRow(1,1,"Correo Electronico");

				$Cont = 2;
				foreach($Mails as $Mail){
					$objPHPExcel->
					setActiveSheetIndex($NextSheet)
							->setCellValueByColumnAndRow(0,$Cont,$Mail["Rut"])
							->setCellValueByColumnAndRow(1,$Cont,$Mail["correo_electronico"]);
					$Cont++;
				}

				$NextSheet++; 
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0);

		if($Download){
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();
			$response =  array(
				'fileName' => utf8_encode($fileName),
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);
		}else{
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			if (!file_exists("../task/asignaciones/".$Cedente."/".$Cola)){
				mkdir("../task/asignaciones/".$Cedente."/".$Cola, 0777, true);
			}
			$objWriter->save('../task/asignaciones/'.$Cedente."/".$Cola.'/'.$fileName.".xlsx");
			$response = array("result" => true);
		}
		
		
		
		return $response;
	}
	function getAsignaciones($idCola){
		$ToReturn = array();
		$db = new DB();
		$Cedente = $_SESSION['cedente'];
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		$SqlTables = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like  '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		foreach($Tables as $Table){
			$Array = array();
			$Tabla = $Table["TABLE_NAME"];
			$ArrayTabla = explode("_",$Tabla);
			$PrefijoTabla = $ArrayTabla[0];
			$Cedente = $ArrayTabla[1];
			$Cola = $ArrayTabla[2];
			$TipoEntidad = $ArrayTabla[3];
			$idEntidad = $ArrayTabla[4];
			$Porcentaje = $ArrayTabla[5];
			$TipoAsignacion = $ArrayTabla[6];
			$Foco = $ArrayTabla[7];
			$Entidad = $TipoEntidad."_".$idEntidad;
			$Nombre = "";
			$Tipo = "";
			switch($TipoEntidad){
				case 'S':
				case 'E':
					$SqlNombre = "select * from Personal where Id_Personal='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					if($Nombre){
						$Nombre = $Nombre[0]["Nombre"];
					}else{
						$Nombre = '';
					}
					$Tipo = "Personal";
				break;
				case 'EE':
					$SqlNombre = "select * from empresa_externa where IdEmpresaExterna='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					if($Nombre){
						$Nombre = $Nombre[0]["Nombre"];
					}else{
						$Nombre = '';
					}
					$Tipo = "Empresa Externa";
				break;
				case 'G':
					$SqlNombre = "select * from grupos where IdGrupo='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					if($Nombre){
						$Nombre = $Nombre[0]["Nombre"];
					}else{
						$Nombre = '';
					}
					$Tipo = "Grupo";
				break;
			}
			$Array["Nombre"] = utf8_encode($Nombre);
			$Array["Tipo"] = $Tipo;
			$Array["Porcentaje"] = $Porcentaje;
			$Array["id"] = $Entidad;
			$Array["Foco"] = $Foco;
			$Array["Actions"] = $Entidad;
			array_push($ToReturn,$Array);
		}
		return $ToReturn;
	}
	function getAsignacionesAuto($idCola,$Cedente){
		$ToReturn = array();
		$db = new DB();
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		$SqlTables = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like  '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		foreach($Tables as $Table){
			$Array = array();
			$Tabla = $Table["TABLE_NAME"];
			$ArrayTabla = explode("_",$Tabla);
			$PrefijoTabla = $ArrayTabla[0];
			$Cedente = $ArrayTabla[1];
			$Cola = $ArrayTabla[2];
			$TipoEntidad = $ArrayTabla[3];
			$idEntidad = $ArrayTabla[4];
			$Porcentaje = $ArrayTabla[5];
			$TipoAsignacion = $ArrayTabla[6];
			$Foco = $ArrayTabla[7];
			$Entidad = $TipoEntidad."_".$idEntidad;
			$Nombre = "";
			$Tipo = "";
			switch($TipoEntidad){
				case 'S':
				case 'E':
					$SqlNombre = "select * from Personal where Id_Personal='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					if($Nombre){
						$Nombre = $Nombre[0]["Nombre"];
					}else{
						$Nombre = '';
					}
					$Tipo = "Personal";
				break;
				case 'EE':
					$SqlNombre = "select * from empresa_externa where IdEmpresaExterna='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					if($Nombre){
						$Nombre = $Nombre[0]["Nombre"];
					}else{
						$Nombre = '';
					}
					$Tipo = "Empresa Externa";
				break;
				case 'G':
					$SqlNombre = "select * from grupos where IdGrupo='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					if($Nombre){
						$Nombre = $Nombre[0]["Nombre"];
					}else{
						$Nombre = '';
					}
					$Tipo = "Grupo";
				break;
			}
			$Array["Nombre"] = utf8_encode($Nombre);
			$Array["Tipo"] = $Tipo;
			$Array["Porcentaje"] = $Porcentaje;
			$Array["id"] = $Entidad;
			$Array["Foco"] = $Foco;
			$Array["Actions"] = $Entidad;
			array_push($ToReturn,$Array);
		}
		return $ToReturn;
	}
	function getAsignacionesArchivos($idCola,$Tipo,$Prefix = ""){
		$ToReturn = array();
		$db = new DB();
		$Cedente = $_SESSION['cedente'];
		if($Prefix == ""){
			$Prefix = "QR_".$Cedente."_".$idCola."_";
		}
		//$Prefix = "QR_".$Cedente."_".$idCola."_";
		$SqlTables = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		$File = "";
		foreach($Tables as $Table){
			$Array = array();
			$Tabla = "".$Table["TABLE_NAME"]."";
			$ArrayTabla = explode("_",$Tabla);
			$PrefijoTabla = $ArrayTabla[0];
			$Cedente = $ArrayTabla[1];
			$Cola = $ArrayTabla[2];
			$TipoEntidad = $ArrayTabla[3];
			$idEntidad = $ArrayTabla[4];
			$Porcentaje = $ArrayTabla[5];
			$TipoAsignacion = $ArrayTabla[6];
			$Foco = $ArrayTabla[7];
			$Entidad = $TipoEntidad."_".$idEntidad;
			$SqlTabla = "select Rut from ".$Tabla;
			$Ruts = $db->select($SqlTabla);
			$RutsTmp = array();
			foreach($Ruts as $Rut){
				array_push($RutsTmp,$Rut["Rut"]);
			}
			$ToReturn[$Entidad] = array();
			switch($Tipo){
				case '1':
					//$File = $this->CrearArchivoAsignacion($Entidad,$RutsTmp);
				break;
				case '2':
					$File = $this->CrearArchivoAsignacionTipo2($Entidad,$RutsTmp);
				break;
			}
			//array_push($ToReturn[$Entidad],$File);
		}
		return $File;
	}
	function CrearArchivoAsignacionTipo2($fileName,$Ruts,$Cedente = "",$Cola = "",$Download = true){
		$db = new DB();
		if($Cedente == ""){
			$Cedente = $_SESSION['cedente'];
		}
		$fileName = $this->getEntidadName($fileName)."_Dial";

		$Rows = "";

		$NextSheet = 0;

		$Sql = "select * from Columnas_Asignacion_Dial where Id_Mandante in (select Id_Mandante from mandante_cedente where Id_Cedente='".$Cedente."') ORDER BY Prioridad";
		$ColumnasAsignacion = $db->select($Sql);
		$Col = 0;
		$ArrayStackedColumns = array();
		foreach($ColumnasAsignacion as $ColumnaAsignacion){
			$Rows .= $ColumnaAsignacion["Nombre"].";";
			if($ColumnaAsignacion["Tipo_Campo"] == "1"){
				array_push($ArrayStackedColumns,$ColumnaAsignacion["Campo"]);
			}
			$Col++;
		}

		$SqlColumnas = "select SIS_Columnas_Estrategias.columna from SIS_Tablas inner join SIS_Columnas_Estrategias on SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id where SIS_Tablas.nombre = 'Deuda' and FIND_IN_SET('".$Cedente."',SIS_Columnas_Estrategias.Id_Cedente) order by SIS_Columnas_Estrategias.columna";
		$Columnas = $db->select($SqlColumnas);

		$ArrayDeudaSearch = array();
		foreach($Columnas as $Columna){
			$key = array_search($Columna["columna"],$ArrayStackedColumns);
			if($key === FALSE){
				/*$objPHPExcel->
				setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$Columna["columna"]);
				$Col++;*/
				array_push($ArrayDeudaSearch,$Columna['columna']);
			}
		}
		
		$ArrayDeudaSearchImplode = implode(",",$ArrayDeudaSearch);
		$Cont = 2;
		//$Rows .= "\r\n";
		foreach($Ruts as $Rut){
			$Rows .= "\r\n";	
			$SqlFonos = "select fono_cob.*, SIS_Categoria_Fonos.tipo_var as Gestion from SIS_Categoria_Fonos inner join fono_cob on fono_cob.color = SIS_Categoria_Fonos.color where fono_cob.rut = '".$Rut."' and SIS_Categoria_Fonos.sel='0' order by SIS_Categoria_Fonos.prioridad LIMIT 3";
			$Fonos = $db->select($SqlFonos);
			$FonosTmp = array();
			foreach($Fonos as $Fono){
				array_push($FonosTmp,$Fono["formato_subtel"]."_".$Fono["Gestion"]);
			}
			$FonoEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],0,strpos($FonosTmp[0],"_")) : "";
			$GestionEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strpos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";
			$ColorEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strripos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";

			$Fono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],0,strpos($FonosTmp[1],"_")) : "";
			$Gestion2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strpos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";
			$ColorFono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strripos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";

			$Fono3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],0,strpos($FonosTmp[2],"_")) : "";
			$Gestion3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],strpos($FonosTmp[2],"_") + 1,strlen($FonosTmp[2])) : "";
			$ColorFono3 = isset($FonosTmp[3]) ? substr($FonosTmp[3],strripos($FonosTmp[3],"_") + 1,strlen($FonosTmp[3])) : "";
			
			$SqlMejorGestion = "select * from Mejor_Gestion_Cedente where Rut='".$Rut."' order by fechahora DESC LIMIT 1";
			$MejorGestion = $db->select($SqlMejorGestion);
			$MejorGestionTexto = "";
			$MejorGestionFecha = "";
			$MejorGestionN1 = "";
			$MejorGestionN2 = "";
			$MejorGestionN3 = "";
			$MejorGestionFechaAgendamiento = "";
			$MejorGestionFechaCompromiso = "";
			$MejorGestionFono = "";
			if(count($MejorGestion) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$MejorGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if(count($TipoContacto) > 0){
					$MejorGestionTexto = $TipoContacto[0]["Nombre"];
					$MejorGestionFecha = $MejorGestion[0]["fechahora"];
					$MejorGestionN1 = isset($MejorGestion[0]["n1"]) ? $MejorGestion[0]["n1"] : "";
					$MejorGestionN2 = isset($MejorGestion[0]["n2"]) ? $MejorGestion[0]["n2"] : "";
					$MejorGestionN3 = isset($MejorGestion[0]["n3"]) ? $MejorGestion[0]["n3"] : "";
					$MejorGestionFechaAgendamiento = isset($MejorGestion[0]["fecha_agendamiento"]) ? $MejorGestion[0]["fecha_agendamiento"] : "";
					$MejorGestionFechaCompromiso = isset($MejorGestion[0]["fecha_compromiso"]) ? $MejorGestion[0]["fecha_compromiso"] : "";
					$MejorGestionFono = $MejorGestion[0]["fono_discado"];
				}
			}

			$SqlUltimaGestion = "select * from Ultima_Gestion_Historica where Rut='".$Rut."'  order by fechahora DESC LIMIT 1";
			$UltimaGestion = $db->select($SqlUltimaGestion);
			$UltimaGestionTexto = "";
			$UltimaGestionFecha = "";
			$UltimaGestionObservacion = "";
			$UltimaGestionUsuario = "";
			$UltimaGestionN1 = "";
			$UltimaGestionN2 = "";
			$UltimaGestionN3 = "";
			$UltimaGestionFechaAgendamiento = "";
			$UltimaGestionFechaCompromiso = "";
			if(count($UltimaGestion) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimaGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				$UltimaGestionObservacion = "";
				if(count($TipoContacto) > 0){
					$UltimaGestionTexto = $TipoContacto[0]["Nombre"];
					$UltimaGestionFecha = $UltimaGestion[0]["fecha_gestion"];
					$UltimaGestionObservacion = $UltimaGestion[0]["observacion"];
					$UltimaGestionUsuario = $UltimaGestion[0]["nombre_ejecutivo"];
					$UltimaGestionN1 = isset($UltimaGestion[0]["n1"]) ? $UltimaGestion[0]["n1"] : "";
					$UltimaGestionN2 = isset($UltimaGestion[0]["n2"]) ? $UltimaGestion[0]["n2"] : "";
					$UltimaGestionN3 = isset($UltimaGestion[0]["n3"]) ? $UltimaGestion[0]["n3"] : "";
					$UltimaGestionFechaAgendamiento = isset($UltimaGestion[0]["fecha_agendamiento"]) ? $UltimaGestion[0]["fecha_agendamiento"] : "";
					$UltimaGestionFechaCompromiso = isset($UltimaGestion[0]["fecha_compromiso"]) ? $UltimaGestion[0]["fecha_compromiso"] : "";
				}
			}

			$SqlUltimoCompromiso = "select * from Ultimo_Compromiso where Rut='".$Rut."'";
			$UltimoCompromiso = $db->select($SqlUltimoCompromiso);
			$UltimoCompromisoTexto = "";
			$UltimoCompromisoFecha = "";
			$UltimoCompromisoObservacion = "";
			if(count($UltimoCompromiso) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimoCompromiso[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if(count($TipoContacto) > 0){
					$UltimoCompromisoTexto = $TipoContacto[0]["Nombre"];
					$UltimoCompromisoFecha = $UltimoCompromiso[0]["fec_compromiso"];
					$UltimoCompromisoObservacion = $UltimoCompromiso[0]["observacion"];	
				}
			}

			$FechaPeriodo = $this->getFechasPeriodosCargas();

			$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."' and fecha_gestion between '".$FechaPeriodo["Desde"]."' and '".$FechaPeriodo["Hasta"]."' and FIND_IN_SET(cedente,(select Lista_Vicidial from mandante_cedente where Id_Cedente='".$Cedente."'))";
			//$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."'";
			$CantidadGestiones = $db->select($SqlCantidadGestiones);
			if(count($CantidadGestiones) > 0){
				$CantidadGestiones = $CantidadGestiones[0]["Cantidad"];
			}
			
			$SqlDeudas = "select ".$ArrayDeudaSearchImplode." from Deuda where Id_Cedente = '".$Cedente."' and Rut = '".$Rut."' LIMIT 1";
			$Deudas = $db->select($SqlDeudas);
			foreach($Deudas as $Deuda){
				$Col = 0;
				foreach($ColumnasAsignacion as $ColumnaAsignacion){
					$Operacion = $ColumnaAsignacion["Operacion"];
					$Tabla = $ColumnaAsignacion["Tabla"];
					$Campo = $ColumnaAsignacion["Campo"];
					$Campo = $Operacion != "" ? $Operacion."(".$Campo.")" : $Campo;
					$TipoCampo = $ColumnaAsignacion["Tipo_Campo"];
					$Value = "";
					switch($TipoCampo){
						case '1':
							switch($Tabla){
								case "Deuda":
									$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' AND Id_Cedente = '".$Cedente."' LIMIT 1";
								break;
								default:
									$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' LIMIT 1";
								break;
							}
							$Vals = $db->select($Sql);
							foreach($Vals as $Val){
								$Value = $Val["Val"];
							}
						break;
						case '2':
							switch($Campo){
								/*
									INICIO VARIABLES FONOS
								*/
								case 'fono_especial':
									$Value = $FonoEspecial;
								break;
								case 'gestion_fono_especial':
									$Value = $GestionEspecial;
								break;
								case 'color_fono_especial':
									$Value = $ColorEspecial;
								break;
								case 'fono_2':
									$Value = $Fono2;
								break;
								case 'gestion_fono_2':
									$Value = $Gestion2;
								break;
								case 'color_fono_2':
									$Value = $ColorFono2;
								break;
								case 'fono_3':
									$Value = $Fono3;
								break;
								case 'gestion_fono_3':
									$Value = $Gestion3;
								break;
								case 'color_fono_3':
									$Value = $ColorFono3;
								break;
								/*
									FIN VARIABLES FONOS
								*/

								/*
									INICIO VARIABLES GESTION
								*/
								case 'mejor_gestion_texto':
									$Value = $MejorGestionTexto;
								break;
								case 'mejor_gestion_fecha':
									$Value = $MejorGestionFecha;
								break;
								case 'mejor_gestion_n1':
									$Value = $MejorGestionN1;
								break;
								case 'mejor_gestion_n2':
									$Value = $MejorGestionN2;
								break;
								case 'mejor_gestion_n3':
									$Value = $MejorGestionN3;
								break;
								case 'mejor_gestion_fecha_agendamiento':
									$Value = $MejorGestionFechaAgendamiento;
								break;
								case 'mejor_gestion_fecha_compromiso':
									$Value = $MejorGestionFechaCompromiso;
								break;
								case 'mejor_gestion_fono':
									$Value = $MejorGestionFono;
								break;
								case 'ultima_gestion_texto':
									$Value = $UltimaGestionTexto;
								break;
								case 'ultima_gestion_fecha':
									$Value = $UltimaGestionFecha;
								break;
								case 'ultima_gestion_observacion':
									$Value = $UltimaGestionObservacion;
								break;
								case 'ultima_gestion_usuario':
									$Value = $UltimaGestionUsuario;
								break;
								case 'ultima_gestion_n1':
									$Value = $UltimaGestionN1;
								break;
								case 'ultima_gestion_n2':
									$Value = $UltimaGestionN2;
								break;
								case 'ultima_gestion_n3':
									$Value = $UltimaGestionN3;
								break;
								case 'ultima_gestion_fecha_agendamiento':
									$Value = $UltimaGestionFechaAgendamiento;
								break;
								case 'ultima_gestion_fecha_compromiso':
									$Value = $UltimaGestionFechaCompromiso;
								break;
								case 'ultimo_compromiso_texto':
									$Value = $UltimoCompromisoTexto;
								break;
								case 'ultimo_compromiso_fecha':
									$Value = $UltimoCompromisoFecha;
								break;
								case 'ultimo_compromiso_observacion':
									$Value = $UltimoCompromisoObservacion;
								break;
								case 'cantidad_gestiones':
									$Value = $CantidadGestiones;
								break;
								/*
									FIN VARIABLES GESTION
								*/
							}
						break;
					}
					$Value = utf8_encode($Value);
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
					$Col++;
				}
				$Cont++;
				//$Rows .= "\r\n";
			}
		}

		if($Download){
			header('Content-Type: text/plain');
			header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
			header('Cache-Control: max-age=0');
			/* $response =  array(
				'fileName' => utf8_encode($fileName),
				'file' => "data:text/plain;base64,".base64_encode($Rows)
			); */
			$response = $Rows;
		}else{
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			if (!file_exists("../task/asignaciones/".$Cedente."/".$Cola)){
				mkdir("../task/asignaciones/".$Cedente."/".$Cola, 0777, true);
			}
			$objWriter->save('../task/asignaciones/'.$Cedente."/".$Cola.'/'.$fileName.".xlsx");
			$response = array("result" => true);
		}
		return $response;
	}
	function CrearArchivoAsignacionTipo2_backup($fileName,$Ruts,$Cedente = "",$Cola = "",$Download = true){
		$objPHPExcel = new PHPExcel();
		$db = new DB();
		if($Cedente == ""){
			$Cedente = $_SESSION['cedente'];
		}
		$fileName = $this->getEntidadName($fileName)."_Dial";
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
		$objPHPExcel->getActiveSheet()->setTitle('Personas');

		$Sql = "select * from Columnas_Asignacion_Dial where Id_Mandante in (select Id_Mandante from mandante_cedente where Id_Cedente='".$Cedente."') ORDER BY Prioridad";
		$ColumnasAsignacion = $db->select($Sql);
		$Col = 0;
		$ArrayStackedColumns = array();
		foreach($ColumnasAsignacion as $ColumnaAsignacion){
			$objPHPExcel->
			setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$ColumnaAsignacion["Nombre"]);
			if($ColumnaAsignacion["Tipo_Campo"] == "1"){
				array_push($ArrayStackedColumns,$ColumnaAsignacion["Campo"]);
			}
			$Col++;
		}

		$SqlColumnas = "select SIS_Columnas_Estrategias.columna from SIS_Tablas inner join SIS_Columnas_Estrategias on SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id where SIS_Tablas.nombre = 'Deuda' and FIND_IN_SET('".$Cedente."',SIS_Columnas_Estrategias.Id_Cedente) order by SIS_Columnas_Estrategias.columna";
		$Columnas = $db->select($SqlColumnas);

		$ArrayDeudaSearch = array();
		foreach($Columnas as $Columna){
			$key = array_search($Columna["columna"],$ArrayStackedColumns);
			if($key === FALSE){
				/*$objPHPExcel->
				setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$Columna["columna"]);
				$Col++;*/
				array_push($ArrayDeudaSearch,$Columna['columna']);
			}
		}

		$ArrayDeudaSearchImplode = implode(",",$ArrayDeudaSearch);
		$Cont = 2;
		foreach($Ruts as $Rut){
			
			$SqlFonos = "select fono_cob.*, SIS_Categoria_Fonos.tipo_var as Gestion from SIS_Categoria_Fonos inner join fono_cob on fono_cob.color = SIS_Categoria_Fonos.color where fono_cob.rut = '".$Rut."' and SIS_Categoria_Fonos.sel='0' order by SIS_Categoria_Fonos.prioridad LIMIT 3";
			$Fonos = $db->select($SqlFonos);
			$FonosTmp = array();
			foreach($Fonos as $Fono){
				array_push($FonosTmp,$Fono["formato_subtel"]."_".$Fono["Gestion"]);
			}
			$FonoEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],0,strpos($FonosTmp[0],"_")) : "";
			$GestionEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strpos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";
			$ColorEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strripos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";

			$Fono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],0,strpos($FonosTmp[1],"_")) : "";
			$Gestion2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strpos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";
			$ColorFono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strripos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";

			$Fono3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],0,strpos($FonosTmp[2],"_")) : "";
			$Gestion3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],strpos($FonosTmp[2],"_") + 1,strlen($FonosTmp[2])) : "";
			$ColorFono3 = isset($FonosTmp[3]) ? substr($FonosTmp[3],strripos($FonosTmp[3],"_") + 1,strlen($FonosTmp[3])) : "";
			
			$SqlMejorGestion = "select * from Mejor_Gestion_Cedente where Rut='".$Rut."' order by fechahora DESC LIMIT 1";
			$MejorGestion = $db->select($SqlMejorGestion);
			$MejorGestionTexto = "";
			$MejorGestionFecha = "";
			$MejorGestionN1 = "";
			$MejorGestionN2 = "";
			$MejorGestionN3 = "";
			$MejorGestionFono = "";
			$MejorGestionFechaAgendamiento = "";
			$MejorGestionFechaCompromiso = "";
			if($MejorGestion){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$MejorGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if($TipoContacto){
					$MejorGestionTexto = $TipoContacto[0]["Nombre"];
					$MejorGestionFecha = $MejorGestion[0]["fechahora"];
					$MejorGestionN1 = isset($MejorGestion[0]["n1"]) ? $MejorGestion[0]["n1"] : "";
					$MejorGestionN2 = isset($MejorGestion[0]["n2"]) ? $MejorGestion[0]["n2"] : "";
					$MejorGestionN3 = isset($MejorGestion[0]["n3"]) ? $MejorGestion[0]["n3"] : "";
					$MejorGestionFechaAgendamiento = isset($MejorGestion[0]["fecha_agendamiento"]) ? $MejorGestion[0]["fecha_agendamiento"] : "";
					$MejorGestionFechaCompromiso = isset($MejorGestion[0]["fecha_compromiso"]) ? $MejorGestion[0]["fecha_compromiso"] : "";
					$MejorGestionFono = $MejorGestion[0]["fono_discado"];
				}
			}

			$SqlUltimaGestion = "select * from Ultima_Gestion_Historica where Rut='".$Rut."'  order by fechahora DESC LIMIT 1";
			$UltimaGestion = $db->select($SqlUltimaGestion);
			$UltimaGestionTexto = "";
			$UltimaGestionFecha = "";
			$UltimaGestionObservacion = "";
			$UltimaGestionUsuario = "";
			$UltimaGestionN1 = "";
			$UltimaGestionN2 = "";
			$UltimaGestionN3 = "";
			$UltimaGestionFechaAgendamiento = "";
			$UltimaGestionFechaCompromiso = "";
			if($UltimaGestion){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimaGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				$UltimaGestionObservacion = "";
				if($TipoContacto){
					$UltimaGestionTexto = $TipoContacto[0]["Nombre"];
					$UltimaGestionFecha = $UltimaGestion[0]["fecha_gestion"];
					$UltimaGestionObservacion = $UltimaGestion[0]["observacion"];
					$UltimaGestionUsuario = $UltimaGestion[0]["nombre_ejecutivo"];
					$UltimaGestionN3 = isset($MejorGestion[0]["n1"]) ? $MejorGestion[0]["n1"] : "";
					$UltimaGestionN3 = isset($MejorGestion[0]["n2"]) ? $MejorGestion[0]["n2"] : "";
					$UltimaGestionN3 = isset($MejorGestion[0]["n3"]) ? $MejorGestion[0]["n3"] : "";
					$UltimaGestionFechaAgendamiento = isset($UltimaGestion[0]["fecha_agendamiento"]) ? $UltimaGestion[0]["fecha_agendamiento"] : "";
					$UltimaGestionFechaCompromiso = isset($UltimaGestion[0]["fecha_compromiso"]) ? $UltimaGestion[0]["fecha_compromiso"] : "";
				}
			}

			$SqlUltimoCompromiso = "select * from Ultimo_Compromiso where Rut='".$Rut."'";
			$UltimoCompromiso = $db->select($SqlUltimoCompromiso);
			$UltimoCompromisoTexto = "";
			$UltimoCompromisoFecha = "";
			$UltimoCompromisoObservacion = "";
			if($UltimoCompromiso){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimoCompromiso[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if($TipoContacto){
					$UltimoCompromisoTexto = $TipoContacto[0]["Nombre"];
					$UltimoCompromisoFecha = $UltimoCompromiso[0]["fec_compromiso"];
					$UltimoCompromisoObservacion = $UltimoCompromiso[0]["observacion"];	
				}
			}

			$FechaPeriodo = $this->getFechasPeriodosCargas();

			$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."' and fecha_gestion between '".$FechaPeriodo["Desde"]."' and '".$FechaPeriodo["Hasta"]."' and FIND_IN_SET(cedente,(select Lista_Vicidial from mandante_cedente where Id_Cedente='".$Cedente."'))";
			//$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."'";
			$CantidadGestiones = $db->select($SqlCantidadGestiones);
			if(count($CantidadGestiones) > 0){
				$CantidadGestiones = $CantidadGestiones[0]["Cantidad"];
			}
			
			$SqlDeudas = "select ".$ArrayDeudaSearchImplode." from Deuda where Id_Cedente = '".$Cedente."' and Rut = '".$Rut."' LIMIT 1";
			$Deudas = $db->select($SqlDeudas);
			foreach($Deudas as $Deuda){
				$Col = 0;
				foreach($ColumnasAsignacion as $ColumnaAsignacion){
					$Operacion = $ColumnaAsignacion["Operacion"];
					$Tabla = $ColumnaAsignacion["Tabla"];
					$Campo = $ColumnaAsignacion["Campo"];
					$Campo = $Operacion != "" ? $Operacion."(".$Campo.")" : $Campo;
					$TipoCampo = $ColumnaAsignacion["Tipo_Campo"];
					$Value = "";
					switch($TipoCampo){
						case '1':
							switch($Tabla){
								case "Deuda":
									$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' AND Id_Cedente = '".$Cedente."' LIMIT 1";
								break;
								default:
									$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' LIMIT 1";
								break;
							}
							$Vals = $db->select($Sql);
							foreach($Vals as $Val){
								$Value = $Val["Val"];
							}
						break;
						case '2':
							switch($Campo){
								/*
									INICIO VARIABLES FONOS
								*/
								case 'fono_especial':
									$Value = $FonoEspecial;
								break;
								case 'gestion_fono_especial':
									$Value = $GestionEspecial;
								break;
								case 'color_fono_especial':
									$Value = $ColorEspecial;
								break;
								case 'fono_2':
									$Value = $Fono2;
								break;
								case 'gestion_fono_2':
									$Value = $Gestion2;
								break;
								case 'color_fono_2':
									$Value = $ColorFono2;
								break;
								case 'fono_3':
									$Value = $Fono3;
								break;
								case 'gestion_fono_3':
									$Value = $Gestion3;
								break;
								case 'color_fono_3':
									$Value = $ColorFono3;
								break;
								/*
									FIN VARIABLES FONOS
								*/

								/*
									INICIO VARIABLES GESTION
								*/
								case 'mejor_gestion_texto':
									$Value = $MejorGestionTexto;
								break;
								case 'mejor_gestion_fecha':
									$Value = $MejorGestionFecha;
								break;
								case 'mejor_gestion_n1':
									$Value = $MejorGestionN1;
								break;
								case 'mejor_gestion_n2':
									$Value = $MejorGestionN2;
								break;
								case 'mejor_gestion_n3':
									$Value = $MejorGestionN3;
								break;
								case 'mejor_gestion_fecha_agendamiento':
									$Value = $MejorGestionFechaAgendamiento;
								break;
								case 'mejor_gestion_fecha_compromiso':
									$Value = $MejorGestionFechaCompromiso;
								break;
								case 'mejor_gestion_fono':
									$Value = $MejorGestionFono;
								break;
								case 'ultima_gestion_texto':
									$Value = $UltimaGestionTexto;
								break;
								case 'ultima_gestion_fecha':
									$Value = $UltimaGestionFecha;
								break;
								case 'ultima_gestion_observacion':
									$Value = $UltimaGestionObservacion;
								break;
								case 'ultima_gestion_usuario':
									$Value = $UltimaGestionUsuario;
								break;
								case 'ultima_gestion_n1':
									$Value = $UltimaGestionN1;
								break;
								case 'ultima_gestion_n2':
									$Value = $UltimaGestionN2;
								break;
								case 'ultima_gestion_n3':
									$Value = $UltimaGestionN3;
								break;
								case 'ultima_gestion_fecha_agendamiento':
									$Value = $UltimaGestionFechaAgendamiento;
								break;
								case 'ultima_gestion_fecha_compromiso':
									$Value = $UltimaGestionFechaCompromiso;
								break;
								case 'ultimo_compromiso_texto':
									$Value = $UltimoCompromisoTexto;
								break;
								case 'ultimo_compromiso_fecha':
									$Value = $UltimoCompromisoFecha;
								break;
								case 'ultimo_compromiso_observacion':
									$Value = $UltimoCompromisoObservacion;
								break;
								case 'cantidad_gestiones':
									$Value = $CantidadGestiones;
								break;
								/*
									FIN VARIABLES GESTION
								*/
							}
						break;
					}
					$Value = utf8_encode($Value);
					$objPHPExcel->
						setActiveSheetIndex($NextSheet)
							->setCellValueByColumnAndRow($Col,$Cont,$Value);
					$Col++;
				}
				$Cont++;
			}
		}
		$objPHPExcel->setActiveSheetIndex(0);

		if($Download){
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();
			$response =  array(
				'fileName' => utf8_encode($fileName),
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);
		}else{
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			if (!file_exists("../task/asignaciones/".$Cedente."/".$Cola)){
				mkdir("../task/asignaciones/".$Cedente."/".$Cola, 0777, true);
			}
			$objWriter->save('../task/asignaciones/'.$Cedente."/".$Cola.'/'.$fileName.".xlsx");
			$response = array("result" => true);
		}
		return $response;
	}
	function DownloadReporteDialAsignacion(){
		$db = new DB();
		$objPHPExcel = new PHPExcel();

		$Cedente = $_SESSION['cedente'];

		$fileName = "Reporte asignacion ".date("d_m_Y H_i_s");
		
		$Rows = "";
		
		$NextSheet = 0;
		
		$Sql = "select * from Columnas_Asignacion_Dial where Id_Mandante in (select Id_Mandante from mandante_cedente where Id_Cedente='".$Cedente."') ORDER BY Prioridad";
		$ColumnasAsignacion = $db->select($Sql);
		$Col = 0;
		$ArrayStackedColumns = array();
		
		$haveFonoFields = false;
		$contFonoFields = 0;

		$haveMejorGestionField = false;
		$contMejorGestionField = 0;

		$haveUltimaGestionField = false;
		$contUltimaGestionField = 0;

		$haveUltimoCompromisoField = false;
		$contUltimoCompromisoField = 0;

		$haveCantidadGestiones = false;

		foreach($ColumnasAsignacion as $ColumnaAsignacion){
			$Rows .= $ColumnaAsignacion["Nombre"].";";
			if($ColumnaAsignacion["Tipo_Campo"] == "1"){
				array_push($ArrayStackedColumns,$ColumnaAsignacion["Campo"]);
			}
			$Col++;
			switch($ColumnaAsignacion["Campo"]){
				case 'fono_especial':
					$contFonoFields++;
				break;
				case 'gestion_fono_especial':
					$contFonoFields++;
				break;
				case 'color_fono_especial':
					$contFonoFields++;
				break;
				case 'fono_2':
					$contFonoFields++;
				break;
				case 'gestion_fono_2':
					$contFonoFields++;
				break;
				case 'color_fono_2':
					$contFonoFields++;
				break;
				case 'fono_3':
					$contFonoFields++;
				break;
				case 'gestion_fono_3':
					$contFonoFields++;
				break;
				case 'color_fono_3':
					$contFonoFields++;
				break;

				case 'mejor_gestion_texto':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_fecha':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_n1':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_n2':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_n3':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_fecha_agendamiento':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_fecha_compromiso':
					$contMejorGestionField++;
				break;
				case 'mejor_gestion_fono':
					$contMejorGestionField++;
				break;

				case 'ultima_gestion_texto':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_fecha':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_observacion':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_usuario':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_n1':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_n2':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_n3':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_fecha_agendamiento':
					$contUltimaGestionField++;
				break;
				case 'ultima_gestion_fecha_compromiso':
					$contUltimaGestionField++;
				break;

				case 'ultimo_compromiso_texto':
					$contUltimoCompromisoField;
				break;
				case 'ultimo_compromiso_fecha':
					$contUltimoCompromisoField;
				break;
				case 'ultimo_compromiso_observacion':
					$contUltimoCompromisoField;
				break;

				case 'cantidad_gestiones':
					$haveCantidadGestiones = true;
				break;
			}
		}
		if($contFonoFields > 0){
			$haveFonoFields = true;
		}
		if($contMejorGestionField > 0){
			$haveMejorGestionField = true;
		}
		if($contUltimaGestionField > 0){
			$haveUltimaGestionField = true;
		}
		if($contUltimoCompromisoField > 0){
			$haveUltimoCompromisoField = true;
		}

		//$Rows .= "\r\n";

		$SqlColumnas = "select SIS_Columnas_Estrategias.columna from SIS_Tablas inner join SIS_Columnas_Estrategias on SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id where SIS_Tablas.nombre = 'Deuda' and FIND_IN_SET('".$Cedente."',SIS_Columnas_Estrategias.Id_Cedente) order by SIS_Columnas_Estrategias.columna";
		$Columnas = $db->select($SqlColumnas);

		$ArrayDeudaSearch = array();
		foreach($Columnas as $Columna){
			$key = array_search($Columna["columna"],$ArrayStackedColumns);
			if($key === FALSE){
				array_push($ArrayDeudaSearch,$Columna['columna']);
			}
		}
		$ArrayDeudaSearchImplode = implode(",",$ArrayDeudaSearch);

		$Cont = 2;

		$SqlDeudas = "select Rut from Deuda where Id_Cedente = '".$Cedente."' group by Rut";
		$Deudas = $db->select($SqlDeudas);
		foreach($Deudas as $Deuda){
			$Rows .= "\r\n";
			$Rut = $Deuda["Rut"];
			if($haveFonoFields){
				$SqlFonos = "select fono_cob.*, SIS_Categoria_Fonos.tipo_var as Gestion from SIS_Categoria_Fonos inner join fono_cob on fono_cob.color = SIS_Categoria_Fonos.color where fono_cob.rut = '".$Rut."' and SIS_Categoria_Fonos.sel='0' order by SIS_Categoria_Fonos.prioridad LIMIT 3";
				$Fonos = $db->select($SqlFonos);
				$FonosTmp = array();
				foreach($Fonos as $Fono){
					array_push($FonosTmp,$Fono["formato_subtel"]."_".$Fono["Gestion"]);
				}
				$FonoEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],0,strpos($FonosTmp[0],"_")) : "";
				$GestionEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strpos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";
				$ColorEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strripos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";

				$Fono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],0,strpos($FonosTmp[1],"_")) : "";
				$Gestion2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strpos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";
				$ColorFono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strripos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";

				$Fono3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],0,strpos($FonosTmp[2],"_")) : "";
				$Gestion3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],strpos($FonosTmp[2],"_") + 1,strlen($FonosTmp[2])) : "";
				$ColorFono3 = isset($FonosTmp[3]) ? substr($FonosTmp[3],strripos($FonosTmp[3],"_") + 1,strlen($FonosTmp[3])) : "";
			}else{
				$FonoEspecial = "";
				$GestionEspecial = "";
				$ColorEspecial = "";
				$Fono2 = "";
				$Gestion2 = "";
				$ColorFono2 = "";
				$Fono3 = "";
				$Gestion3 = "";
				$ColorFono3 = "";
			}

			if($haveMejorGestionField){
				$SqlMejorGestion = "select * from Mejor_Gestion_Cedente where Rut='".$Rut."' order by fechahora DESC LIMIT 1";
				$MejorGestion = $db->select($SqlMejorGestion);
				$MejorGestionTexto = "";
				$MejorGestionFecha = "";
				$MejorGestionN1 = "";
				$MejorGestionN2 = "";
				$MejorGestionN3 = "";
				$MejorGestionFechaAgendamiento = "";
				$MejorGestionFechaCompromiso = "";
				$MejorGestionFono = "";
				if(count($MejorGestion) > 0){
					$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$MejorGestion[0]["Id_TipoGestion"]."'";
					$TipoContacto = $db->select($SqlTipoContacto);
					if(count($TipoContacto) > 0){
						$MejorGestionTexto = $TipoContacto[0]["Nombre"];
						$MejorGestionFecha = $MejorGestion[0]["fechahora"];
						$MejorGestionN1 = isset($MejorGestion[0]["n1"]) ? $MejorGestion[0]["n1"] : "";
						$MejorGestionN2 = isset($MejorGestion[0]["n2"]) ? $MejorGestion[0]["n2"] : "";
						$MejorGestionN3 = isset($MejorGestion[0]["n3"]) ? $MejorGestion[0]["n3"] : "";
						$MejorGestionFechaAgendamiento = isset($MejorGestion[0]["fecha_agendamiento"]) ? $MejorGestion[0]["fecha_agendamiento"] : "";
						$MejorGestionFechaCompromiso = isset($MejorGestion[0]["fecha_compromiso"]) ? $MejorGestion[0]["fecha_compromiso"] : "";
						$MejorGestionFono = $MejorGestion[0]["fono_discado"];
					}
				}
			}else{
				$MejorGestionTexto = "";
				$MejorGestionFecha = "";
				$MejorGestionN1 = "";
				$MejorGestionN2 = "";
				$MejorGestionN3 = "";
				$MejorGestionFechaAgendamiento = "";
				$MejorGestionFechaCompromiso = "";
			}

			if($haveUltimaGestionField){
				$SqlUltimaGestion = "select * from Ultima_Gestion_Historica where Rut='".$Rut."'  order by fechahora DESC LIMIT 1";
				$UltimaGestion = $db->select($SqlUltimaGestion);
				$UltimaGestionTexto = "";
				$UltimaGestionFecha = "";
				$UltimaGestionObservacion = "";
				$UltimaGestionUsuario = "";
				$UltimaGestionN1 = "";
				$UltimaGestionN2 = "";
				$UltimaGestionN3 = "";
				$UltimaGestionFechaAgendamiento = "";
				$UltimaGestionFechaCompromiso = "";
				if(count($UltimaGestion) > 0){
					$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimaGestion[0]["Id_TipoGestion"]."'";
					$TipoContacto = $db->select($SqlTipoContacto);
					$UltimaGestionObservacion = "";
					if(count($TipoContacto) > 0){
						$UltimaGestionTexto = $TipoContacto[0]["Nombre"];
						$UltimaGestionFecha = $UltimaGestion[0]["fecha_gestion"];
						$UltimaGestionObservacion = $UltimaGestion[0]["observacion"];
						$UltimaGestionUsuario = $UltimaGestion[0]["nombre_ejecutivo"];
						$UltimaGestionN1 = isset($UltimaGestion[0]["n1"]) ? $UltimaGestion[0]["n1"] : "";
						$UltimaGestionN2 = isset($UltimaGestion[0]["n2"]) ? $UltimaGestion[0]["n2"] : "";
						$UltimaGestionN3 = isset($UltimaGestion[0]["n3"]) ? $UltimaGestion[0]["n3"] : "";
						$UltimaGestionFechaAgendamiento = isset($UltimaGestion[0]["fecha_agendamiento"]) ? $UltimaGestion[0]["fecha_agendamiento"] : "";
						$UltimaGestionFechaCompromiso = isset($UltimaGestion[0]["fecha_compromiso"]) ? $UltimaGestion[0]["fecha_compromiso"] : "";
					}
				}
			}else{
				$UltimaGestionTexto = "";
				$UltimaGestionFecha = "";
				$UltimaGestionObservacion = "";
				$UltimaGestionUsuario = "";
				$UltimaGestionN1 = "";
				$UltimaGestionN2 = "";
				$UltimaGestionN3 = "";
				$UltimaGestionFechaAgendamiento = "";
				$UltimaGestionFechaCompromiso = "";
			}

			if($haveUltimoCompromisoField){
				$SqlUltimoCompromiso = "select * from Ultimo_Compromiso where Rut='".$Rut."'";
				$UltimoCompromiso = $db->select($SqlUltimoCompromiso);
				$UltimoCompromisoTexto = "";
				$UltimoCompromisoFecha = "";
				$UltimoCompromisoObservacion = "";
				if(count($UltimoCompromiso) > 0){
					$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimoCompromiso[0]["Id_TipoGestion"]."'";
					$TipoContacto = $db->select($SqlTipoContacto);
					if(count($TipoContacto) > 0){
						$UltimoCompromisoTexto = $TipoContacto[0]["Nombre"];
						$UltimoCompromisoFecha = $UltimoCompromiso[0]["fec_compromiso"];
						$UltimoCompromisoObservacion = $UltimoCompromiso[0]["observacion"];	
					}
				}
			}else{
				$UltimoCompromisoTexto = "";
				$UltimoCompromisoFecha = "";
				$UltimoCompromisoObservacion = "";
			}

			if($haveCantidadGestiones){
				$FechaPeriodo = $this->getFechasPeriodosCargas();

				$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."' and fecha_gestion between '".$FechaPeriodo["Desde"]."' and '".$FechaPeriodo["Hasta"]."' and FIND_IN_SET(cedente,(select Lista_Vicidial from mandante_cedente where Id_Cedente='".$Cedente."'))";
				//$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."'";
				$CantidadGestiones = $db->select($SqlCantidadGestiones);
				if(count($CantidadGestiones) > 0){
					$CantidadGestiones = $CantidadGestiones[0]["Cantidad"];
				}
			}else{
				$CantidadGestiones = "";
			}

			$Col = 0;
			foreach($ColumnasAsignacion as $ColumnaAsignacion){
				$Operacion = $ColumnaAsignacion["Operacion"];
				$Tabla = $ColumnaAsignacion["Tabla"];
				$Campo = $ColumnaAsignacion["Campo"];
				$Campo = $Operacion != "" ? $Operacion."(".$Campo.")" : $Campo;
				$TipoCampo = $ColumnaAsignacion["Tipo_Campo"];
				$Value = "";
				switch($TipoCampo){
					case '1':
						switch($Tabla){
							case "Deuda":
								$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' AND Id_Cedente = '".$Cedente."' LIMIT 1";
							break;
							default:
								$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' LIMIT 1";
							break;
						}
						$Vals = $db->select($Sql);
						foreach($Vals as $Val){
							$Value = $Val["Val"];
						}
					break;
					case '2':
						switch($Campo){
							/*
								INICIO VARIABLES FONOS
							*/
							case 'fono_especial':
								$Value = $FonoEspecial;
							break;
							case 'gestion_fono_especial':
								$Value = $GestionEspecial;
							break;
							case 'color_fono_especial':
								$Value = $ColorEspecial;
							break;
							case 'fono_2':
								$Value = $Fono2;
							break;
							case 'gestion_fono_2':
								$Value = $Gestion2;
							break;
							case 'color_fono_2':
								$Value = $ColorFono2;
							break;
							case 'fono_3':
								$Value = $Fono3;
							break;
							case 'gestion_fono_3':
								$Value = $Gestion3;
							break;
							case 'color_fono_3':
								$Value = $ColorFono3;
							break;
							/*
								FIN VARIABLES FONOS
							*/

							/*
								INICIO VARIABLES GESTION
							*/
							case 'mejor_gestion_texto':
								$Value = $MejorGestionTexto;
							break;
							case 'mejor_gestion_fecha':
								$Value = $MejorGestionFecha;
							break;
							case 'mejor_gestion_n1':
								$Value = $MejorGestionN1;
							break;
							case 'mejor_gestion_n2':
								$Value = $MejorGestionN2;
							break;
							case 'mejor_gestion_n3':
								$Value = $MejorGestionN3;
							break;
							case 'mejor_gestion_fecha_agendamiento':
								$Value = $MejorGestionFechaAgendamiento;
							break;
							case 'mejor_gestion_fecha_compromiso':
								$Value = $MejorGestionFechaCompromiso;
							break;
							case 'mejor_gestion_fono':
								$Value = $MejorGestionFono;
							break;
							case 'ultima_gestion_texto':
								$Value = $UltimaGestionTexto;
							break;
							case 'ultima_gestion_fecha':
								$Value = $UltimaGestionFecha;
							break;
							case 'ultima_gestion_observacion':
								$Value = $UltimaGestionObservacion;
							break;
							case 'ultima_gestion_usuario':
								$Value = $UltimaGestionUsuario;
							break;
							case 'ultima_gestion_n1':
								$Value = $UltimaGestionN1;
							break;
							case 'ultima_gestion_n2':
								$Value = $UltimaGestionN2;
							break;
							case 'ultima_gestion_n3':
								$Value = $UltimaGestionN3;
							break;
							case 'ultima_gestion_fecha_agendamiento':
								$Value = $UltimaGestionFechaAgendamiento;
							break;
							case 'ultima_gestion_fecha_compromiso':
								$Value = $UltimaGestionFechaCompromiso;
							break;
							case 'ultimo_compromiso_texto':
								$Value = $UltimoCompromisoTexto;
							break;
							case 'ultimo_compromiso_fecha':
								$Value = $UltimoCompromisoFecha;
							break;
							case 'ultimo_compromiso_observacion':
								$Value = $UltimoCompromisoObservacion;
							break;
							case 'cantidad_gestiones':
								$Value = $CantidadGestiones;
							break;
							/*
								FIN VARIABLES GESTION
							*/
						}
					break;
				}
				//$Value = utf8_encode($Value);
				if(strrpos($Value,"ñ") !== false){
					$Value = str_replace("ñ","n",$Value);
				}
				if(strrpos($Value,"Ñ") !== false){
					$Value = str_replace("Ñ","N",$Value);
				}
				if(strrpos($Value,"ó") !== false){
					$Value = str_replace("ó","o",$Value);
				}
				if(strrpos($Value,"á") !== false){
					$Value = str_replace("á","a",$Value);
				}
				$Value = html_entity_decode(htmlentities($Value), ENT_QUOTES, "UTF-8");
				$Value = str_replace(";","",$Value);
				$Value = str_replace("\n","",$Value);
				$Value = str_replace("\r","",$Value);
				$Rows .= $Value.";";
				$Col++;
			}
			//$Rows .= "\r\n";
			$Cont++;
		}
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
		header('Cache-Control: max-age=0');
		
		return $Rows;
	}
	function getEntidadName($Entidad){
		$db = new DB();
		$ToReturn = $Entidad;
		$ArrayEntidad = explode("_",$Entidad);
		switch($ArrayEntidad[0]){
			case 'S':
			case 'E':
				$Sql = "select Nombre as Nombre from Personal where Id_Personal = '".$ArrayEntidad[1]."'";
			break;
			case 'EE':
				$Sql = "select Nombre as Nombre from empresa_externa where IdEmpresaExterna = '".$ArrayEntidad[1]."'";
			break;
			case 'G':
				$Sql = "select Nombre as Nombre from grupos where IdGrupo = '".$ArrayEntidad[1]."'";
			break;
		}
		$Entidad = $db->select($Sql);
		if($Entidad){
			$Nombre = $Entidad[0]["Nombre"];
		}else{
			$Nombre = '';
		}
		$ToReturn = $ArrayEntidad[0]."_".$Nombre;
		return $ToReturn;
	}
	function updateAsignaciones($Prefix = "QR_"){
		$db = new DB();
		//$Prefix = "QR_";
		$SqlColas = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 2 and TABLE_NAME like  '".$Prefix."%'";
		$Colas = $db->select($SqlColas);
		$ArrayColasAsignaciones = array();
		foreach($Colas as $Cola){
			$Prefix = $Cola["TABLE_NAME"];
			$SqlAsignaciones = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 7 and TABLE_NAME like '".$Prefix."%'";
			$Asignaciones = $db->select($SqlAsignaciones);
			$ColaArray = array();
			foreach($Asignaciones as $Asignacion){
				$SqlDeleteNotIn = "delete from ".$Asignacion["TABLE_NAME"]." where Rut not in (select Rut from ".$Prefix.")";
				$DeleteNotIn = $db->query($SqlDeleteNotIn);
				$ArrayTmp = array();
				$ArrayTabla = explode("_",$Asignacion["TABLE_NAME"]);
				$Prefijo = $ArrayTabla[0];
				$Cedente = $ArrayTabla[1];
				$Cola = $ArrayTabla[2];
				$TipoEntidad = $ArrayTabla[3];
				$idEntidad = $ArrayTabla[4];
				$Porcentaje = $ArrayTabla[5];
				$TipoAlgoritmo = $ArrayTabla[6];
				$Foco = $ArrayTabla[7];

				$ArrayTmp["Tabla"] = $Asignacion["TABLE_NAME"];
				$ArrayTmp["TipoAlgoritmo"] = $TipoAlgoritmo;
				$ArrayTmp["Foco"] = $Foco;

				array_push($ColaArray,$ArrayTmp);
			}
			$ArrayColasAsignaciones[$Prefix] = $ColaArray;
		}
		foreach($ArrayColasAsignaciones as $key => $ColaArray){
			$Cola = $key;
			$CantEntidades = count($ColaArray);
			if($CantEntidades > 0){
				$TipoAlgoritmo = "";
				$Entidades = array();
				$Cont = 0;
				$ColaRuts = array();
				$SqlCola = "select Rut from ".$key." order by Rut";
				$Ruts = $db->select($SqlCola);
				foreach($Ruts as $Rut){
					array_push($ColaRuts,array("Rut"=>$Rut["Rut"]));
				}
				/*echo "<pre>";print_r($ColaRuts);echo"</pre>";
				echo "<br><br><br>";*/
				foreach($ColaArray as $Asignacion){
					$SqlRuts = "select Rut from ".$Asignacion["Tabla"]." order by Rut";
					$Ruts = $db->select($SqlRuts);
					$Entidades[$Cont]["Ruts"] = array();
					//echo "<pre>";print_r($Ruts);echo"</pre>";
					foreach($Ruts as $Rut){
						$ArrayKey = array_search(trim($Rut["Rut"]),array_column($ColaRuts,'Rut'));
						if(($ArrayKey !== FALSE)){
							array_push($Entidades[$Cont]["Ruts"],$Rut["Rut"]);
							unset($ColaRuts[$ArrayKey]);
							$ColaRuts = array_values($ColaRuts);
							//echo "<pre>";print_r($ColaRuts);echo"</pre>";
						}
					}
					
					$ArrayTabla = explode("_",$Asignacion["Tabla"]);
					$Prefijo = $ArrayTabla[0];
					$Cedente = $ArrayTabla[1];
					$Cola = $ArrayTabla[2];
					$TipoEntidad = $ArrayTabla[3];
					$idEntidad = $ArrayTabla[4];
					$Porcentaje = $ArrayTabla[5];
					$TipoAlgoritmo = $ArrayTabla[6];
					$Foco = $ArrayTabla[7];
					$Entidades[$Cont][0] = "";
					$Entidades[$Cont][1] = $Porcentaje;
					$Entidades[$Cont][2] = $TipoEntidad."_".$idEntidad;
					$Entidades[$Cont][3] = $Foco;
					$Entidades[$Cont][4] = count($Entidades[$Cont]["Ruts"]);
					$Entidades[$Cont][5] = $this->getDeudaFromRuts($Entidades[$Cont]["Ruts"]);
					$Cont++;
				}
				switch($TipoAlgoritmo){
					case '0':
						$this->AutoSeparateByRuts($ColaRuts,$Entidades,$key);
					break;
					case '1':
						$this->AutoSeparateByDeuda($ColaRuts,$Entidades,$key);
					break;
				}
			}
		}
		unlinkRecursive("../task/asignaciones/",false);
		/*echo "<pre>";
		print_r($ArrayColasAsignaciones);
		echo "</pre>";*/
	}
	function AutoSeparateByRuts($RutsCola, $Rows, $TableCola){
		$Algoritmo = "0";
		$db = new DB();
		$Ruts = $RutsCola;
		
		$SqlCantRutsCola = "select count(*) as CantRutsCola from ".$TableCola;
		$CantRutsCola = $db->select($SqlCantRutsCola);
		$CantRutsCola = $CantRutsCola[0]["CantRutsCola"];

		$NumRuts = $CantRutsCola;//count($Ruts);
		$CantRutsAvailable = $NumRuts;
		$ArrayAsignacion = array();
		$ArrayCola = explode("_",$TableCola);
		$Cedente = $ArrayCola[1];
		$Cola = $ArrayCola[2];
		$Rows = array_sort($Rows, 4, SORT_ASC);
		/*echo "<pre>";print_r($RutsCola);echo "</pre>";
		exit;*/
		foreach($Rows as $Row){
			$Nombre = $Row[0];
			$Porcentaje = $Row[1];
			$Porcentaje = $Porcentaje / 100;
			$Foco = $Row[3];
			$Id = $Row[2];
			$TableName = "".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."";
			$TotalRuts = ceil($NumRuts * $Porcentaje);
			if($CantRutsAvailable <= $TotalRuts){
				$TotalRuts = $CantRutsAvailable;
			}
			$CantRutsAvailable = $CantRutsAvailable - $TotalRuts;
			$ArrayAsignacion[$Id]["Porcentaje"] = $Porcentaje * 100;
			$ArrayAsignacion[$Id]["TotalRuts"] = $TotalRuts;
			$ArrayAsignacion[$Id]["Ruts"] = array();

			$SqlCantRutsAsignacion = "select count(*) as CantRutsAsignacion from ".$TableName;
			$CantRutsAsignacion = $db->select($SqlCantRutsAsignacion);
			$CantRutsAsignacion = $CantRutsAsignacion[0]["CantRutsAsignacion"];
			
			$Cont = $CantRutsAsignacion;
			foreach($Ruts as $Key => $Rut){
				if($Cont <= $TotalRuts){
					array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
					$Cont++;
					unset($Ruts[$Key]);
				}else{
					break;
				}
			}
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			//echo "<pre>";print_r($RutsArray);echo "</pre>";
			$Cont = 1;
			$Cont1000 = 0;
			$ArrayValues = array();
			$ArrayValues[$Cont1000] = array();
			$CantRutsAsignacion++;
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
				array_push($ArrayValues[$Cont1000],"(".$Rut.",'".$CantRutsAsignacion."')");
				$CantRutsAsignacion++;
				$Cont++;
				if($Cont == 1000){
					$Cont = 1;
					$Cont1000++;
					$ArrayValues[$Cont1000] = array();
				}
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			$RutNotIn = implode(",",$Row["Ruts"]);
			$SearchRutsFromTable = "select Rut from ".$TableName;
			$RutsFromTable = $db->select($SearchRutsFromTable);
			foreach($RutsFromTable as $Rut){
				array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
			}
			if(count($ArrayValues) > 0){
				/* $SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert); */
				foreach($ArrayValues as $Values){
					$Implode = implode(",",$Values);
					$SqlInsert = "INSERT INTO $TableName (Rut,orden) values ".$Implode;
					$Insert = $db->query($SqlInsert);
				}
				//if($Insert){
					/*$DropColumn = $db->query("ALTER TABLE ".$TableName." DROP COLUMN id;");
					if($DropColumn){
						$AddColumn = $db->query("ALTER TABLE ".$TableName." ADD id int not null AUTO_INCREMENT PRIMARY KEY");
					}*/
				//}
			}
			//echo "<pre>";print_r($ArrayAsignacion);echo "</pre>";
		}

		/*foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
			$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
		}*/
	}
	function AutoSeparateByDeuda($RutsCola, $Rows, $TableCola){
		$Algoritmo = "1";
		$db = new DB();
		$ArrayCola = explode("_",$TableCola);
		$Rows = array_sort($Rows, 5, SORT_ASC);
		$Cedente = $ArrayCola[1];
		$Cola = $ArrayCola[2];
		$RutsImplode = array();
		foreach($RutsCola as $Rut){
			array_push($RutsImplode,"'".$Rut["Rut"]."'");
		}
		if(count($RutsImplode) > 0){
			$RutsImplode = implode(",",$RutsImplode);
			$SqlCola = "select Rut as Rut, Sum(Deuda) as Deuda from Deuda where Id_Cedente = '".$Cedente."' and Rut in (".$RutsImplode.") Group By Rut Order by Deuda DESC";
			$Deudas = $db->select($SqlCola);			
			if(count($Deudas) > 0){
				
				$SqlSumDeudaCola = "select SUM(Deuda) as SumDeudaCola from Deuda inner join ".$TableCola." QR on QR.Rut = Deuda.Rut";
				$SumDeudaCola = $db->select($SqlSumDeudaCola);
				$SumDeudaCola = $SumDeudaCola[0]["SumDeudaCola"];

				//$CantTotalDeudas = $Deudas[0]["Deuda"] > 0 ? $this->DeudaTotal($Deudas) : 0;
				$CantTotalDeudas = $SumDeudaCola > 0 ? $SumDeudaCola : 0;
				$CantDeudasAvailable = $CantTotalDeudas;
				$ArrayAsignacion = array();
				if($Deudas[0]["Deuda"] > 0){
					foreach($Rows as $Row){
						$Nombre = $Row[0];
						$Porcentaje = $Row[1];
						$Porcentaje = $Porcentaje / 100;
						$Foco = $Row[3];
						$Id = $Row[2];
						$TotalDeudas = ($CantTotalDeudas * $Porcentaje);
						if($CantDeudasAvailable <= $TotalDeudas){
							$TotalDeudas = $CantDeudasAvailable;
						}
						$ArrayAsignacion[$Id]["Ruts"] = array();
						
						$RutsImplodeTmp = implode(",",$Row["Ruts"]);

						$SqlSumDeudaCola = "select SUM(Deuda) as SumDeudaCola from Deuda inner join ".$TableCola." QR on QR.Rut = Deuda.Rut where QR.Rut in(".$RutsImplodeTmp.")";
						$SumDeudaCola = $db->select($SqlSumDeudaCola);
						$SumDeudaCola = $SumDeudaCola[0]["SumDeudaCola"];

						//$SumDeuda = 0;
						$SumDeuda = $SumDeudaCola > 0 ? $SumDeudaCola : 0;
						//echo $SumDeuda." - ".$CantTotalDeudas."<br>";
						foreach($Deudas as $Key => $Deuda){	
							if($SumDeuda <= $TotalDeudas){
								$SumDeuda += $Deuda["Deuda"];
								array_push($ArrayAsignacion[$Id]["Ruts"],$Deuda["Rut"]);
								unset($Deudas[$Key]);
							}else{
								//echo $SumDeuda."<br>";
								break;
							}
						}

						$TableName = "".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."";

						echo $SqlCantRutsAsignacion = "select count(*) as CantRutsAsignacion from ".$TableName;
						$CantRutsAsignacion = $db->select($SqlCantRutsAsignacion);
						$CantRutsAsignacion = $CantRutsAsignacion[0]["CantRutsAsignacion"];

						$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
						$Cont = 1;
						$ArrayValues = array();
						$Cont1000 = 0;
						$ArrayValues = array();
						$ArrayValues[$Cont1000] = array();
						$CantRutsAsignacion++;
						foreach($RutsArray as $Key => $Rut){
							$RutsArray[$Key] = "(".$Rut.")";
							array_push($ArrayValues[$Cont1000],"(".$Rut.",'".$CantRutsAsignacion."')");
							$CantRutsAsignacion++;
							$Cont++;
							if($Cont == 1000){
								$Cont = 1;
								$Cont1000++;
								$ArrayValues[$Cont1000] = array();
							}
						}
						$RutsImplode = implode(",",$RutsArray);
						//echo $RutsImplode."<br>";

						$SearchRutsFromTable = "select Rut from ".$TableName;
						$RutsFromTable = $db->select($SearchRutsFromTable);
						foreach($RutsFromTable as $Rut){
							array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
						}
						if(count($ArrayValues) > 0){
							/* $SqlInsert = "INSERT  INTO $TableName (Rut) values ".$RutsImplode;
							$Insert = $db->query($SqlInsert); */
							foreach($ArrayValues as $Values){
								$Implode = implode(",",$Values);
								$SqlInsert = "INSERT INTO $TableName (Rut,orden) values ".$Implode;
								$Insert = $db->query($SqlInsert);
							}
							//if($Insert){
								/*$DropColumn = $db->query("ALTER TABLE ".$TableName." DROP COLUMN id;");
								if($DropColumn){
									$AddColumn = $db->query("ALTER TABLE ".$TableName." ADD id int not null AUTO_INCREMENT PRIMARY KEY");
								}*/
							//}
						}
					}
					/*foreach($ArrayAsignacion as $key => $Entidad){
						$Ruts = $Entidad["Ruts"];
						$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
						$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
					}*/
				}
			}else{ //PREGUNTARRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
				foreach($Rows as $Row){
					$Porcentaje = $Row[1];
					$Porcentaje = $Porcentaje / 100;
					$Foco = $Row[3];
					$Id = $Row[2];
					$TableName = "".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."";
					
					$ArrayAsignacion[$Id]["Ruts"] = array();
					$SearchRutsFromTable = "select Rut from ".$TableName;
					$RutsFromTable = $db->select($SearchRutsFromTable);
					foreach($RutsFromTable as $Rut){
						array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
					}
					/*foreach($ArrayAsignacion as $key => $Entidad){
						$Ruts = $Entidad["Ruts"];
						$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
						$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
					}*/
				}
			}
		}else{
			foreach($Rows as $Row){
				$Porcentaje = $Row[1];
				$Porcentaje = $Porcentaje / 100;
				$Foco = $Row[3];
				$Id = $Row[2];
				$TableName = "".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."";
				
				$ArrayAsignacion[$Id]["Ruts"] = array();
				$SearchRutsFromTable = "select Rut from ".$TableName;
				$RutsFromTable = $db->select($SearchRutsFromTable);
				foreach($RutsFromTable as $Rut){
					array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
				}
				/*foreach($ArrayAsignacion as $key => $Entidad){
					$Ruts = $Entidad["Ruts"];
					$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
					$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
				}*/
			}
		}
	}
	function getColumnasAsignacion(){
		$db = new DB();
		$ToReturn = array();
		if(isset($_SESSION['mandante'])){
			$SqlColumnas = "select * from Columnas_Asignacion_Dial where Id_Mandante='".$_SESSION['mandante']."' order by Prioridad";
			$Columnas = $db->select($SqlColumnas);
			$Cont = 1;
			foreach($Columnas as $Columna){
				$ArrayTmp = array();
				$ArrayTmp["Prioridad"] = $Columna["Prioridad"];
				$ArrayTmp["Titulo"] = $Columna["Nombre"];
				$ArrayTmp["TipoCampo"] = $Columna["Tipo_Campo"] == "1" ? "Tabla" : "Especial";
				$ArrayTmp["Tabla"] = $Columna["Tabla"];
				$ArrayTmp["Campo"] = $Columna["Campo"];
				$ArrayTmp["Operacion"] = $Columna["Operacion"];
				$ArrayTmp["Accion"] = $Columna["id"];
				array_push($ToReturn,$ArrayTmp);
				$Cont++;
			}
		}
		return $ToReturn;
	}
	function addColumnaAsignacion($Nombre,$TipoCampo,$Tabla,$Campo,$Operacion){
		$ToReturn = array();
		$db = new DB();
		$SqlQuery = "insert into Columnas_Asignacion_Dial (Nombre,Tabla,Campo,Operacion,Tipo_Campo,Id_Mandante) values ('".$Nombre."','".$Tabla."','".$Campo."','".$Operacion."','".$TipoCampo."','".$_SESSION['mandante']."')";
		$Query = $db->query($SqlQuery);
		if($Query){
			$ToReturn["result"] = true;
		}else{
			$ToReturn['result'] = false;
		}
		return $ToReturn;
	}
	function updatePrioridad($Value,$ID){
		$ToReturn = array();
		$db = new DB();
		$SqlUpdate = "update Columnas_Asignacion_Dial set Prioridad='".$Value."' where id='".$ID."'";
		$Update = $db->query($SqlUpdate);
		if($Update){
			$ToReturn['result'] = true;
		}else{
			$ToReturn['result'] = false;
		}
		return $ToReturn;
	}
	function deleteColumna($ID){
		$ToReturn = array();
		$db = new DB();
		$SqlDelete = "delete from Columnas_Asignacion_Dial where id='".$ID."'";
		$Delete = $db->query($SqlDelete);
		if($Delete){
			$ToReturn['result'] = true;
		}else{
			$ToReturn['result'] = false;
		}
		$ToReturn['query'] = $SqlDelete;
		return $ToReturn;
	}
	function getColumnaData($ID){
		$ToReturn = array();
		$db = new DB();
		$configTablasClass = new configTablas();
		$SqlRows = "select * from Columnas_Asignacion_Dial where id='".$ID."'";
		$Rows = $db->select($SqlRows);
		foreach($Rows as $Row){
			$ToReturn["data"]["id"] = $Row['id'];
			$ToReturn["data"]["Nombre"] = $Row['Nombre'];
			$ToReturn["data"]["TipoCampo"] = $Row['Tipo_Campo'];
			switch($ToReturn["data"]["TipoCampo"]){
				case '1':
					$ToReturn["data"]["Tabla"] = $configTablasClass->getIdTablaByNombre($Row['Tabla']);
					$ToReturn["data"]["Campo"] = $configTablasClass->getIdCampoByNombreAndTabla($ToReturn["data"]["Tabla"],$Row['Campo']);
				break;
				default:
					$ToReturn["data"]["Tabla"] = $Row['Tabla'];
					$ToReturn["data"]["Campo"] = $Row['Campo'];
				break;
			}
			$ToReturn["data"]["Operacion"] = $Row['Operacion'];
		}
		return $ToReturn;
	}
	function updateColumnaAsignacion($ID,$Nombre,$TipoCampo,$Tabla,$Campo,$Operacion){
		$ToReturn = array();
		$db = new DB();
		$SqlQuery = "update Columnas_Asignacion_Dial set Nombre='".$Nombre."' ,Tabla='".$Tabla."',Campo='".$Campo."',Operacion='".$Operacion."',Tipo_Campo='".$TipoCampo."' where id='".$ID."' and Id_Mandante='".$_SESSION['mandante']."'";
		$Query = $db->query($SqlQuery);
		if($Query){
			$ToReturn["result"] = true;
		}else{
			$ToReturn['result'] = false;
		}
		$ToReturn['query'] = $SqlQuery;
		return $ToReturn;
	}
	function ExisteCola($idCola){
		$ToReturn = false;
		$db = new DB();
		$Cola = "QR_".$_SESSION["cedente"]."_".$idCola;
		$SqlCola = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 2 and  TABLE_NAME like '".$Cola."%'";
		$Cola = $db->select($SqlCola);
		if(count($Cola) > 0){
			$ToReturn = true;
		}
		return $ToReturn;
	}
	function ExisteAsignacion($idCola){
		$ToReturn = false;
		$db = new DB();
		$Cola = "QR_".$_SESSION["cedente"]."_".$idCola;
		$SqlCola = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) > 2 and  TABLE_NAME like '".$Cola."%'";
		$Cola = $db->select($SqlCola);
		if(count($Cola) > 0){
			$ToReturn = true;
		}
		return $ToReturn;
	}
	function descargarGestiones($Cedente,$startDate,$endDate){
		$db = new DB();
		$objPHPExcel = new PHPExcel();
		$fileName = "Reporte Gestion ".date("d_m_Y H_i_s");
		
		$Rows = "";

		$NextSheet = 0;

		
		
		$Columnas = array();
		$Columnas[0][0] = "Rut";
		$Columnas[0][1] = "Rut";
		$Columnas[1][0] = "Nombre";
		$Columnas[1][1] = "Nombre_Completo";
		$Columnas[2][0] = "Fecha Gestion";
		$Columnas[2][1] = "fechahora";
		$Columnas[3][0] = "Fono Discado";
		$Columnas[3][1] = "fono_discado";
		$Columnas[4][0] = "Observacion";
		$Columnas[4][1] = "observacion";
		$Columnas[5][0] = "Ejecutivo";
		$Columnas[5][1] = "nombre_ejecutivo";
		$Columnas[6][0] = "Gestion";
		$Columnas[6][1] = "Gestion";
		$Columnas[7][0] = "Fecha de Compromiso";
		$Columnas[7][1] = "fec_compromiso";
		$Columnas[8][0] = "Monto Compromiso";
		$Columnas[8][1] = "monto_comp";
		$Columnas[9][0] = "Origen";
		$Columnas[9][1] = "origen";
		$Columnas[10][0] = "Nivel 1";
		$Columnas[10][1] = "n1";
		$Columnas[11][0] = "Nivel 2";
		$Columnas[11][1] = "n2";
		$Columnas[12][0] = "Nivel 3";
		$Columnas[12][1] = "n3";
		$Columnas[13][0] = "Status Name";
		$Columnas[13][1] = "status_name";
                $Columnas[14][0] = "Factura";
                $Columnas[14][1] = "factura";
		$Columnas[15][0] = "Grabación";
		$Columnas[15][1] = "urlGrabacion";
		//File
		$Columnas[16][0] = "Ruta Archivo";
		$Columnas[16][1] = "file_url";
		//Monto
		$Columnas[17][0] = "Monto Refacturado";
		$Columnas[17][1] = "refacturacion";
		//Saldo_Agregado
		/*$Columnas[17][0] = "Saldo Agregado";
		$Columnas[17][1] = "saldo_agregado";*/

		$Col = 0;
		foreach($Columnas as $Columna){
			$Titulo = $Columna[0];
			$Rows .= $Titulo.";";
			$Col++;
		}

		//$Rows .= "\r\n";
        /**
         * -- inner join Persona on Persona.Rut = gestion_ult_trimestre.rut_cliente AND Persona.Id_Cedente = 7 
         */
		$Cont = 2;
		$SqlGestiones = "SELECT
							gestion_refacturada.monto AS refacturacion,
							gestion_ult_trimestre.rut_cliente AS Rut,
							(SELECT Nombre_Completo FROM Persona WHERE rut = gestion_ult_trimestre.rut_cliente=Rut AND Id_Cedente='".$Cedente."' LIMIT 1) AS Nombre_Completo,
							Tipo_Contacto.Nombre AS Gestion,
							gestion_ult_trimestre.*,
							(SELECT CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN REPLACE(gestion_ult_trimestre.url_grabacion,IpServidorDiscado,IpServidorDiscadoAux) ELSE gestion_ult_trimestre.url_grabacion END FROM (SELECT IpServidorDiscado,IpServidorDiscadoAux FROM fireConfig) tb1) AS urlGrabacion
						from
							gestion_ult_trimestre
								INNER JOIN Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
								LEFT JOIN gestion_refacturada on gestion_ult_trimestre.id_gestion = gestion_refacturada.id_gestion
						where
							FIND_IN_SET(cedente,(SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Cedente='".$Cedente."')) AND
							fecha_gestion BETWEEN '".$startDate."' AND '".$endDate."'
						order by
							fechahora ASC";
		$Gestiones = $db->select($SqlGestiones);
		foreach($Gestiones as $Gestion){
			$Rows .= "\r\n";
			$Col = 0;
			foreach($Columnas as $Columna){
				
				$Campo = $Columna[1];
				$Value = utf8_encode($Gestion[$Campo]);
				$Value = str_replace(";","",$Value);//$Value = preg_replace('([^A-Za-z0-9- :._¿?/])', '', $Value);
				$Value = str_replace("\n","",$Value);
				$Value = str_replace("\r","",$Value);
				$Value = str_replace(",","",$Value);
				$Rows .= $Value.";";
				$Col++;

			}
			//$Rows .= "\r\n";
			$Cont++;
		}
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
		header('Cache-Control: max-age=0');
		
		return $Rows;
	}
	function getColas(){
		$db = new DB();

		$Prefix = "QR_".$_SESSION["cedente"]."_";
		$SqlColas = "SELECT TABLE_NAME AS tabla FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'foco' AND LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 7 AND TABLE_NAME LIKE '".$Prefix."%' AND TABLE_NAME LIKE '%_G_%' AND TABLE_NAME LIKE '%1' ORDER BY TABLE_NAME";
		$Colas = $db->select($SqlColas);
		return $Colas;
	}
	function crearQueryDiscador($Cola,$TipoTelefono,$Canales,$TlfxRut,$Salida,$TipoCategorias){
		$ToReturn = array();
		$ToReturn["result"] = false;

		$db = new DB();
		$dbDiscador = new DB("discador");

		$TipoTelefono = implode(",",$TipoTelefono);
		$WhereTipoCategorias = "";
		$WhereSubTipoCategorias = "";
		switch($TipoCategorias){
			case "Colores":
				$WhereTipoCategorias = "SIS_Categoria_Fonos.color in (".$TipoTelefono.") and";
				$WhereSubTipoCategorias = "cf.color in (".$TipoTelefono.") and";
			break;
			case "Prioridad_Fonos":
				$WhereTipoCategorias = "fono_cob.Prioridad_Fono	in (".$TipoTelefono.") and";
				$WhereSubTipoCategorias = "f.Prioridad_Fono	in (".$TipoTelefono.") and";
			break;
		}

		$focoConfig = getFocoConfig();

		$SqlRuts = "select
						QR.Rut as Rut,
						(SELECT substring_index(GROUP_CONCAT(f.formato_subtel),',',".$TlfxRut.") FROM fono_cob f inner join SIS_Categoria_Fonos cf on cf.color = f.color WHERE ".$WhereSubTipoCategorias." f.Rut = QR.Rut AND LENGTH(f.formato_subtel)=9 AND f.vigente = '1' AND f.formato_subtel NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 2 AND Id_Cedente = '".$_SESSION['cedente']."' AND Fecha_Term >= CURDATE()) order by cf.prioridad) AS Fono
					from
						".$Cola." QR 
					WHERE
						Rut NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 1 AND Id_Cedente = '".$_SESSION["cedente"]."' AND Fecha_Term >= CURDATE())
					group by
						QR.rut";
		/* $SqlRuts = "select
						QR.Rut as Rut,
						(SELECT substring_index(GROUP_CONCAT(f.formato_subtel),',',".$TlfxRut.") FROM fono_cob f inner join SIS_Categoria_Fonos cf on cf.color = f.color WHERE ".$WhereSubTipoCategorias." f.Rut = QR.Rut AND LENGTH(f.formato_subtel)=9 AND fono_cob.vigente = '1' order by cf.prioridad) AS Fono
					from
						".$Cola." QR
							inner join fono_cob on fono_cob.Rut = QR.Rut
							inner join SIS_Categoria_Fonos on SIS_Categoria_Fonos.color = fono_cob.color
					where
						".$WhereTipoCategorias."
						fono_cob.vigente = '1' 
					group by
						QR.rut"; */
		$Ruts = $db->select($SqlRuts);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Ruts as $RutArray){
			$Rut = $RutArray["Rut"];
			$Fonos = $RutArray["Fono"];
			$FonosArray = explode(",",$Fonos);
			foreach($FonosArray as $Fono){
				if($Fono != ""){
					$Value = "('".$Fono."','".$Rut."','".$_SESSION["cedente"]."','".$focoConfig["CodigoFoco"]."')";
					array_push($Array1000[$Cont1000],$Value);
					if(count($Array1000[$Cont1000]) == 1000){
						$Cont1000++;
						$Array1000[$Cont1000] = array();
					}
				}
			}
		}
		$ToReturn = $this->crearColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida,$TipoCategorias);
		if($ToReturn["result"]){
			$SqlCreate = "CREATE TABLE IF NOT EXISTS DR_".$ToReturn["Queue"]."_".$Cola." ( 
							id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
							Fono int,
							Rut int,
							Cedente  varchar(100),
							CodigoFoco varchar(100),
							llamado int DEFAULT 0,
							UNIQUE (Fono,Rut)
						) ENGINE = MyISAM";

			$Create = $dbDiscador->query($SqlCreate);
			$SqlTruncate = "TRUNCATE TABLE DR_".$ToReturn["Queue"]."_".$Cola."";
			$Truncate = $dbDiscador->query($SqlTruncate);
			if($Truncate){
				foreach($Array1000 as $ValuesArray){
					if(count($ValuesArray) > 0){
						$Values = implode(",",$ValuesArray);
						$SqlInsert = "INSERT IGNORE INTO DR_".$ToReturn["Queue"]."_".$Cola." (Fono,Rut,Cedente,CodigoFoco) values ".$Values;
						$Insert = $dbDiscador->query($SqlInsert);
					}
				}
				/* $SqlInsert = "INSERT INTO DR_".$ToReturn["Queue"]."_".$Cola." (Fono,Rut,Cedente,CodigoFoco) ".$SqlRuts;
				$Insert = $dbDiscador->query($SqlInsert); */
				//if($Insert){
					$this->CopyPersonaDataToPredictivo($Cola,$ToReturn["Queue"]);
					$this->CopyDeudaDataToPredictivo($Cola,$ToReturn["Queue"]);
					//$this->CopyFonoDataToPredictivo($Cola,$ToReturn["Queue"]);
					$this->CopyMailDataToPredictivo($Cola,$ToReturn["Queue"]);
					$this->CopyDireccionesDataToPredictivo($Cola,$ToReturn["Queue"]);
					$this->CopyMejorGestionDataToPredictivo($Cola,$ToReturn["Queue"]);
					$this->CopyUltimaGestionDataToPredictivo($Cola,$ToReturn["Queue"]);
					$this->CopyColumnasCRMDataToPredictivo($ToReturn["Queue"]);
				//}
			}
		}
		return $ToReturn;
	}
/*function ExistColaDiscador($Cola){
	$ToReturn = false;
	$dbDiscador = new DB("discador");
	$SqlExist = "select * from Asterisk_Discador_Cola where Cola  = '".$Cola."' and Id_Cedente='".$_SESSION["cedente"]."'";
	$Exist = $dbDiscador->select($SqlExist);
	if(count($Exist) > 0){
		$ToReturn = true;
	}
	return $ToReturn;
}*/
	function crearColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida,$TipoCategorias){
		$ToReturn = array();
		$ToReturn = $this->insertColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida,$TipoCategorias);
		return $ToReturn;
	}
	function insertColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida,$TipoCategorias){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$ToReturn["Queue"] = "";
		$db = new DB();
		$SqlInsert = "INSERT INTO Asterisk_Discador_Cola (Cola,numero_canales,telfxrut,tipo_telefono,Id_Cedente,Salida,TipoCategorias) values ('".$Cola."','".$Canales."','".$TlfxRut."','".$TipoTelefono."','".$_SESSION["cedente"]."','".$Salida."','".$TipoCategorias."')";
		$id = $db->insert($SqlInsert);
		if($id){
			$Queue = $this->GetQueueDisponibleDiscado($id);
			if($Queue["result"]){
				$SqlInsertQueue = "insert into Asterisk_All_Queues (Queue,id_discador) values('".$Queue["Queue"]."','".$id."')";
				$InsertQueue = $db->query($SqlInsertQueue);
				if($InsertQueue){
					$ToReturn["result"] = true;
					$ToReturn["Queue"] = $Queue["Queue"];
				}
			}else{

			}
		}
		return $ToReturn;
	}
	function GetQueueDisponibleDiscado($idCola){
		$FocoConfig = getFocoConfig();
		$ToReturn = array();
		//create curl resource
		$ch = curl_init();
		//set url
		curl_setopt($ch, CURLOPT_URL, "http://".$FocoConfig['IpServidorDiscado']."/includes/queues/getQueueDisponibleDiscado-webService.php");
		//setup post Variables
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$FocoConfig['CodigoFoco']."&idCola=".$idCola);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//$output contains the output string 
		$output = curl_exec($ch);
		$ToReturn = $output;
		$ToReturn = json_decode($ToReturn,true);
		//close curl resource to free up system resources
		curl_close($ch);
		$asm = new AGI_AsteriskManager();
		$asm->connect($FocoConfig['IpServidorDiscado'],"lponce","lponce");
		$ChannelsReponse = $asm->command("reload");
		return $ToReturn;
	}
	function getQueueByDiscador($Discador){
		$db = new DB();
		$SqlQueue = "select Queue from Asterisk_All_Queues where id_discador = '".$Discador."'";
		$Queue = $db->select($SqlQueue);
		return $Queue[0]["Queue"];
	}
	function updateColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono){
		$db = new DB();
		$SqlUpdate = "UPDATE Asterisk_Discador_Cola set numero_canales = '".$Canales."', telfxrut = '".$TlfxRut."', tipo_telefono = '".$TipoTelefono."' where Cola = '".$Cola."' and Id_Cedente = '".$_SESSION['cedente']."'";
		$Update = $db->query($SqlUpdate);
	}
	function getColasDiscadores(){
		$ToReturn = array();
		$dbDiscador = new DB("discador");
		$db = new DB();
		$SqlColas = "select
						Cola.*,
						Q.Queue,
						(select GROUP_CONCAT(tipo_var) from SIS_Categoria_Fonos where FIND_IN_SET(color,Cola.tipo_telefono)) as Contacto
					from
						Asterisk_Discador_Cola Cola
							inner join Asterisk_All_Queues Q on Q.id_discador = Cola.id
					where
							Cola.Id_Cedente = '".$_SESSION['cedente']."'";
		$Colas = $db->select($SqlColas);
		if($Colas){
			foreach($Colas as $Cola){
				$ArrayTmp = array();

				$TablaColaDiscador = "DR_".$Cola["Queue"]."_".$Cola["Cola"];
				
				$SqlCantRuts = "select count(*) as Cantidad from (select count(*) as CantidadTmp from ".$TablaColaDiscador." group by Rut) tb1";
				$CantRuts = $dbDiscador->select($SqlCantRuts);
				if($CantRuts){
					$CantRuts = $CantRuts[0]["Cantidad"];
				}else{
					$CantRuts = 0;
				}

				$SqlCantRutsLlamados = "select count(*) as Cantidad from (select count(*) as CantidadTmp from ".$TablaColaDiscador." Where llamado='1' group by Rut) tb1";
				$CantRutsLlamados = $dbDiscador->select($SqlCantRutsLlamados);
				if(count($CantRutsLlamados) > 0){
					$CantRutsLlamados = $CantRutsLlamados[0]["Cantidad"];
				}else{
					$CantRutsLlamados = 0;
				}

				$SqlCantFonos = "select count(*) as Cantidad from ".$TablaColaDiscador;
				$CantFonos = $dbDiscador->select($SqlCantFonos);
				if($CantFonos){
					$CantFonos = $CantFonos[0]["Cantidad"];
				}else{
					$CantFonos = 0;
				}
				$SqlCantFonosLlamados = "select count(*) as Cantidad from ".$TablaColaDiscador." where llamado = '1' ";
				$CantFonosLlamados = $dbDiscador->select($SqlCantFonosLlamados);
				if($CantFonosLlamados){
					$CantFonosLlamados = $CantFonosLlamados[0]["Cantidad"];
				}else{
					$CantFonosLlamados = 0;
				}

				$SqlQueueEstatus = "select activo, Estado from Asterisk_All_Queues where Queue = '".$Cola["Queue"]."' ";
				$QueueEstatus = $dbDiscador->select($SqlQueueEstatus);
				if($QueueEstatus){
					$Activo = $QueueEstatus[0]["activo"];
					$Estado = $QueueEstatus[0]["Estado"];
				}else{
					$Activo = 0;
					$Estado = 0;
				}

				$SqlCantFonosGestionNegativa = "select count(*) as Cantidad from (select count(*) as Cantidad from auto_gestiones where id_discador = '".$Cola["id"]."' group by fono_discado) tb1";
				$CantFonosGestionNegativa = $dbDiscador->select($SqlCantFonosGestionNegativa);
				if($CantFonosGestionNegativa){
					$CantFonosGestionNegativa = $CantFonosGestionNegativa[0]["Cantidad"];
				}else{
					$CantFonosGestionNegativa = 0;
				}
				$SqlCantFonosLlamadosNegativos = "select count(*) as Cantidad from ".$TablaColaDiscador." where llamado = '2' ";
				$CantFonosLlamadosNegativos = $dbDiscador->select($SqlCantFonosLlamadosNegativos);
				if($CantFonosLlamadosNegativos){
					$CantFonosLlamadosNegativos = $CantFonosLlamadosNegativos[0]["Cantidad"];
				}else{
					$CantFonosLlamadosNegativos = 0;
				}
				
				$ArrayAsignacion = explode("_",$Cola["Cola"]);
				$TipoEntidad = $ArrayAsignacion[3];
				$idEntidad = $ArrayAsignacion[4];
				$Foco = $ArrayAsignacion[7];
				$Nombre = "";
				switch($TipoEntidad){
					case 'E':
					case 'S':
					break;
					case 'EE':
					break;
					case 'G':
						$GrupoClass = new Grupos();
						$Grupo = $GrupoClass->getGroup($idEntidad);
						$Nombre = utf8_encode($Grupo["Nombre"]);
					break;
				}
				$ArrayTmp["Cola"] = $Nombre;
				$ArrayTmp["Queue"] = $Cola["Queue"];
				$ArrayTmp["Canales"] = $Cola["numero_canales"];
				$ArrayTmp["TlfxRut"] = $Cola["telfxrut"];
				$ArrayTmp["tipoTelefono"] = $Cola["Contacto"];
				switch($Cola["TipoCategorias"]){
					case "Colores":
						$ArrayTmp["tipoTelefono"] = $Cola["Contacto"];
					break;
					case "Prioridad_Fonos":
						$ArrayTmp["tipoTelefono"] = $Cola["tipo_telefono"];
					break;
				}
				$ArrayTmp["TipoCategorias"] = $Cola["TipoCategorias"];
				$ArrayTmp["Reproduccion"] = $Cola["id"]."_".$Estado;
				$ArrayTmp["ProgresoRuts"] = $CantRutsLlamados."/".$CantRuts;
				$ArrayTmp["ProgresoFonos"] = $CantFonosLlamados."/".$CantFonos;
				$ArrayTmp["ProgresoReinicio"] = $CantFonosLlamadosNegativos."/".$CantFonosGestionNegativa;
				$ArrayTmp["Status"] = $Cola["id"]."_".$Activo;
				$ArrayTmp["Accion"] = $Cola["id"];
				array_push($ToReturn,$ArrayTmp);
			}
		}
		return $ToReturn;
	}
	function verColaDiscador($id){
		$ToReturn = array();
		$dbDiscador = new DB("discador");
		$db = new DB();
		$SqlColas = "select
						Cola.*,
						Q.Queue,
						(select GROUP_CONCAT(tipo_var) from SIS_Categoria_Fonos where FIND_IN_SET(color,Cola.tipo_telefono)) as Contacto
						from
							Asterisk_Discador_Cola Cola
								inner join Asterisk_All_Queues Q on Q.id_discador = Cola.id
						where
							Cola.id='".$id."'";
		$Colas = $db->select($SqlColas);
		if($Colas){
			foreach($Colas as $Cola){
				$ArrayTmp = array();

				$TablaColaDiscador = "DR_".$Cola["Queue"]."_".$Cola["Cola"];
				

				$SqlCantRuts = "select count(*) as Cantidad from (select count(*) as CantidadTmp from ".$TablaColaDiscador." group by Rut) tb1";
				$CantRuts = $dbDiscador->select($SqlCantRuts);
				$CantRuts = $CantRuts[0]["Cantidad"];

				$SqlCantRutsLlamados = "select count(*) as Cantidad from (select count(*) as CantidadTmp from ".$TablaColaDiscador." Where llamado='1' group by Rut) tb1";
				$CantRutsLlamados = $dbDiscador->select($SqlCantRutsLlamados);
				if(count($CantRutsLlamados) > 0){
					$CantRutsLlamados = $CantRutsLlamados[0]["Cantidad"];
				}else{
					$CantRutsLlamados = "0";
				}

				$SqlCantFonos = "select count(*) as Cantidad from ".$TablaColaDiscador;
				$CantFonos = $dbDiscador->select($SqlCantFonos);
				$CantFonos = $CantFonos[0]["Cantidad"];

				$SqlCantFonosLlamados = "select count(*) as Cantidad from ".$TablaColaDiscador." where llamado = '1' ";
				$CantFonosLlamados = $dbDiscador->select($SqlCantFonosLlamados);
				$CantFonosLlamados = $CantFonosLlamados[0]["Cantidad"];

				$SqlQueueEstatus = "select activo, Estado from Asterisk_All_Queues where Queue = '".$Cola["Queue"]."' ";
				$QueueEstatus = $dbDiscador->select($SqlQueueEstatus);
				$Activo = $QueueEstatus[0]["activo"];
				$Estado = $QueueEstatus[0]["Estado"];

				$SqlCantFonosGestionNegativa = "select count(*) as Cantidad from (select count(*) as Cantidad from auto_gestiones where id_discador = '".$Cola["id"]."' group by fono_discado) tb1";
				$CantFonosGestionNegativa = $dbDiscador->select($SqlCantFonosGestionNegativa);
				$CantFonosGestionNegativa = $CantFonosGestionNegativa[0]["Cantidad"];

				$SqlCantFonosLlamadosNegativos = "select count(*) as Cantidad from ".$TablaColaDiscador." where llamado = '2' ";
				$CantFonosLlamadosNegativos = $dbDiscador->select($SqlCantFonosLlamadosNegativos);
				$CantFonosLlamadosNegativos = $CantFonosLlamadosNegativos[0]["Cantidad"];

				$ArrayAsignacion = explode("_",$Cola["Cola"]);
				$TipoEntidad = $ArrayAsignacion[3];
				$idEntidad = $ArrayAsignacion[4];
				$Foco = $ArrayAsignacion[7];
				$Nombre = "";
				switch($TipoEntidad){
					case 'E':
					case 'S':
					break;
					case 'EE':
					break;
					case 'G':
						$GrupoClass = new Grupos();
						$Grupo = $GrupoClass->getGroup($idEntidad);
						$Nombre = utf8_encode($Grupo["Nombre"]);
					break;
				}
				$ArrayTmp["Cola"] = $Nombre;
				$ArrayTmp["Queue"] = $Cola["Queue"];
				$ArrayTmp["Canales"] = $Cola["numero_canales"];
				$ArrayTmp["TlfxRut"] = $Cola["telfxrut"];
				$ArrayTmp["tipoTelefono"] = $Cola["Contacto"];
				switch($Cola["TipoCategorias"]){
					case "Colores":
						$ArrayTmp["tipoTelefono"] = $Cola["Contacto"];
					break;
					case "Prioridad_Fonos":
						$ArrayTmp["tipoTelefono"] = $Cola["tipo_telefono"];
					break;
				}
				$ArrayTmp["TipoCategorias"] = $Cola["TipoCategorias"];
				$ArrayTmp["Reproduccion"] = $Cola["id"]."_".$Estado;
				$ArrayTmp["ProgresoRuts"] = $CantRutsLlamados."/".$CantRuts;
				$ArrayTmp["ProgresoFonos"] = $CantFonosLlamados."/".$CantFonos;
				$ArrayTmp["ProgresoReinicio"] = $CantFonosLlamadosNegativos."/".$CantFonosGestionNegativa;
				$ArrayTmp["Status"] = $Cola["id"]."_".$Activo;
				$ArrayTmp["Accion"] = $Cola["id"];
				array_push($ToReturn,$ArrayTmp);
			}
		}
		return $ToReturn;
	}
	function CambiarEstadoColaDiscado($idCola,$Value,$Provider,$TipoDiscado,$PAbandono){
		$ToReturn = array();

		$db = new DB();

		$dbDiscador = new DB("discador");
		
		$FocoConfig = getFocoConfig();

		$TipoCall = "";
		switch($Value){
			case "0":
				$SqlUpdate = "update Asterisk_All_Queues set Estado='".$Value."' where id_discador='".$idCola."'";
				$Update = $dbDiscador->query($SqlUpdate);

				$SqlUpdate = "update Asterisk_Discador_Cola set Estado='".$Value."' where id='".$idCola."'";
				$Update = $db->query($SqlUpdate);

				$SqlDelete = "delete from Actives_Queues where id_discador='".$idCola."' and CodigoFoco='".$FocoConfig["CodigoFoco"]."'";
				$Delete = $dbDiscador->query($SqlDelete);

				$ToReturn["message"] = "La cola fue detenida satisfactoriamente.";
			break;
			case "1":

				$SqlActivesQueues = "select * from Actives_Queues where id_discador='".$idCola."' and CodigoFoco='".$FocoConfig["CodigoFoco"]."'";
				$ActivesQueues = $dbDiscador->select($SqlActivesQueues);
				if(count($ActivesQueues) == 0){
					$SqlUpdate = "update Asterisk_All_Queues set Estado='".$Value."' where id_discador='".$idCola."'";
					$Update = $dbDiscador->query($SqlUpdate);

					$SqlUpdate = "update Asterisk_Discador_Cola set Estado='".$Value."' where id='".$idCola."'";
					$Update = $db->query($SqlUpdate);

					$Cola = $this->getColaDiscador($idCola);
					$TipoCall = "call";
					$this->StartCallPredictivo($idCola,$Cola["numero_canales"],$TipoCall,$TipoDiscado,$PAbandono);

					$SqlInsert = "insert into Actives_Queues (id_discador,tipo,fechahora,usuario,CodigoFoco) values ('".$idCola."','".$TipoCall."','".date("Y-m-d H:i:s")."','".$_SESSION["nombreUsuario"]."','".$FocoConfig["CodigoFoco"]."')";
					$Insert = $dbDiscador->query($SqlInsert);

					$ToReturn["message"] = "La cola fue iniciada satisfactoriamente.";
				}else{
					$ActivesQueues = $ActivesQueues[0];
					$Dia = date("d-m-Y",strtotime($ActivesQueues["fechahora"]));
					$Hora = date("H:i:s",strtotime($ActivesQueues["fechahora"]));
					$ToReturn["message"] = "Ya existe una ejecucion de la cola por <strong>".$ActivesQueues["usuario"]."</strong> el día: ".$Dia." a las: ".$Hora;
				}
			break;
			case "2":
				$SqlUpdate = "update Asterisk_All_Queues set Estado='".$Value."' where id_discador='".$idCola."'";
				$Update = $dbDiscador->query($SqlUpdate);

				$SqlUpdate = "update Asterisk_Discador_Cola set Estado='".$Value."' where id='".$idCola."'";
				$Update = $db->query($SqlUpdate);

				$SqlDelete = "delete from Actives_Queues where id_discador='".$idCola."' and CodigoFoco='".$FocoConfig["CodigoFoco"]."'";
				$Delete = $dbDiscador->query($SqlDelete);

				$ToReturn["message"] = "La cola fue pausada satisfactoriamente.";
			break;
			case "3":
				$SqlActivesQueues = "select * from Actives_Queues where id_discador='".$idCola."' and CodigoFoco='".$FocoConfig["CodigoFoco"]."'";
				$ActivesQueues = $dbDiscador->select($SqlActivesQueues);
				if(count($ActivesQueues) == 0){
					$SqlUpdate = "update Asterisk_All_Queues set Estado='".$Value."' where id_discador='".$idCola."'";
					$Update = $dbDiscador->query($SqlUpdate);

					$SqlUpdate = "update Asterisk_Discador_Cola set Estado='".$Value."' where id='".$idCola."'";
					$Update = $db->query($SqlUpdate);

					$Cola = $this->getColaDiscador($idCola);
					$TipoCall = "re-call";
					$this->StartCallPredictivo($idCola,$Cola["numero_canales"],$TipoCall,$TipoDiscado,$PAbandono);

					$SqlInsert = "insert into Actives_Queues (id_discador,tipo,fechahora,usuario,CodigoFoco) values ('".$idCola."','".$TipoCall."','".date("Y-m-d H:i:s")."','".$_SESSION["nombreUsuario"]."','".$FocoConfig["CodigoFoco"]."')";
					$Insert = $dbDiscador->query($SqlInsert);

					$ToReturn["message"] = "La cola fue reiniciado satisfactoriamente.";
				}else{
					$ActivesQueues = $ActivesQueues[0];
					$Dia = date("d-m-Y",strtotime($ActivesQueues["fechahora"]));
					$Hora = date("H:i:s",strtotime($ActivesQueues["fechahora"]));
					$ToReturn["message"] = "Ya existe una ejecucion de la cola por <strong>".$ActivesQueues["usuario"]."</strong> el día: ".$Dia." a las: ".$Hora;
				}
			break;
			case "4":
				$SqlUpdate = "update Asterisk_All_Queues set Estado='0' where id_discador='".$idCola."'";
				$Update = $dbDiscador->query($SqlUpdate);

				$SqlUpdate = "update Asterisk_Discador_Cola set Estado='0' where id='".$idCola."'";
				$Update = $db->query($SqlUpdate);
				
				$this->actualizarColaDiscador($idCola);
				$ToReturn["message"] = "La cola fue actualizada satisfactoriamente.";
			break;
		}
		//$ToReturn["message"] = utf8_encode($ToReturn["message"]);
		return $ToReturn;
	}
	function StartCallPredictivo($idCola,$NumeroCanales,$TipoCall,$TipoDiscado,$PAbandono){
		$FocoConfig = getFocoConfig();
		$ToReturn = array();
		//create curl resource
		$ch = curl_init();
		//set url
		curl_setopt($ch, CURLOPT_URL, "http://".$FocoConfig['IpServidorDiscado']."/includes/discador/callQueue-webService.php");
		//setup post Variables
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$FocoConfig['CodigoFoco']."&idCola=".$idCola."&NumeroCanales=".$NumeroCanales."&IpDiscador=".$FocoConfig['IpServidorDiscado']."&TipoCall=".$TipoCall."&TipoDiscado=".$TipoDiscado."&PAbandono=".$PAbandono);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//$output contains the output string 
		$output = curl_exec($ch);
		echo $ToReturn = $output;
		//$ToReturn = json_decode($ToReturn,true);
		//close curl resource to free up system resources
		curl_close($ch);
	}
	function EliminarColaDiscador($Discador){
		$ToReturn["result"] = false;
		$dbDiscador = new DB("discador");
		$db = new DB();
		/*$Queue = $this->getQueueByDiscador($Discador);
		$ToReturn["Queue"] = $Queue;*/
		$deleteQueueDiscador = $this->DeleteQueueDiscado($Discador);
		if($deleteQueueDiscador["result"]){
			$Queue = $deleteQueueDiscador["Queue"];
			$Prefix = "DR_".$Queue;
			$SqlSelectTable = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='discador' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) >= 7 and TABLE_NAME like '".$Prefix."%' order by TABLE_NAME";
			$SelectTable = $dbDiscador->select($SqlSelectTable);
			foreach($SelectTable as $Table){
				$SqlDropTable = "DROP TABLE ".$Table["tabla"];
				$DropTable = $dbDiscador->query($SqlDropTable);
				if($DropTable){
					$SqlDelete = "delete from Asterisk_Discador_Cola where id='".$Discador."'";
					$Delete = $db->query($SqlDelete);
					if($Delete){
						$SqlUpdateQueue = "delete from Asterisk_All_Queues where id_discador='".$Discador."'";
						$UpdateQueue = $db->query($SqlUpdateQueue);
						if($UpdateQueue){
							$this->dropTablesDiscador($Queue);
							$ToReturn["result"] = true;
						}
					}
				}
			}
		}
		return $ToReturn;
	}
	function EliminarAsignacionesSinEstrategias(){
		set_time_limit(0);
		$db = new DB();
		$query = "SELECT asignacion as TABLE_NAME FROM SIS_Querys_Estrategias sqe RIGHT JOIN asignacion_cola a ON a.id_cola = sqe.id WHERE sqe.id IS NULL";
		$Asignaciones = $db->select($query);
		$ToReturn = array();
		foreach($Asignaciones as $Asignacion){
			$QR1 = $Asignacion['TABLE_NAME'];
			$query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '".$QR1."'";
			$Existe = $db->select($query);
			if($Existe){
				array_push($ToReturn,$QR1);
				$query = "SELECT SUBSTRING_INDEX( asignacion, '_', 3 ) as TABLE_NAME FROM SIS_Querys_Estrategias sqe RIGHT JOIN asignacion_cola a ON a.id_cola = sqe.id WHERE sqe.id IS NULL AND asignacion = '".$QR1."'";
				$Tabla = $db->select($query);
				if($Tabla){
					$QR2 = $Tabla[0]['TABLE_NAME'];
					$query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '".$QR2."'";
					$Existe = $db->select($query);
					if($Existe){
						array_push($ToReturn,$QR2);
						$query = "DROP TABLE " . $QR2;
						$db->query($query);
					}
				}
				$query = "DROP TABLE " . $QR1;
				$db->query($query);
			}
		}
		$query = "DELETE a FROM asignacion_cola a LEFT JOIN SIS_Querys_Estrategias sqe ON a.id_cola = sqe.id WHERE sqe.id IS NULL";
		return $ToReturn;
	}
	function DeleteQueueDiscado($idCola){
		$FocoConfig = getFocoConfig();
		$ToReturn = array();
		//create curl resource
		$ch = curl_init();
		//set url
		curl_setopt($ch, CURLOPT_URL, "http://".$FocoConfig['IpServidorDiscado']."/includes/queues/deleteQueueDiscado-webService.php");
		//setup post Variables
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$FocoConfig['CodigoFoco']."&idCola=".$idCola);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//$output contains the output string 
		$output = curl_exec($ch);
		$ToReturn = $output;
		$ToReturn = json_decode($ToReturn,true);
		//close curl resource to free up system resources
		curl_close($ch);
		$asm = new AGI_AsteriskManager();
		$asm->connect($FocoConfig['IpServidorDiscado'],"lponce","lponce");
		$ChannelsReponse = $asm->command("reload");
		return $ToReturn;
	}
	function ReiniciarColaDiscado($Discador){
		$dbDiscador = new DB("discador");
		$Queue = $this->getQueueByDiscador($Discador);
		$SqlSelectTable = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='discador' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) >= 7 and TABLE_NAME like 'DR_".$Queue."%' order by TABLE_NAME";
		$SelectTable = $dbDiscador->select($SqlSelectTable);
		foreach($SelectTable as $Table){
			$SqlReinicio = "update ".$Table["tabla"]." set llamado='0'";
			$Reinicio = $dbDiscador->query($SqlReinicio);
		}
		$this->DetenerColaDiscado($Discador);
	}
	function CambiarStatusColaDiscado($Cola,$Value){
		$dbDiscador = new DB("discador");
		$SqlUpdate = "update Asterisk_All_Queues set activo='".$Value."' where id_discador='".$Cola."'";
		$Update = $dbDiscador->query($SqlUpdate);
		$db = new DB();
		$SqlUpdate = "update Asterisk_Discador_Cola set Status='".$Value."' where id='".$Cola."'";
		$Update = $db->query($SqlUpdate);
	}
	function IniciarColaDiscado($Discador){
		$db = new DB();
		$SqlUpdate = "update Asterisk_Discador_Cola set FeMin = NOW(), Id_Usuario='".$_SESSION['id_usuario']."' where id = '".$Discador."'";
		$Update = $db->query($SqlUpdate);
	}
	function DetenerColaDiscado($Discador){
		$db = new DB();
		$SqlUpdate = "update Asterisk_Discador_Cola set FeMin = '',FeFin = '', Id_Usuario='0' where id = '".$Discador."'";
		$Update = $db->query($SqlUpdate);
	}
	function FindQueueFinished(){
		$ToReturn = array();
		$db = new DB();
		$SqlFinishedQueue = "select Asterisk_Discador_Cola.*,Asterisk_All_Queues.Queue from Asterisk_Discador_Cola inner join Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id where FeMin <= FeFin and Estado='0' and FeMin > '' and Id_Cedente='".$_SESSION['cedente']."'";
		$FinishedQueue = $db->select($SqlFinishedQueue);
		if($FinishedQueue){
			foreach($FinishedQueue as $Queue){
				$ArrayAsignacion = explode("_",$Queue["Cola"]);
				$TipoEntidad = $ArrayAsignacion[3];
				$idEntidad = $ArrayAsignacion[4];
				$Foco = $ArrayAsignacion[7];
				$Nombre = "";
				switch($TipoEntidad){
					case 'E':
					case 'S':
					break;
					case 'EE':
					break;
					case 'G':
						$GrupoClass = new Grupos();
						$Grupo = $GrupoClass->getGroup($idEntidad);
						$Nombre = utf8_encode($Grupo["Nombre"]);
					break;
				}
				$ArrayTmp = array();
				$ArrayTmp['Queue'] = $Queue["Queue"];
				$ArrayTmp['Cola'] = $Nombre;
				if($_SESSION['id_usuario'] == $Queue["Id_Usuario"]){
					array_push($ToReturn,$ArrayTmp);
					$this->DetenerColaDiscado($Queue["id"]);
					$this->ReiniciarColaDiscado($Queue["id"]);
				}
			}
		}
		return $ToReturn;
	}
	function getDeudaFromRuts($Ruts){
		$db = new DB();
		if(count($Ruts) > 0){
			$RutsImplode = implode(",",$Ruts);
			$SqlDeuda = "select SUM(Deuda) as Deuda from Deuda where Rut in (".$RutsImplode.")";
			$Deuda = $db->select($SqlDeuda);
			$Deuda = $Deuda[0]["Deuda"];
		}else{
			$Deuda = 0;
		}
		return $Deuda;
	}
	function getFechasPeriodosCargas(){
		$db = new DB();
		$ToReturn = array();
		$SqlFechas = "select * from Historico_Carga where Id_Cedente='".$_SESSION['cedente']."' and fecha_fin > NOW() order by id DESC LIMIT 1";
		$Fechas = $db->select($SqlFechas);
		if(count($Fechas) > 0){
			$Fechas = $Fechas[0];
			$ToReturn["Desde"] = $Fechas["fecha"];
			$ToReturn["Hasta"] = $Fechas["fecha_fin"];
		}else{
			$SqlFechas = "select * from Historico_Carga where Id_Cedente='".$_SESSION['cedente']."' order by id DESC LIMIT 1";
			$Fechas = $db->select($SqlFechas);
			if(count($Fechas) > 0){
				$Fechas = $Fechas[0];
				$ToReturn["Desde"] = $Fechas["fecha"];
				$ToReturn["Hasta"] = date("Ymd");
			}else{
				$ToReturn["Desde"] = date("Ym01");
				$ToReturn["Hasta"] = date("Ymd");
			}
		}
		return $ToReturn;
	}
	function CopyPersonaDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsPersona = $this->getCamposTabla("Persona","foco","Id_Cedente,Mandante,id_persona");
		$ColumnasPersonaImplode = implode(",",$ColumnsPersona);

		$SqlPersonas = "select ".$ColumnasPersonaImplode.",Id_Cedente,Mandante from Persona inner join ".$ColaQR." QR on QR.Rut = Persona.Rut";

		foreach($ColumnsPersona as $key => $Column){
			$ColumnsPersona[$key] = str_replace("Persona.","",$Column);
		}
		$ColumnasPersonaImplode = implode(",",$ColumnsPersona);

		$Personas = $db->select($SqlPersonas);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Personas as $Persona){
			$Values = array();
			foreach($ColumnsPersona as $Field){
				$Valor = utf8_encode($Persona[$Field]);
				$Valor = str_replace("'","",$Valor);
				array_push($Values,"'".$Valor."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.",'".$_SESSION['cedente']."','".$_SESSION['mandante']."')";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}
		$SqlCreateTable = $this->getTableStructure("Persona",$Queue);
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertPersonaDiscador = "INSERT INTO ".$Queue."_Persona (".$ColumnasPersonaImplode.",Id_Cedente,Mandante) values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertPersonaDiscador);
				}
			}
		}
	}
	function CopyDeudaDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsDeuda = $this->getCamposTabla("Deuda","foco","Id_deuda");
		$ColumnasDeudaImplode = implode(",",$ColumnsDeuda);
		
		$SqlDeudas = "select ".$ColumnasDeudaImplode." from Deuda inner join ".$ColaQR." QR on QR.Rut = Deuda.Rut where Id_Cedente='".$_SESSION['cedente']."'";

		foreach($ColumnsDeuda as $key => $Column){
			$ColumnsDeuda[$key] = str_replace("Deuda.","",$Column);
		}
		$ColumnasDeudaImplode = implode(",",$ColumnsDeuda);

		$Deudas = $db->select($SqlDeudas);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Deudas as $Deuda){
			$Values = array();
			foreach($ColumnsDeuda as $Field){
				$Valor = utf8_encode($Deuda[$Field]);
				$Valor = str_replace("'","",$Valor);
				array_push($Values,"'".$Valor."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.")";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}
		$SqlCreateTable = $this->getTableStructure("Deuda",$Queue);
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = "INSERT INTO ".$Queue."_Deuda (".$ColumnasDeudaImplode.") values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function CopyFonoDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsFono = $this->getCamposTabla("fono_cob","foco","id_fono");
		$ColumnasFonoImplode = implode(",",$ColumnsFono);

		$SqlFonos = "select ".$ColumnasFonoImplode." from fono_cob inner join ".$ColaQR." QR on QR.Rut = fono_cob.Rut";

		foreach($ColumnsFono as $key => $Column){
			$ColumnsFono[$key] = str_replace("fono_cob.","",$Column);
		}
		$ColumnasFonoImplode = implode(",",$ColumnsFono);

		$Fonos = $db->select($SqlFonos);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Fonos as $Fono){
			$Values = array();
			foreach($ColumnsFono as $Field){
				array_push($Values,"'".utf8_encode($Fono[$Field])."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.")";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}
		$SqlCreateTable = $this->getTableStructure("fono_cob",$Queue);
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = "INSERT INTO ".$Queue."_fono_cob (".$ColumnasFonoImplode.") values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function CopyMailDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsMail = $this->getCamposTabla("Mail","foco","id_mail");
		$ColumnasMailImplode = implode(",",$ColumnsMail);

		$SqlMails = "select ".$ColumnasMailImplode." from Mail inner join ".$ColaQR." QR on QR.Rut = Mail.Rut";

		foreach($ColumnsMail as $key => $Column){
			$ColumnsMail[$key] = str_replace("Mail.","",$Column);
		}
		$ColumnasMailImplode = implode(",",$ColumnsMail);

		$Mails = $db->select($SqlMails);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Mails as $Mail){
			$Values = array();
			foreach($ColumnsMail as $Field){
				$Valor = utf8_encode($Mail[$Field]);
				$Valor = str_replace("'","",$Valor);
				array_push($Values,"'".$Valor."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.")";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}
		$SqlCreateTable = $this->getTableStructure("Mail",$Queue);
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = "INSERT INTO ".$Queue."_Mail (".$ColumnasMailImplode.") values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function CopyDireccionesDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsDirecciones = $this->getCamposTabla("Direcciones","foco","Id_Direccion");
		$ColumnasDireccionesImplode = implode(",",$ColumnsDirecciones);

		$SqlDirecciones = "select ".$ColumnasDireccionesImplode." from Direcciones inner join ".$ColaQR." QR on QR.Rut = Direcciones.Rut";

		foreach($ColumnsDirecciones as $key => $Column){
			$ColumnsDirecciones[$key] = str_replace("Direcciones.","",$Column);
		}
		$ColumnasDireccionesImplode = implode(",",$ColumnsDirecciones);

		$Direcciones = $db->select($SqlDirecciones);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Direcciones as $Direccion){
			$Values = array();
			foreach($ColumnsDirecciones as $Field){
				$Valor = utf8_encode($Direccion[$Field]);
				$Valor = str_replace("'","",$Valor);
				array_push($Values,"'".$Valor."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.")";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}

		$SqlCreateTable = $this->getTableStructure("Direcciones",$Queue);
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = "INSERT INTO ".$Queue."_Direcciones (".$ColumnasDireccionesImplode.") values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function CopyMejorGestionDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsMejorGestion = $this->getCamposTabla("Mejor_Gestion_Historica");
		$ColumnasMejorGestionImplode = implode(",",$ColumnsMejorGestion);
		
		$SqlMejorGestionHistorica = "select ".$ColumnasMejorGestionImplode." from Mejor_Gestion_Historica inner join ".$ColaQR." QR on QR.Rut = Mejor_Gestion_Historica.Rut";
		$SqlCreateTable = $this->getTableStructure("Mejor_Gestion_Historica",$Queue);
		
		foreach($ColumnsMejorGestion as $key => $Column){
			$ColumnsMejorGestion[$key] = str_replace("Mejor_Gestion_Historica.","",$Column);
		}
		$ColumnasMejorGestionImplode = implode(",",$ColumnsMejorGestion);

		$MejorGestionHistorica = $db->select($SqlMejorGestionHistorica);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($MejorGestionHistorica as $Row){
			$Values = array();
			foreach($ColumnsMejorGestion as $Field){
				array_push($Values,"'".utf8_encode($Row[$Field])."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.")";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}

		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = "INSERT INTO ".$Queue."_Mejor_Gestion_Historica (".$ColumnasMejorGestionImplode.") values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function CopyUltimaGestionDataToPredictivo($ColaQR,$Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$ColumnsUltimaGestion = $this->getCamposTabla("Ultima_Gestion_Historica");
		$ColumnasUltimaGestionImplode = implode(",",$ColumnsUltimaGestion);
		
		$SqlUltimaGestionHistorica = "select ".$ColumnasUltimaGestionImplode." from Ultima_Gestion_Historica inner join ".$ColaQR." QR on QR.Rut = Ultima_Gestion_Historica.Rut";

		foreach($ColumnsUltimaGestion as $key => $Column){
			$ColumnsUltimaGestion[$key] = str_replace("Ultima_Gestion_Historica.","",$Column);
		}
		$ColumnasUltimaGestionImplode = implode(",",$ColumnsUltimaGestion);

		$UltimaGestionHistorica = $db->select($SqlUltimaGestionHistorica);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($UltimaGestionHistorica as $Row){
			$Values = array();
			foreach($ColumnsUltimaGestion as $Field){
				array_push($Values,"'".utf8_encode($Row[$Field])."'");
			}
			$ValuesImplode = implode(",",$Values);
			$Value = "(".$ValuesImplode.")";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}

		$SqlCreateTable = $this->getTableStructure("Ultima_Gestion_Historica",$Queue);
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = "INSERT INTO ".$Queue."_Ultima_Gestion_Historica (".$ColumnasUltimaGestionImplode.") values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function CopyColumnasCRMDataToPredictivo($Queue){
		$db = new DB();
		$dbDiscador = new DB("discador");

		$SqlColumns = "select
						SIS_Columnas_Estrategias.columna as columna,
						SIS_Columnas_Estrategias.tipo_dato,
						SIS_Columnas_Estrategias.suma,
						Columnas_Asignacion_CRM.prioridad,
						Columnas_Asignacion_CRM.destacar
					from
						SIS_Columnas_Estrategias
							inner join Columnas_Asignacion_CRM on Columnas_Asignacion_CRM.columna = SIS_Columnas_Estrategias.columna
					where
						Columnas_Asignacion_CRM.Id_Cedente='".$_SESSION["cedente"]."'
					order by
						Columnas_Asignacion_CRM.prioridad";

		$Columns = $db->select($SqlColumns);
		$Array1000 = array();
		$Cont1000 = 0;
		$Array1000[$Cont1000] = array();
		foreach($Columns as $Row){
			$Value = "('".$Row["columna"]."','".$Row["tipo_dato"]."','".$Row["suma"]."','".$Row["prioridad"]."','".$Row["destacar"]."')";
			array_push($Array1000[$Cont1000],$Value);
			if(count($Array1000[$Cont1000]) == 1000){
				$Cont1000++;
				$Array1000[$Cont1000] = array();
			}
		}

		$SqlCreateTable = "CREATE TABLE ".$Queue."_Columnas_Asignacion_CRM ( 
							id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
							columna varchar(100) NOT NULL, 
							tipo_dato int NOT NULL, 
							suma int NOT NULL, 
							prioridad int NOT NULL,
							destacar int NOT NULL)";
		$CreateTable = $dbDiscador->query($SqlCreateTable);
		if($CreateTable){
			foreach($Array1000 as $ValuesArray){
				if(count($ValuesArray) > 0){
					$Values = implode(",",$ValuesArray);
					$SqlInsertDeudaDiscador = " INSERT  INTO ".$Queue."_Columnas_Asignacion_CRM (columna,tipo_dato,suma,prioridad,destacar) values ".$Values;
					$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
				}
			}
		}
	}
	function getCamposTabla($Tabla,$Link = "foco",$Exclusiones = ""){
		$ToReturn = array();
		$db = new DB($Link);
		//$SqlColumns = "DESCRIBE ".$Tabla;
		$SqlColumns = "SELECT COLUMN_NAME as Field FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='foco' AND TABLE_NAME='".$Tabla."' AND COLUMN_KEY <> 'PRI'";
		$Columns = $db->select($SqlColumns);
		$ArrayExclusiones = explode(",",$Exclusiones);
		foreach($Columns as $Column){
			$Flag = true;
			foreach($ArrayExclusiones as $Exclusion){
				if($Column["Field"] == $Exclusion){
					$Flag = false;
				}
			}
			if($Flag){
				//if($Column["Key"] != "PRI"){
					array_push($ToReturn,$Tabla.".".$Column["Field"]);
				//}
			}
		}
		return $ToReturn;
	}
	function actualizarColaDiscador($idDiscador){
		$db = new DB();
		$dbDiscador = new DB("discador");
		$SqlColaDiscador = "SELECT Asterisk_Discador_Cola.*, Asterisk_All_Queues.Queue FROM Asterisk_Discador_Cola inner join Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id where Asterisk_Discador_Cola.id='".$idDiscador."'";
		$ColaDiscador = $db->select($SqlColaDiscador);
		foreach($ColaDiscador as $Cola){
			$PrefixColaArray = explode("_",$Cola["Cola"]);
			$Prefix = $PrefixColaArray[0]."_".$PrefixColaArray[1]."_".$PrefixColaArray[2];
			$this->actualizarCola($PrefixColaArray[2]);
			$this->updateAsignaciones($Prefix);
			/*$WhereTipoCategorias = "";
			switch($Cola["TipoCategorias"]){
				case "Colores":
					$WhereTipoCategorias = "SIS_Categoria_Fonos.color in (".$Cola["tipo_telefono"].") and";
					$WhereSubTipoCategorias = "cf.color in (".$Cola["tipo_telefono"].") and";
				break;
				case "Prioridad_Fonos":
					$WhereTipoCategorias = "fono_cob.Prioridad_Fono	in (".$Cola["tipo_telefono"].") and";
					$WhereSubTipoCategorias = "f.Prioridad_Fono	in (".$Cola["tipo_telefono"].") and";
				break;
			}

			$focoConfig = getFocoConfig();

			$SqlIpLinkedServer = "select IpLinkedServer from foco where CodigoFoco = '".$focoConfig["CodigoFoco"]."'";
			$IpLinkedServer = $dbDiscador->select($SqlIpLinkedServer);
			if(count($IpLinkedServer) > 0){
				$IpLinkedServer = $IpLinkedServer[0]["IpLinkedServer"];
			}else{
				$IpLinkedServer = "";
			}

			$SqlRuts = "select
						value as Fono,Rut, '".$_SESSION["cedente"]."' as Cedente, '".$focoConfig["CodigoFoco"]."' as CodigoFoco
					from
						(select
							QR.Rut,
							SUBSTRING((SELECT TOP(".$Cola["telfxrut"].") STUFF(CONVERT(f.formato_subtel,CHAR(50)),1,0,',') FROM [".$IpLinkedServer."].[foco].[dbo].fono_cob f inner join [".$IpLinkedServer."].[foco].[dbo].SIS_Categoria_Fonos cf on cf.color = f.color WHERE ".$WhereSubTipoCategorias." f.Rut = QR.Rut AND LEN(f.formato_subtel)=9 order by cf.prioridad FOR XML PATH ('')),2,99999999) AS Fono
						from
							[".$IpLinkedServer."].[foco].[dbo].".$Cola["Cola"]." QR
								inner join [".$IpLinkedServer."].[foco].[dbo].fono_cob on fono_cob.Rut = QR.Rut
								inner join [".$IpLinkedServer."].[foco].[dbo].SIS_Categoria_Fonos on SIS_Categoria_Fonos.color = fono_cob.color
						where
							".$WhereTipoCategorias."
							fono_cob.vigente = '1' 
						group by
							QR.rut) tb1
					cross apply STRING_SPLIT (Fono, ',')  Fono";*/

			$WhereTipoCategorias = "";
			$WhereSubTipoCategorias = "";
			switch($Cola["TipoCategorias"]){
				case "Colores":
					$WhereTipoCategorias = "SIS_Categoria_Fonos.color in (".$Cola["tipo_telefono"].") and";
					$WhereSubTipoCategorias = "cf.color in (".$Cola["tipo_telefono"].") and";
				break;
				case "Prioridad_Fonos":
					$WhereTipoCategorias = "fono_cob.Prioridad_Fono	in (".$Cola["tipo_telefono"].") and";
					$WhereSubTipoCategorias = "f.Prioridad_Fono	in (".$Cola["tipo_telefono"].") and";
				break;
			}

			$SqlTruncate = "TRUNCATE TABLE [DR_".$Cola["Queue"]."_".$Cola["Cola"]."]";
			$Truncate = $dbDiscador->query($SqlTruncate);
			if($Truncate){
				$focoConfig = getFocoConfig();
	
				/* $SqlRuts = "select
								QR.Rut as Rut,
								(SELECT substring_index(GROUP_CONCAT(f.formato_subtel),',',".$Cola["telfxrut"].") FROM fono_cob f inner join SIS_Categoria_Fonos cf on cf.color = f.color WHERE ".$WhereSubTipoCategorias." f.Rut = QR.Rut AND LENGTH(f.formato_subtel)=9 AND fono_cob.vigente = '1' order by cf.prioridad) AS Fono
							from
								".$Cola["Cola"]." QR
									inner join fono_cob on fono_cob.Rut = QR.Rut
									inner join SIS_Categoria_Fonos on SIS_Categoria_Fonos.color = fono_cob.color
							where
								".$WhereTipoCategorias."
								fono_cob.vigente = '1' 
							group by
								QR.rut"; */
				$SqlRuts = "select
								QR.Rut as Rut,
								(SELECT substring_index(GROUP_CONCAT(f.formato_subtel),',',".$Cola["telfxrut"].") FROM fono_cob f inner join SIS_Categoria_Fonos cf on cf.color = f.color WHERE ".$WhereSubTipoCategorias." f.Rut = QR.Rut AND LENGTH(f.formato_subtel)=9 AND f.vigente = '1' AND f.formato_subtel NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 2 AND Id_Cedente = '".$_SESSION['cedente']."' AND Fecha_Term >= CURDATE()) order by cf.prioridad) AS Fono
							from
								".$Cola["Cola"]." QR
							WHERE
								Rut NOT IN (SELECT Dato FROM Exclusiones WHERE Tipo = 1 AND Id_Cedente = '".$_SESSION["cedente"]."' AND Fecha_Term >= CURDATE())
							group by
								QR.rut";
				$Ruts = $db->select($SqlRuts);
				$Array1000 = array();
				$Cont1000 = 0;
				$Array1000[$Cont1000] = array();
				foreach($Ruts as $RutArray){
					$Rut = $RutArray["Rut"];
					$Fonos = $RutArray["Fono"];
					$FonosArray = explode(",",$Fonos);
					foreach($FonosArray as $Fono){
						if($Fono != ""){
							$Value = "('".$Fono."','".$Rut."','".$_SESSION["cedente"]."','".$focoConfig["CodigoFoco"]."')";
							array_push($Array1000[$Cont1000],$Value);
							if(count($Array1000[$Cont1000]) == 1000){
								$Cont1000++;
								$Array1000[$Cont1000] = array();
							}
						}
					}
				}

				foreach($Array1000 as $ValuesArray){
					if(count($ValuesArray) > 0){
						$Values = implode(",",$ValuesArray);
						$SqlInsert = "INSERT IGNORE INTO DR_".$Cola["Queue"]."_".$Cola["Cola"]." (Fono,Rut,Cedente,CodigoFoco) values ".$Values;
						$Insert = $dbDiscador->query($SqlInsert);
					}
				}

				/*$SqlInsert = "INSERT INTO DR_".$Cola["Queue"]."_".$Cola["Cola"]." (Fono,Rut,Cedente,CodigoFoco) ".$SqlRuts;
				$Insert = $dbDiscador->query($SqlInsert);*/
				//if($Insert){
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Persona";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyPersonaDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Deuda";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyDeudaDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_fono_cob";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyFonoDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Mail";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyMailDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Direcciones";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyDireccionesDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Mejor_Gestion_Historica";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyMejorGestionDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Ultima_Gestion_Historica";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyUltimaGestionDataToPredictivo($Cola["Cola"],$Cola["Queue"]);
					}
					$SqlDrop = "DROP TABLE ".$Cola["Queue"]."_Columnas_Asignacion_CRM";
					$Drop = $dbDiscador->query($SqlDrop);
					if($Drop){
						$this->CopyColumnasCRMDataToPredictivo($Cola["Queue"]);
					}
				//}
			}
		}
	}
	function getCola($idCola){
		$db = new DB();
		$SqlCola = "SELECT * FROM SIS_Querys_Estrategias WHERE id = '".$idCola."'";
		$Cola = $db->select($SqlCola);
		if($Cola){
			$Cola = $Cola[0];
		}else{
			$Cola = array();
		}
		return $Cola;
	}
	function getEstrategia($idEstrategia){
		$db = new DB();
		$query = "SELECT * FROM SIS_Estrategias WHERE id = '".$idEstrategia."'";
		$Estrategia = $db->select($query);
		if($Estrategia){
			$Estrategia = $Estrategia[0];
		}else{
			$Estrategia = array();
		}
		
		return $Estrategia;
	}
	function getEjecutivosActivos(){
		$db = new DB();
		$SqlEjecutivos = "select Usuarios.id as idUsuario, Personal.Nombre as Nombre from Personal inner join Usuarios on Usuarios.id = Personal.id_usuario where Personal.Activo = '1' and Usuarios.nivel in ('4','2') order by Personal.Nombre";
		$Ejecutivos = $db->select($SqlEjecutivos);
		return $Ejecutivos;
	}
	function updateColaCautiva($idCola,$idUserCautiva,$StatusCautiva){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		$idUserCautiva = $StatusCautiva == "0" ? "" : $idUserCautiva;
		$SqlInsert = "update SIS_Querys_Estrategias set cautiva='".$StatusCautiva."', idUserCautiva='".$idUserCautiva."' where id='".$idCola."'";
		$Insert = $db->query($SqlInsert);
		if($Insert){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}
	/*function getTableStructure($Table,$Queue){
		$db = new DB();
		$SqlColumns = "exec sp_columns ".$Table;
		$Columns = $db->select($SqlColumns);
		$Cols = array();
		foreach($Columns as $Column){
			$NullSQL = "";
			$keySQL = "";
			$DefaultSQL = "";
			$ExtraSQL = "";
			$Type = "";
			switch($Column["NULLABLE"]){
				case "0":
					$NullSQL = " NOT NULL ";
				break;
			}
			switch($Column["TYPE_NAME"]){
				case "int identity":
					$ExtraSQL = " IDENTITY(1,1) ";
				break;
			}
			switch($Column["TYPE_NAME"]){
				case "int identity":
					$Type = " int ";
				break;
				case "varchar":
					$Type = " varchar(".$Column["PRECISION"].") ";
				break;
				default:
					$Type = " ".$Column["TYPE_NAME"]." ";
				break;
			}
			if($Column["COLUMN_DEF"] != ""){
				$Default = str_replace("(","",$Column["COLUMN_DEF"]);
				$Default = str_replace(")","",$Default);
				$Default = str_replace("'","",$Default);
				$DefaultSQL = " DEFAULT '".$Default."' ";
			}
			$Col = $Column["COLUMN_NAME"]." ".$Type.$NullSQL.$keySQL.$DefaultSQL.$ExtraSQL." ";
			array_push($Cols,$Col);
		}
		$ColsImplode = implode(",",$Cols);
		$Create = "if not exists (select * from sysobjects where name='".$Queue."_".$Table."' and xtype='U') CREATE TABLE [".$Queue."_".$Table."](".$ColsImplode.")";
		return $Create;
	}*/
	function getTableStructure($Table,$Queue){
		$db = new DB();
		$SqlColumns = "describe ".$Table;
		$Columns = $db->select($SqlColumns);
		$Cols = array();
		foreach($Columns as $Column){
			$NullSQL = "";
			$keySQL = "";
			$DefaultSQL = "";
			$ExtraSQL = "";
			switch($Column["Null"]){
				case "NO":
					$NullSQL = " NOT NULL ";
				break;
			}
			switch($Column["Key"]){
				case "PRI":
					$keySQL = " PRIMARY KEY ";
				break;
			}
			switch($Column["Extra"]){
				case "auto_increment":
					$ExtraSQL = " AUTO_INCREMENT ";
				break;
			}
			if($Column["Default"] != ""){
				$DefaultSQL = " DEFAULT '".$Column["Default"]."' ";
			}
			$Col = $Column["Field"]." ".$Column["Type"].$NullSQL.$keySQL.$DefaultSQL.$ExtraSQL." ";
			array_push($Cols,$Col);
		}
		$ColsImplode = implode(",",$Cols);
		$Create = "CREATE TABLE IF NOT EXISTS ".$Queue."_".$Table."(".$ColsImplode.") ENGINE = MyISAM";
		return $Create;
	}
	function dropTablesDiscador($Queue){
		$dbDiscador = new DB("discador");
		$Tables = array();
		array_push($Tables,$Queue."_Persona");
		array_push($Tables,$Queue."_Deuda");
		//array_push($Tables,$Queue."_fono_cob");
		array_push($Tables,$Queue."_Mail");
		array_push($Tables,$Queue."_Direcciones");
		array_push($Tables,$Queue."_Mejor_Gestion_Historica");
		array_push($Tables,$Queue."_Ultima_Gestion_Historica");
		array_push($Tables,$Queue."_Columnas_Asignacion_CRM");
		/* $TablesImplode = implode(",",$Tables);
		$SqlDropTable = "DROP TABLE ".$TablesImplode;
		$DropTable = $dbDiscador->select($SqlDropTable); */
		foreach($Tables as $Table){
			$SqlDropTable = "DROP TABLE ".$Table;
			$DropTable = $dbDiscador->query($SqlDropTable);
		}
	}
	function getColaDiscador($idCola){
		$db = new DB();
		$SqlCola = "select * from Asterisk_Discador_Cola where id='".$idCola."'";
		$Cola = $db->select($SqlCola);
		$Cola = $Cola[0];
		return $Cola;
	}

	function actualizarCanales($canal, $tlfxrut, $queue){
		$return = array();
		$return["result"] = false;
		
		$db = new DB();

		$update = "UPDATE 
							Asterisk_Discador_Cola 
						SET numero_canales = '" . $canal . "', telfxrut = '" . $tlfxrut . "' 
					WHERE 
						id = (SELECT 
									id_discador 
								FROM 
									Asterisk_All_Queues 
								WHERE Queue = '" . $queue . "')";
		
		$uptd = $db->query($update);

		if($uptd){
			$return["result"] = true;
		}
		return $return;
	}
	function ExisteAsignacionEnCola($idCola){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		$SqlAsignaciones = "select * from asignacion_cola where id_cola='".$idCola."'";
		$Asignaciones = $db->select($SqlAsignaciones);
		if(count($Asignaciones) > 0){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}
	function getTablasEntidades($idCola){
		$db = new DB();
		$Prefijo = "QR_".$_SESSION["cedente"]."_".$idCola."_";
		//$Prefijo = "QR_".$Cedente."_".$idCola."_";
		$SqlTables = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like '".$Prefijo."%'";
		$Tables = $db->select($SqlTables);
		$Entidades = array();
		foreach($Tables as $Table){
			$Array = array();
			$Tabla = "".$Table["TABLE_NAME"]."";
			$ArrayTabla = explode("_",$Tabla);
			$PrefijoTabla = $ArrayTabla[0];
			$Cedente = $ArrayTabla[1];
			$Cola = $ArrayTabla[2];
			$TipoEntidad = $ArrayTabla[3];
			$idEntidad = $ArrayTabla[4];
			$Porcentaje = $ArrayTabla[5];
			$TipoAsignacion = $ArrayTabla[6];
			$Foco = $ArrayTabla[7];
			$Entidad = $TipoEntidad."_".$idEntidad;
			$Array["fileName"] = $this->getEntidadName($Entidad)."_Dial";
			$Array["Table"] = $Table["TABLE_NAME"];
			array_push($Entidades,$Array);
		}
		return $Entidades;
	}
	function getFieldTypes($Table,$Link = "foco"){
		$ToReturn = array();
		$db = new DB($Link);
		$SqlFields = "SELECT DATA_TYPE as Tipo, COLUMN_NAME as Columna FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$Table."' and TABLE_SCHEMA='foco'";
		$Fields = $db->select($SqlFields);
		foreach($Fields as $Key => $Field){
			switch($Field["Tipo"]){
				case "int identity":
					$ToReturn[$Field["Columna"]] = "int";
				break;
				default:
					$ToReturn[$Field["Columna"]] = $Field["Tipo"];
				break;
			}
		}
		return $ToReturn;
	}
	function haveAsignaciones($idCedente,$idCola){
		$ToReturn = false;
		$db = new DB();
		$Prefix = "QR_".$idCedente."_".$idCola;
		$SqlAsignaciones = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 7 and TABLE_NAME like '".$Prefix."%'";
		$Asignaciones = $db->select($SqlAsignaciones);
		if(count($Asignaciones) > 0){
			$ToReturn = true;
		}
		return $ToReturn;
	}

    
	function descargarCompromisos($Cedente,$startDate,$endDate){
		$db = new DB();
		$objPHPExcel = new PHPExcel();
		$fileName = "Reporte Gestion ".date("d_m_Y H_i_s");
		
		$Rows = "";

		$NextSheet = 0;

		
		
		$Columnas = array();
		$Columnas[0][0] = "Rut";
		$Columnas[0][1] = "Rut";
		$Columnas[1][0] = "Nombre";
		$Columnas[1][1] = "Nombre_Completo";
		$Columnas[2][0] = "Ejecutivo";
		$Columnas[2][1] = "nombre_ejecutivo";
		$Columnas[3][0] = "Fecha Gestion";
		$Columnas[3][1] = "fechahora";
		$Columnas[4][0] = "Fecha de Compromiso";
		$Columnas[4][1] = "fec_compromiso";
        $Columnas[5][0] = "Factura";
        $Columnas[5][1] = "numero_factura";
		$Columnas[6][0] = "Monto Compromiso";
		$Columnas[6][1] = "monto_comp";
		$Columnas[7][0] = "Saldo Agregado";
		$Columnas[7][1] = "saldo_agregado";

		$Col = 0;
		foreach($Columnas as $Columna){
			$Titulo = $Columna[0];
			$Rows .= $Titulo.";";
			$Col++;
		}

		//$Rows .= "\r\n";
        /**
         * -- inner join Persona on Persona.Rut = gestion_ult_trimestre.rut_cliente AND Persona.Id_Cedente = 7 
         */
		$Cont = 2;
		$SqlGestiones = "SELECT
                            CONVERT(a.saldo_agregado , UNSIGNED) as saldo_agregado,
                            a.numero_factura,
                            b.nombre_ejecutivo,
                            b.rut_cliente as Rut,
                            (SELECT Nombre_Completo FROM Persona WHERE rut = b.rut_cliente AND Id_Cedente=b.cedente LIMIT 1) AS Nombre_Completo,
                            b.fechahora,
                            b.fec_compromiso,
                            b.monto_comp
                        FROM
                            tbl_gestiones_compromisos a
                            JOIN gestion_ult_trimestre b ON a.id_gestion = b.id_gestion 
						WHERE
							FIND_IN_SET(cedente,(SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Cedente='".$Cedente."')) AND
							fecha_gestion BETWEEN '".$startDate."' AND '".$endDate."'
						ORDER BY
							fechahora ASC";
		$Gestiones = $db->select($SqlGestiones);
		foreach($Gestiones as $Gestion){
			$Rows .= "\r\n";
			$Col = 0;
			foreach($Columnas as $Columna){
				
				$Campo = $Columna[1];
				$Value = utf8_encode($Gestion[$Campo]);
				$Value = str_replace(";","",$Value);//$Value = preg_replace('([^A-Za-z0-9- :._¿?/])', '', $Value);
				$Value = str_replace("\n","",$Value);
				$Value = str_replace("\r","",$Value);
				$Value = str_replace(",","",$Value);
				$Rows .= $Value.";";
				$Col++;

			}
			//$Rows .= "\r\n";
			$Cont++;
		}
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
		header('Cache-Control: max-age=0');
		
		return $Rows;
	}
}
?>
