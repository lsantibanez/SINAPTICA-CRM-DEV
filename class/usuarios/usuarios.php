<?php
require_once __DIR__.'/../db/DB.php';
require_once(__DIR__.'/../logs.php');
include_once(__DIR__.'/../../includes/functions/Functions.php');
require_once(__DIR__.'/hash.php');
include_once(__DIR__.'/../../vendor/autoload.php');
// include_once(__DIR__.'/../db/DB.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

class Usuarios
{
	private $db;
	private $logs;
	//private $urlApiDiscador = 'https://fireapidiscador.sinaptica.io/api/';
	private $urlApiDiscador; //'https://10.10.30.11/fireapi/api/';
	//private $urlApiDiscador = 'http://127.0.0.1:9201/api/';
	private $httpClient = null;

	public function __construct()
	{
		$this->urlApiDiscador = $_ENV['API_URL'];
		$this->db = new Db();
		$this->logs = new Logs();
		$this->httpClient = new GuzzleHttp\Client([
			'base_uri' => $this->urlApiDiscador,
			'timeout'  => 5.0,
			'verify' => false,
		]);
	}

	public function crearUsuarios($usuario,$nombre,$password,$nivel,$cargo,$idMandante,$email,$idCedente,$idTrabajador,$idEmpresa)
	{
		// $db = new Db();.
		// Valido si el usuario existe antes de registrarlo
		if ($this->validarUsuario($usuario,""))
			$array = array('respuesta' => "1");
		else
		{
			$this->db->query("INSERT INTO Usuarios(usuario,nombre,clave,nivel,cargo,Id_Cedente,email,mandante,Id_Personal,idEmpresaExterna) VALUES('$usuario','$nombre','$password','$nivel','$cargo','$idCedente','$email','$idMandante','$idTrabajador','$idEmpresa')");
			if ($idTrabajador > 0){
				$row = $this->db->select("SELECT id FROM Usuarios WHERE Id_Personal = '".$idTrabajador."'");
				$row = $row[0];
				$idNuevoUsuario = $row['id'];
				$this->db->query("UPDATE Personal SET id_usuario='$idNuevoUsuario', Nombre_Usuario='$usuario' Where Id_Personal='$idTrabajador'");
			}
			$array = array('respuesta' => "2");
		}
		echo json_encode($array);
	}

	public function validarUsuario($usuario,$id)
	{
		// $db = new Db();.
		$isValid = false;
		if ($id == "") {
			$row = $this->db->select("SELECT * FROM Usuarios WHERE usuario='$usuario'");
		}	else {
			$row = $this->db->select("SELECT * FROM Usuarios WHERE usuario='$usuario' AND id!='$id'");
		}

		if(count((array) $row) > 0){
			$isValid = true;
		}

		return $isValid;
	}

		public function validarUsuarioPorSi($usuario)
		{
			// $db = new Db();.
			$isValid = false;
			$row = $this->db->select("SELECT * FROM Usuarios WHERE usuario='$usuario'");
			if(count((array) $row) > 0){
				$isValid = true;
			}
			return $isValid;
		}

		public function modificarUsuario($usuario,$password,$nivel,$cargo,$idCedente,$email,$id,$idMandanteUsu,$modificoPassword){
			// $db = new Db();.
			// Valido si el usuario existe antes de modificarlo
			if($this->validarUsuario($usuario,$id)){
				$array = array('respuesta' => "1");
			}else{
				if($modificoPassword){
					$query = "UPDATE Usuarios SET usuario = '".$usuario."', clave = '".$password."', nivel = '".$nivel."', Id_Cedente = '".$idCedente."', mandante = '".$idMandanteUsu."' WHERE id = '".$id."'";
				}else {
					$query = "UPDATE Usuarios SET usuario = '".$usuario."', nivel = '".$nivel."', Id_Cedente = '".$idCedente."', mandante = '".$idMandanteUsu."' WHERE id = '".$id."'";
				}
				$this->db->query($query);
				$SqlUsuario = "SELECT id as idUsuario, anexo_foco as anexo from Usuarios where usuario='".$usuario."'";
				$Usuario = $this->db->select($SqlUsuario);
				$Usuario = $Usuario[0];
				$ToReturn = array();
				$ToReturn["respuesta"] = "2";
				$ToReturn["Usuario"] = $Usuario;
			}
			echo json_encode($ToReturn);
		}

		// Verifico sip el usuario cambio la contraseña
		public function validarClave($clave)
		{
			$isValid = false; // no la cambio
			if ($clave != '*.8++')
				$isValid = true; // si la cambio

			return $isValid;
		}


		public function eliminarUsuarios($idUsuario)
		{
			// $db = new Db();.
			$this->db->query("DELETE FROM Usuarios WHERE id = '$idUsuario'");
			$this->db->query("UPDATE Personal SET id_usuario='0' Where id_usuario='$idUsuario'");
			}

		public function listarUsuarios($nivel, $mandante = '')
		{
			// $db = new Db();. 
			$visible = "";
			$strSql = 'SELECT * FROM Usuarios';
			//if (!empty($mandante) && intval($mandante) > 0) $strSql .= " WHERE find_in_set('".$mandante."',mandante)  OR ((mandante = '' OR mandante IS NULL) AND nivel = 2) AND tipo_usuario_sac != 2 ";
			if (!empty($mandante) && intval($mandante) > 0) $strSql .= " WHERE tipo_usuario_sac != 2 ";
			$strSql .= ' ORDER BY nombre DESC;';
			$Usuarios = $this->db->select($strSql);
			if($Usuarios)	{
					echo '<table id="TablaUsuarios" class="table table-striped" cellspacing="0" width="100%">';
					echo '<thead>';
					echo '<tr>';
					echo '<th style="width: 10%;">Usuario</th>';
					echo '<th>Nombre</th>';
					echo '<th style="width: 15%; text-align: center;">Empresas</th>';
					echo '<th style="width: 10%; text-align: center;">Nivel</th>';
					echo '<th style="width: 10%; text-align: center;"">Extensión</th>';
					echo '<th style="text-align: center; width: 10%;">Acciones</th>';
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach((array) $Usuarios as $Usuario) {
						$Extension = (!empty($Usuario['user_dial']) && !is_null($Usuario['user_dial']))? $Usuario['user_dial'] : '--';
						echo '<tr id="'.$Usuario['id'].'">';
						echo '<td style="vertical-align: middle;">'.$Usuario['usuario'].'</td>';
						echo '<td style="vertical-align: middle;">'.$Usuario['nombre'].'</td>';
						echo '<td style="vertical-align: middle; text-align: center">'.$this->mostrarEmpresas(explode(',',$Usuario['mandante'])).'</td>';
						echo '<td style="vertical-align: middle; text-align: center">'.$this->mostrarNombreRol($Usuario['nivel'])."</td>";
						echo '<td style="vertical-align: middle; text-align: center;"">'.$Extension."</td>";
						echo '<td style="vertical-align: middle; text-align: center;"><button style="margin: 0 5px" type="button" class="fa fa-pen btn gestionar_usu btn-success btn-icon icon-lg" '.$visible.' data-toggle="modal" id="'.$Usuario['id'].'" title="Modificar"></button><button type="button" class="btn eliminar fa fa-trash btn-danger btn-icon icon-lg" '.$visible.' data-toggle="modal" id="'.$Usuario['id'].'" title="Eliminar"></button></td>';
						echo '</tr>';
					}
				echo '</tbody>';
				echo '</table>';
			} else {
				echo "No hay Usuarios creados en la BD";
			}
		}

		public function datosUsuario($idUsuario)
		{
			// $db = new Db();.
			$usuarios = array();
			$row = $this->db->select("SELECT * FROM Usuarios WHERE id = ".intVal($idUsuario));
			if(count((array) $row) > 0){
				$row = $row[0];
				// busco el nombre del Rol
				$nivel = $row['nivel'];
				$row2 = $this->db->select("SELECT * FROM Roles WHERE id = '".$nivel."'");
				$row2 = $row2[0];

				$idTrabajador = (int) $row['Id_Personal'];
				$idEmpresa = $row['idEmpresaExterna'];
				$cargoTelefono = "";
				$email = $row['email'];
				$nombre = $row['nombre'];
				$tipoUsuario = ""; 
				if (($idTrabajador > 0)){
					$row3 = $this->db->select("SELECT nombre, id_cargo, email FROM Personal WHERE Id_Personal = '".$idTrabajador."'");
					$row3 = $row3[0];
					$idCargo = $row3['id_cargo'];
					$email = $row3['email'];
					$nombre = $row3['nombre'];
					// busco el nombre del cargo del trabajador
					$row4 = $this->db->select("SELECT cargo FROM RH_cargo WHERE id_cargo = '".$idCargo."'");
					if ($row4) {
						$row4 = $row4[0];
						$cargoTelefono = $row4['cargo'];
					}
					$tipoUsuario = "Trabajador";
				}else{
					if (($idEmpresa > 0)){
					$row3 = $this->db->select("SELECT telefono, email, nombre FROM EE_empresa_externa WHERE idEmpresaExterna = '".$idEmpresa."'");
					$row3 = $row3[0];
					$cargoTelefono = $row3['telefono'];
					$email = $row3['email'];
					$nombre = $row3['nombre']; 
					$tipoUsuario = "Empresa";
					}  
				}

				$userProyectos = [];
				if (!empty($row['Id_Cedente']) && !is_null($row['Id_Cedente'])) {
					$items = explode(',', trim($row['Id_Cedente']));
					if (count($items) > 0) {
						$userProyectos = array_map(function($i) {
							return (int) $i;
						}, $items);
					}
				}
				/*
				$userProyectos = (array) $this->db->select('SELECT Id_Cedente AS proyecto FROM Usuarios_Cedentes WHERE Id_Usuario = '.intVal($idUsuario));
				if (count((array) $userProyectos) > 0) $userProyectos = array_map(function ($item) {
					return (int) $item['proyecto'];
				},$userProyectos);
				*/

				$usuarios = [
					'haveUsuario'=>true, 
					'usuario'=> $row['usuario'], 
					'nombre_usuario'=> $row['nombre'], 
					'clave'=> $row['clave'], 
					'nivel'=> $row['nivel'],
					'cargo'=> $row['cargo'], 
					'id_cedente'=> $row['Id_Cedente'], 
					'email'=> $row['email'], 
					'id_mandante'=> $row['mandante'],
					'user_dial'=> $row['user_dial'], 
					'nombreNivel'=> $row2['nombre'], 
					'pass_dial'=> $row['pass_dial'],
					'cargoTelefono'=> $cargoTelefono, 
					'email'=> $email, 
					'nombre'=> $nombre, 
					'tipoUsuario'=> $tipoUsuario, 
					'anexo_foco'=> $row['anexo_foco'],
					'proyectos' => $userProyectos,
				];
			}else{
				$usuarios = array('haveUsuario'=>false);
			}
			return $usuarios;
		}

		public function mostrarNombreRol($nivel)
		{
			// $db = new Db();.
			$row = $this->db->select("SELECT nombre FROM Roles WHERE id = $nivel");
			if ($row) {
				$row = $row[0];
				return $row['nombre'];
			}
			return '';
		}

		public function mostrarEmpresas($empresas)
		{
			// $db = new Db();.
			$row = $this->db->select("SELECT nombre FROM mandante WHERE id IN(".implode(',',$empresas).")");
			$row = array_map(function ($item) {
				return $item['nombre'];
			}, (array) $row);
			return implode(', ',$row);
		}

		function getUserIdByUsername($Username){
			$ToReturn = "";
			// $db = new Db();.
			$SqlUserID = "select * from Usuarios where usuario='".$Username."'";
			$UserID = $this->db->select($SqlUserID);
			if(count((array) $UserID) > 0){
				$ToReturn = $UserID[0]["id"];
			}
			return $ToReturn;
		}
		function updateExtensionFoco($idUsuario,$Extension){
			$ToReturn = array();
			$ToReturn["result"] = false;
			// $db = new Db();.
			$SqlUpdate = "update Usuarios set anexo_foco='".$Extension."' where id='".$idUsuario."'";
			$Update = $this->db->query($SqlUpdate);
			if($Update){
				$ToReturn["result"] = true;
			}
			return $ToReturn;
		}

	public function saveData($data)
	{
		$success = true;
		$message = 'Operación realizada con éxito.';

		try {
			if ($data['accion'] === 'update' && intval($data['id_usuario']) > 0) {
				$apiRequest = [
					'name' => $data['campos']['nombre'],
				];
				$data['campos']['user_dial'] = (isset($data['campos']['extension']) && !empty($data['campos']['extension']))? $data['campos']['extension']:'';
				$data['campos']['Id_Cedente'] = (isset($data['campos']['proyectos']) && !empty($data['campos']['proyectos']))? $data['campos']['proyectos']: '';
				$data['campos']['mandante'] = (isset($data['campos']['empresas']) && !empty($data['campos']['empresas']))?$data['campos']['empresas']:'';
				$data['campos']['anexo_foco'] = (!empty($data['campos']['extension']))? $data['campos']['extension']:'0';
				$data['campos']['nivel'] = $data['campos']['rol'];
				unset(
					$data['campos']['proyectos'],
					$data['campos']['empresas'],
					$data['campos']['rol'],
					$data['campos']['extension'],
					$data['campos']['clave']
				);

				if (!empty($data['campos']['password'])) {
					$objetoHash = new Hash();
					$textPassword = $data['campos']['password'];
					$data['campos']['clave'] = $objetoHash->convertirHash($textPassword);
					$apiRequest['password'] = $textPassword;
				}					
				unset($data['campos']['password']);

				$strSql = 'UPDATE Usuarios SET ';
				foreach ($data['campos'] as $campo => $valor) {
					if (in_array($campo, ['mandante','Id_Cedente'])) {
						if (is_array($valor)) $valor = implode(',', $valor);
						$strSql .= $campo." = '".$valor."', ";
					} else {
						$strSql .= $campo." = '".$valor."', ";
					}
				}
				$strSql .= 'Id_personal = 0 WHERE id = '.intval($data['id_usuario']);
				$this->db->query($strSql);

				if ((int) $data['campos']['Id_Cedente'] > 0) $apiRequest['cedente'] = (int) $data['campos']['Id_Cedente'];
				$result = $this->__apiDiscadorUpdateAgent([
					'usuario' => $data['campos']['usuario'],
					'uid' => (int) $data['id_usuario'],
					'campos' => $apiRequest
				]);
				$this->logs->debug($result);

				if (isset($result['item']) && !is_null($result['item']['extension'])) {
					if ((empty($data['campos']['anexo_foco']) || is_null($data['campos']['anexo_foco']))) {
						if ($result['item']['login'] == $data['campos']['usuario'] && !empty($result['item']['extension']) && !is_null($result['item']['extension'])) {
							$nExtension = $result['item']['extension'];
							$this->db->query("UPDATE Usuarios SET user_dial = '{$nExtension}', pass_dial = '{$nExtension}', anexo_foco = '{$nExtension}' WHERE id = ".intval($data['id_usuario'])." LIMIT 1");
						}
					}
				}
			} else if ($data['accion'] === 'create') {
				$message = '';
				$existe = false;
				$data['campos']['usuario'] = preg_replace('/[^a-z0-9\-\_]/i', '', $data['campos']['usuario']);
				$usuario = $data['campos']['usuario'];
				$registro = $this->db->select("SELECT id, user_dial, pass_dial, usuario, Id_Cedente, mandante, anexo_foco FROM Usuarios WHERE usuario = '{$usuario}' LIMIT 1");
				
				if ($registro) {
					$existe = true;
					if ((!empty($registro[0]['user_dial']) || !is_null($registro[0]['user_dial'])) && (!empty($registro[0]['anexo_foco']) || !is_null($registro[0]['anexo_foco']))) {
						return [
							'success' => false,
							'message' => 'Ya se encuentran registrados los datos en el sistema.'
						];
					}
				} 
				$objetoHash = new Hash();
				$textPassword = $data['campos']['password'];
				$data['campos']['clave'] = $objetoHash->convertirHash($data['campos']['password']);
				$data['campos']['nivel'] = $data['campos']['rol'];

				if ((int) $data['campos']['rol'] === 3) {
					$data['campos']['Id_Cedente'] = (isset($data['campos']['proyectos']) && !empty($data['campos']['proyectos']))? implode(',',$data['campos']['proyectos']): '';
					$data['campos']['mandante'] = (isset($data['campos']['empresas']) && !empty($data['campos']['empresas']))? implode(',',$data['campos']['empresas']):'';
					$data['campos']['anexo_foco'] = (!empty($data['campos']['extension']))? $data['campos']['extension']:'0';
				}
				unset($data['campos']['password'], $data['campos']['rol'], $data['campos']['proyectos'], $data['campos']['empresas']);
				

				$strSql = 'INSERT INTO Usuarios ('.implode(', ',array_keys($data['campos'])).') VALUES(';
				$strSql .= "'".implode("', '", array_values($data['campos']))."')";
				if (!$existe) {
					$insertado = $this->db->insert($strSql);
					$message = 'Usuario creado con éxito.';
				}

				if ((int) $data['campos']['nivel'] === 3) {
					$userId = ($existe)? (int) $registro[0]['id']: (int) $insertado;
					$apiData = [
						'usuario'  =>  $usuario,
						'password' =>  $textPassword, //$data['campos']['clave'],
						'nombre'   =>  $data['campos']['nombre'],
						'empresa'  =>  (int) $data['campos']['mandante'],
						'cedente'  =>  (int) $data['campos']['Id_Cedente'],
						'uid'      =>  $userId,
						'email'    =>  $data['campos']['usuario']
					];
					$respuestaApi = $this->__apiDiscadorNewAgent($apiData);
					$this->logs->debug($respuestaApi);				
					if ($respuestaApi['status'] !== 200) {
						$success = false;
						$message = 'Discador: La extensión no pudo ser configurada, creación incompleta.';
					}
						
					if ((bool) $respuestaApi['success'] !== true) {
						if ($existe && (empty($registro[0]['user_dial']) || is_null($registro[0]['user_dial']))) {
							if ($respuestaApi['item']['login'] == $usuario && !empty($respuestaApi['item']['extension']) && !is_null($respuestaApi['item']['extension'])) {
								$nExtension = $respuestaApi['item']['extension'];
								$this->db->query("UPDATE Usuarios SET user_dial = '{$nExtension}', pass_dial = '{$nExtension}', anexo_foco = '{$nExtension}' WHERE id = {$userId} LIMIT 1");
							}
						}
						$success = false;
						$message = 'Discador: '.$respuestaApi['message'];
					} else {
						$nExtension = $respuestaApi['item']['extension'];
						$this->db->query("UPDATE Usuarios SET user_dial = '{$nExtension}', pass_dial = '{$nExtension}', anexo_foco = '{$nExtension}' WHERE id = {$userId} LIMIT 1");
						$message .= ' Discador: extensión *'.$nExtension.'* creada con éxito.';
					}
				}
			}				
		} catch (\Exception $ex) {
			$this->logs->error(' [Usuarios_Class_saveData] '.$ex->getMessage());
			$message = 'Se ha presentado un error';
			$success = false;
		}

		return [
			'success' => $success,
			'message' => $message
		];
	}

		private function __apiDiscadorNewAgent($requestData)
		{
			$message = '...';
			$body = '';
			$status = 200;

			try {
				$this->__updateUrl();
        $res = $this->httpClient->request('POST', 'agents/create', [
          'json' => $requestData,
        ]);
  
        $status = $res->getStatusCode();
        $body = $res->getBody()->getContents();
        $message = 'Operación realizada con éxito.';
				if (is_string($body)) $body = json_decode($body, true);
				return $body;

      } catch(GuzzleHttp\Exception\RequestException $ex) {
        $status = 500; //$ex->getResponse()->getStatusCode();
        $message = $ex->getmessage();
      } catch (GuzzleHttp\Exception\ConnectException $ex) {
        $status = 500;// $ex->getStatusCode();
        $message = $ex->getmessage();
      }
			
			return [
				'status' => $status,
				'success' => false,
				'message' => $message,
			];
		}

		private function __apiDiscadorUpdateAgent($requestData)
		{
			$message = '...';
			$body = '';
			$status = 200;

			try {
				$this->__updateUrl();
        $res = $this->httpClient->request('POST', 'agents/update', [
          'json' => $requestData,
        ]);
  
        $status = $res->getStatusCode();
        $body = $res->getBody()->getContents();
        $message = 'Operación realizada con éxito.';
				if (is_string($body)) $body = json_decode($body, true);
				if (isset($body['item'])) {
					return [
						'status' => 200,
						'success' => true,
						'message' => 'Extensión creada en el discador',
						'item' => $body['item'],
					];
				}
				//return $body;
      } catch(GuzzleHttp\Exception\RequestException $ex) {
        $status = 500; //$ex->getResponse()->getStatusCode();
        $message = $ex->getmessage();
      } catch (GuzzleHttp\Exception\ConnectException $ex) {
        $status = 500;// $ex->getStatusCode();
        $message = $ex->getmessage();
      }
			
			return [
				'status' => $status,
				'success' => true,
				'message' => $message,
			];
		}

		private function __updateUrl()
		{
			$this->urlApiDiscador = $_ENV['API_URL'];
			$this->logs->debug($this->urlApiDiscador);
		}
	}
?>