<?php

include_once __DIR__."/../../includes/functions/Functions.php";
include_once __DIR__.'/../db/DB.php';

class Cedente
{
	public $Id_Cedente;
	private $db; 

	function __construct()
	{
		$this->db = new Db();	
	}
		public function formCedente($cedente, $mandante)
		{
			// $db = new DB();

			echo '<form class="form-horizontal" role="form">';
				echo '<div class="form-group">';
					echo '<label class="control-label col-sm-3">Empresa</label>';
					echo "<select class='selectpicker col-sm-9' data-live-search='true' id='mandanteSeleccionado' name='mandanteSeleccionado'>";
					$query = "SELECT id,nombre FROM mandante WHERE estatus = '1' ORDER BY nombre ASC";
					$mandantes = $this->db->select($query);
					foreach((array) $mandantes as $row){
						$Selected = utf8_encode($row["nombre"]) == utf8_encode($this->getMandanteName($mandante)) ? " selected='selected' " : "";
						echo "<option value='".$row["id"]."' ".$Selected.">"; echo utf8_encode($row["nombre"]); echo "</option>";
					}
					echo "</select>";
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label class="control-label col-sm-3">Proyecto</label>';
					echo "<select class='selectpicker col-sm-9' data-live-search='true' id='cedenteSeleccionado' name='cedenteSeleccionado'>";
					$query = "SELECT DISTINCT 
									c.Id_Cedente AS id, c.Nombre_Cedente AS nombre
								FROM 
									Cedente AS c
									INNER JOIN mandante_cedente AS mc ON (mc.Id_Cedente = c.Id_Cedente) 
									INNER JOIN mandante AS m ON (m.id = mc.Id_Mandante) 
								WHERE 
									m.id = '" . $mandante . "' 
								ORDER BY c.Nombre_Cedente ASC";
					$rows = $this->db->select($query);

					foreach((array) $rows as $row) {
						$Selected = utf8_encode($row["nombre"]) == utf8_encode($this->getCedenteName($cedente)) ? " selected='selected' " : "";
						echo "<option value='".$row["id"]."' ".$Selected.">"; echo utf8_encode($row["nombre"]); echo "</option>";
					}
					echo "</select>";
				echo '</div>';
			echo '</form>';
		}
		function getCedenteName($cedente){
			$ToReturn = "";
			// $db = new DB();
			$cedentes = $this->db->select("SELECT Cedente.Nombre_Cedente as Nombre FROM Cedente WHERE Cedente.Id_Cedente = '".$cedente."'");
			if($cedentes){
				foreach((array) $cedentes as $cedente){
					$ToReturn = $cedente["Nombre"];
				}
			}else{
				$ToReturn = '';
			}
			return $ToReturn;
		}
		function getMandanteName($mandante){
			$ToReturn = "";
			// $db = new DB();
			$mandantes = $this->db->select("SELECT mandante.nombre as Nombre FROM mandante WHERE mandante.id = '".$mandante."'");
			if($mandantes){
				foreach((array) $mandantes as $mandante){
					$ToReturn = $mandante["Nombre"];
				}
			}else{
				$ToReturn = '';
			}
			return $ToReturn;
		}
		function getCedentesMandante($mandante)
		{
			// $db = new DB();
			$cedentes = $this->db->select("SELECT DISTINCT m.id_cedente as idCedente, c.Nombre_Cedente as NombreCedente FROM mandante_cedente as m, Cedente as c WHERE m.id_mandante = '".$mandante."' AND c.id_cedente = m.Id_Cedente");
			//print_r($cedentes);
			return $cedentes;
		}
		
		function getMandantes()
		{
			// $db = new DB();
			$estatus = 1;
			$strSql = "SELECT id, nombre FROM mandante WHERE estatus = '$estatus'";
			/*
			if (isset($_SESSION['mandante']) && intval($_SESSION['mandante']) > 0) {
				$idMandante = (int) $_SESSION['mandante'];
				$strSql .= " AND id = '".$idMandante."'";
			}
			*/
			$strSql .= ' ORDER BY nombre';
			$mandantes = $this->db->select($strSql);
			return $mandantes;
		}

		function getCedentes()
		{
			// $db = new DB();
			$CedentesArray = array();
			$Sql = "select * from Cedente";
			$cedentes = $this->db->select($Sql);
			foreach((array) $cedentes as $cedente){
				$Array = array();
				$Array['nombre'] = utf8_encode($cedente["Nombre_Cedente"]);
				$Array['Actions'] = $cedente["Id_Cedente"];
				array_push($CedentesArray,$Array);
			}
			return $CedentesArray;  
		}

		function creaCedente($datos)
		{
			// $db = new DB();
			$rsIdCedente = $this->db->select('SELECT MAX(id) +1 AS ID FROM Cedente LIMIT 1');
			if($rsIdCedente) $IdCedente = (int) $rsIdCedente [0]['ID'];

			$SqlInsertCedente = "INSERT INTO Cedente (Nombre_Cedente, Fecha_Ingreso, tipo, planDiscado, IPDiscador, DialPlan, Id_Cedente) VALUES ('".$datos['nombreCedente']."', DATE_FORMAT('".$datos['fechaIngreso']."','%Y-%m-%d'), '".$datos['discado']."', '".$datos['plan']."', '".$datos['ipdiscador']."', '".$datos['dialplan']."', {$IdCedente})";
			$InsertCedente = $this->db->query($SqlInsertCedente); 
			$SqlSelectCedente = "SELECT Id_Cedente FROM Cedente WHERE Nombre_Cedente = '".$datos['nombreCedente']."'";
			$Cedente = $this->db->select($SqlSelectCedente);
			if($Cedente) {
				$Id_Cedente = (int) $Cedente[0]['Id_Cedente'];
				$SqlInsertCedenteMandante = "INSERT INTO mandante_cedente (Id_Cedente, Id_Mandante,Lista_Vicidial) VALUES ('".$Id_Cedente."', '".$datos['idMandante']."','".$Id_Cedente."')";               
				$InsertCedente = $this->db->query($SqlInsertCedenteMandante); 
			} else {
				$Id_Cedente = 0;
			}
			return $Id_Cedente;     
		}

		function creaMandante($datos){
			// $db = new DB();
			$SqlInsert = "insert into mandante (nombre, Empieza) values('".$datos['nombre']."', '".$datos['evaluar']."')";               
			$Insert = $this->db->insert($SqlInsert);   
			return $Insert;     
		}

		public function eliminaCedente($idCedente)
		{
			// $db = new DB();
			$SqlEliminarCedente = "delete from Cedente where Id_Cedente = '".$idCedente."'";
			$this->db->query($SqlEliminarCedente); 
			$SqlEliminaMandanteCedente = "Delete from mandante_cedente where Id_Cedente = '".$idCedente."'";
			$this->db->query($SqlEliminaMandanteCedente);        
		}

		public function eliminaMandante($idMandante)
		{
			// $db = new DB();
			$estatus = 0;
			$SqlUpdate = "UPDATE mandante set estatus = '".$estatus."' WHERE id='".$idMandante."'";
			$this->db->query($SqlUpdate);      
		}

		public function modificarMandante($datos)
		{
			// $db = new DB();
			$SqlUpdate = "UPDATE mandante set nombre = '".$datos['nombre']."', Empieza = '".$datos['evaluar']."' WHERE id='".$datos['id']."' ";
			$this->db->query($SqlUpdate);
		} 

		public function modificarCedente($datos){ // tipo, planDiscado
			// $db = new DB();
			if(isset($datos['planDiscado'])) {
				$planDiscado = 0;
			} else {
				$planDiscado = 1;
			}
			if(isset($datos['posee_speech'])){
				$posee_speech = 1;
			}else{
				$posee_speech = 0;
			}
			if(isset($datos['omnicanalidad'])){
				$omnicanalidad = 1;
			}else{
				$omnicanalidad = 0;
			}
			if(isset($datos['compromiso'])){
				$compromiso = 1;
			}else{
				$compromiso = 0;
			}
			if(isset($datos['agendamiento'])){
				$agendamiento = 1;
			}else{
				$agendamiento = 0;
			}
			if(isset($datos['facturas'])){
				$facturas = 1;
			}else{
				$facturas = 0;
			}
			if(isset($datos['posee_scoring'])){
				$posee_scoring = 1;
			}else{
				$posee_scoring = 0;
			}
			if(isset($datos['carga_personalizada'])){
				$carga_personalizada = 1;
			}else{
				$carga_personalizada = 0;
			}
			if(isset($datos['agendamiento_obligatorio'])){
				$agendamiento_obligatorio = 1;
			}else{
				$agendamiento_obligatorio = 0;
			}
			$SqlUpdate = "UPDATE Cedente 
							SET 
								Nombre_Cedente = '".$datos['nombreCedente']."', 
								Fecha_Ingreso = '".$datos['fechaIngreso']."', 
								tipo = '".$datos['TipoOperacion']."', 
								planDiscado = '".$planDiscado."', 
								IPDiscador = '".$datos["IPDiscador"]."', 
								DialPlan = '".$datos["DialPlan"]."', 
								posee_speech = '".$posee_speech."',
								tipo_refresco = '".$datos["tipo_refresco"]."', 
								omnicanalidad = '".$omnicanalidad."', 
								compromiso = '".$compromiso."', 
								agendamiento = '".$agendamiento."',
								facturas = '".$facturas."',
								posee_scoring = '".$posee_scoring."',
								carga_personalizada = '".$carga_personalizada."',
								algoritmo_discado = '".$datos["algoritmo_discado"]."',
								agendamiento_obligatorio = '".$agendamiento_obligatorio."'
							WHERE 
								Id_Cedente = '".$datos['id']."'";
			$this->db->query($SqlUpdate);
			echo json_encode($datos);
		} 
		public function mostrarMandante($idMandante){
			// $db = new DB();
			$mandante = $this->db->select("SELECT * FROM mandante WHERE id = '".$idMandante."'");
			return $mandante;	
		} 
		public function mostrarCedente($idCedente){
			// $db = new DB();
			$cedente = $this->db->select("SELECT * FROM Cedente WHERE Id_Cedente = '".$idCedente."'");
			return $cedente;	
		}
		public function getMandanteFromCedente($Cedente){
			// $db = new DB();
			$SqlMandante = "SELECT mandante.* FROM mandante INNER JOIN mandante_cedente ON mandante_cedente.Id_Mandante = mandante.id WHERE mandante_cedente.Id_Cedente='".$Cedente."'";
			$Mandante = $this->db->select($SqlMandante);
			$Mandante = $Mandante[0];
			return $Mandante;
		}
		function getTramos(){
			$ToReturn = array();
			// $db = new DB();
			$SqlTramos = "SELECT * FROM tramos_cedentes WHERE Id_Cedente='".$_SESSION["cedente"]."' order by desde";
			$Tramos = $this->db->select($SqlTramos);
			foreach((array) $Tramos as $Tramo){
				$ArrayTmp = array();
				$ArrayTmp["Descripcion"] = utf8_encode($Tramo["Descripcion"]);
				$ArrayTmp["Desde"] = $Tramo["desde"];
				$ArrayTmp["Hasta"] = $Tramo["operacion"] == "0" ? $Tramo["hasta"] : "";
				$ArrayTmp["Operacion"] = "";
				switch($Tramo["operacion"]){
					case "0":
						$ArrayTmp["Operacion"] = "Rango";
					break;
					case "1":
						$ArrayTmp["Operacion"] = "Menor o igual";
					break;
					case "2":
						$ArrayTmp["Operacion"] = "Mayor o igual";
					break;
				}
				$ArrayTmp["Accion"] = $Tramo["id"];
				array_push($ToReturn,$ArrayTmp);
			}
			return $ToReturn;
		}
		function saveTramo($Tramo,$Operacion,$Desde,$Hasta){
			$ToReturn = array();
			$ToReturn["result"] = false;
			// $db = new DB();
			$SqlInsert = "insert into tramos_cedentes (Descripcion,desde,hasta,operacion,Id_Cedente) values ('".$Tramo."','".$Desde."','".$Hasta."','".$Operacion."','".$_SESSION["cedente"]."')";
			$Insert = $this->db->query($SqlInsert);
			if($Insert){
				$ToReturn["result"] = true;
			}
			return $ToReturn;
		}
		function deleteTramo($ID){
			$ToReturn = array();
			$ToReturn["result"] = false;
			// $db = new DB();
			$SqlDelete = "delete from tramos_cedentes where id='".$ID."' and Id_Cedente='".$_SESSION["cedente"]."'";
			$Delete = $this->db->query($SqlDelete);
			if($Delete){
				$ToReturn["result"] = true;
			}
			return $ToReturn;
		}
		function getRutSearcherData($Rut,$WithData=true){
			$ToReturn = array();
			$ToReturn["Persona"] = $this->getPersonalData($Rut,$WithData);
			$ToReturn["Deuda"] = $this->getDeudasData($Rut,$WithData);
			$ToReturn["Direcciones"] = $this->getDireccionesData($Rut,$WithData);
			$ToReturn["Mail"] = $this->getCorreosData($Rut,$WithData);
			$ToReturn["fono_cob"] = $this->getTelefonosData($Rut,$WithData);
			$ToReturn["Gestion"] = $this->getGestionesData($Rut,$WithData);
			return $ToReturn;
		}
		function getPersonalData($Rut,$WithData){
			$ToReturn = array();
			// $db = new DB();
			if($WithData){
				$SqlPersona = "select * from Persona where Rut='".$Rut."' and FIND_IN_SET('".$_SESSION["cedente"]."',Id_Cedente)";
				$Persona = $this->db->select($SqlPersona);
				$ToReturn["Data"] = $Persona;
			}else{
				$ToReturn["Data"] = array();
			}
			$ToReturn["Campos"] = $this->getCamposCarga("Persona");
			return $ToReturn;
		}
		function getDeudasData($Rut,$WithData){
			$ToReturn = array();
			// $db = new DB();
			if($WithData){
				$SqlDeudas = "select * from Deuda where Rut='".$Rut."' and Id_Cedente='".$_SESSION["cedente"]."'";
				$Deudas = $this->db->select($SqlDeudas);
				$ToReturn["Data"] = $Deudas;
			}else{
				$ToReturn["Data"] = array();
			}
			$ToReturn["Campos"] = $this->getCamposCarga("Deuda");
			return $ToReturn;
		}
		function getDireccionesData($Rut,$WithData){
			$ToReturn = array();
			// $db = new DB();
			if($WithData){
				$SqlDirecciones = "select * from Direcciones_cedente where Rut='".$Rut."' and Id_Cedente='".$_SESSION["cedente"]."'";
				$Direcciones = $this->db->select($SqlDirecciones);
				$ToReturn["Data"] = $Direcciones;
			}else{
				$ToReturn["Data"] = array();
			}
			$ToReturn["Campos"] = $this->getCamposCarga("Direcciones");
			return $ToReturn;
		}
		function getCorreosData($Rut,$WithData){
			$ToReturn = array();
			// $db = new DB();
			if($WithData){
				$SqlCorreos = "select * from Mail_cedente where Rut='".$Rut."' and Id_Cedente='".$_SESSION["cedente"]."' and correo_electronico <> ''";
				$Correos = $this->db->select($SqlCorreos);
				$ToReturn["Data"] = $Correos;
			}else{
				$ToReturn["Data"] = array();
			}
			$ToReturn["Campos"] = $this->getCamposCarga("Mail");
			return $ToReturn;
		}
		function getTelefonosData($Rut,$WithData){
			$ToReturn = array();
			// $db = new DB();
			if($WithData){
				$SqlTelefonos = "select * from fono_cob_cedente where Rut='".$Rut."' and Id_Cedente='".$_SESSION["cedente"]."'";
				$Telefonos = $this->db->select($SqlTelefonos);
				$ToReturn["Data"] = $Telefonos;
			}else{
				$ToReturn["Data"] = array();
			}
			$ToReturn["Campos"] = $this->getCamposCarga("fono_cob");
			return $ToReturn;
		}
		function getGestionesData($Rut,$WithData){
			$ToReturn = array();
			// $db = new DB();
			if($WithData){
				$SqlGestiones = "select gestion_ult_trimestre.*,Tipo_Contacto.Nombre as TipoGestion from gestion_ult_trimestre  inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where rut_cliente='".$Rut."' and cedente='".$_SESSION["cedente"]."' order by fechahora DESC";
				$Gestiones = $this->db->select($SqlGestiones);
				$ToReturn["Data"] = $Gestiones;
			}else{
				$ToReturn["Data"] = array();
			}
			
			$ToReturn["Campos"] = array();
			$CamposExplode = explode(",","fono_discado,fechahora,nombre_ejecutivo,TipoGestion,observacion");
			foreach($CamposExplode as $Value){
				$ArrayTmp = array();
				$ArrayTmp["data"] = $Value;
				array_push($ToReturn["Campos"],$ArrayTmp);
			}
			return $ToReturn;
		}
		function getCamposCarga($Tabla){
            $ToReturn = array();
            // $db = new DB();
            $SqlCampos = "select campos from campos_cargas_asignaciones where tabla='".$Tabla."' and Id_Cedente='".$_SESSION["cedente"]."'";
            $Campos = $this->db->select($SqlCampos);
            foreach((array) $Campos as $Campo){
				$CamposExplode = explode(",",$Campo["campos"]);
				foreach($CamposExplode as $Value){
					$ArrayTmp = array();
					$ArrayTmp["data"] = $Value;
					array_push($ToReturn,$ArrayTmp);
				}
			}
            return $ToReturn;
        }
	}
?>
