<?php

require __DIR__.'/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

class Session
{
	private $loginUsername;
	private $MM_authorizedUsers;
	private $MM_donotCheckaccess;
	private $loginStrGroup;
	private $idUsuLogin;
	private $nombreUsu;
	private $emailUsu;
	private $cargoUsu;
	private $sexo;
	private $anexo_foco;
	private $inbound;
	private $multiServicio;
	private $empresas = [];
	private $proyectos = [];
	private $db;

	public function __construct($MM_authorizedUsers,$MM_donotCheckaccess)
	{ // listo
		if(!isset($_SESSION))	session_start();
		$this->db = new DB();
		$this->MM_authorizedUsers = $MM_authorizedUsers;  // se llena distinto en cada pagina
		$this->MM_donotCheckaccess = $MM_donotCheckaccess;
	}

			// La session solo la destruyo cuando el usuario se desloguea
	public function destruirSession()
	{
		if(isset($_SESSION)) session_destroy();
	}

	public function crearVariableSession($vector)
	{
		foreach ((array) $vector as $clave => $valor) {
			$_SESSION[$clave] = $valor;
		}
	}

	function checkLogin($userName,$password,$nivel = "")
	{
		$ToReturn = [];
		$ToReturn["result"] = false;
		// $db = $this->db;
		$WhereNivel = $nivel == "" ? "" : " nivel = '".$nivel."' AND";
		$SqlUsuarios = sprintf("SELECT * FROM Usuarios WHERE ".$WhereNivel." usuario = %s", $this->db->GetSQLValueString($userName, "text"));
		$Usuarios = $this->db->select($SqlUsuarios);
		if(count((array) $Usuarios) > 0){
			$Usuarios = $Usuarios[0];
			$objetoHash = new Hash();			
			if($objetoHash->verificarHash($password, $Usuarios['clave'])){
				$ToReturn["result"] = true;
				$_SESSION["Autenticated"] = $Usuarios["id"];
			} else {
				unset($_SESSION["Autenticated"]);
			}
		}
		return $ToReturn;
	}

	public function login($loginUsername, $password)
	{
		// $db = $this->db;
		$password = substr($password, 0, 20);
		$loginUsername = preg_replace('/[^\p{L}\p{N}]+/', '', $loginUsername);
		$this->loginUsername = substr($loginUsername, 0, 15);
		$MM_fldUserAuthorization = "nivel";
		$MM_redirectLoginSuccessNew = "servicio";
		$redirecionaSuite = "suite/suite.php"; // admin // $MM_redirectLoginSuccess = "cedente.php"; // admin //
		$MM_redirectLoginSuccess = "dashboard/dashboard"; // admin // $MM_redirectLoginSuccess = "cedente.php"; // admin //
		$MM_redirectLoginSuccess2 = "proyecto.php"; // supervisor
		//$MM_redirectLoginSuccess3 = "index.php";
		$MM_redirectLoginSuccess3 = "cedente";
		$MM_redirectLoginSuccess4 = "crm/index"; // ejecutivos
		$MM_redirectLoginSuccess5 = "proyecto"; // 6 Calidad
		$MM_redirectLoginFailed = "index?id=1"; // 5 cedente
		$MM_redirectLoginSuccessDemo = "crm/operaciones"; // ejecutivos
		$MM_redirecttoReferrer = false;

		$LoginRS__query = sprintf("SELECT usuario, clave, nivel, nombre, id, sexo, email, cargo, anexo_foco, INBOUND, multiServicio, mandante, Id_Cedente FROM Usuarios WHERE usuario = %s", $this->db->GetSQLValueString($this->loginUsername, "text"));
		$LoginRS = $this->db->select($LoginRS__query);
		$isValid = False;

		if (count((array) $LoginRS) > 0) { // si entro aca es porque el usuario existe
			// verifico el password con el Hash
			$objetoHash = new Hash();
			if($objetoHash->verificarHash($password, $LoginRS[0]['clave'])) {
				// si entro aca es porque las contraseñas si coinciden
				$isValid = true;
			}
		}

		if ($isValid) { // si entra aca es porque el usuario y contraseña son correctos
			unset($LoginRS[0]['clave']);
			$this->loginStrGroup = (int) $LoginRS[0]['nivel'];
			$this->idUsuLogin 	 = (int) $LoginRS[0]['id'];
			$this->nombreUsu 	   = $LoginRS[0]['nombre'];
			$this->emailUsu 	   = $LoginRS[0]['email'];
			$this->cargoUsu 	   = $LoginRS[0]['cargo'];
			$this->sexo 		     = $LoginRS[0]['sexo'];
			$this->anexo_foco 	 = $LoginRS[0]['anexo_foco'];
			$this->inbound 		   = $LoginRS[0]['INBOUND'];
			$this->multiServicio = (int) $LoginRS[0]['multiServicio'];
			if (!empty($LoginRS[0]['mandante']) && !is_null($LoginRS[0]['mandante'])) $this->empresas = explode(',', $LoginRS[0]['mandante']);
			if (!empty($LoginRS[0]['Id_Cedente']) && !is_null($LoginRS[0]['Id_Cedente'])) $this->proyectos = explode(',', $LoginRS[0]['Id_Cedente']);

			$logo = "";
			$nombreLogo = "";
			// OJO esta creando las variables de session asi el nivel del usuario no exista
			// session logo por defecto
			/*
			$datosLogo = $this->db->select("SELECT logo, nombre FROM logo WHERE tipoSistema = 10");
			if($datosLogo) {
				$logo 		= $datosLogo[0]['logo'];
				$nombreLogo = $datosLogo[0]['nombre'];
			} else {
				$logo = "";
				$nombreLogo = "";
			}
			*/
			$array = [
				"MM_Username" 	=> $this->loginUsername,
				"MM_UserGroup" 	=> $this->loginStrGroup,
				"nombreUsuario" => $this->nombreUsu,
				"emailUsuario" 	=> $this->emailUsu,
				"cargoUsuario" 	=> $this->cargoUsu,
				"id_usuario" 	  => $this->idUsuLogin,
				"sexo_usuario" 	=> $this->sexo,
				"anexo_foco" 	  => $this->anexo_foco,
				"logo" 			    => $logo,
				"nombreLogo" 	  => $nombreLogo,
				"inbound" 		  => $this->inbound,
				"multiServicio" => $this->multiServicio,
				"empresas"      => $this->empresas,
				"proyectos"     => $this->proyectos,
			];

			$this->crearVariableSession($array);
			$usuario_sis = $_SESSION['MM_Username'];
			$personal = $this->db->select("SELECT * FROM Personal WHERE Nombre_Usuario = '".$usuario_sis."'");
			if($personal) {
				$_SESSION['personal'] 		= $personal[0]['Id_Personal'];
				$_SESSION['personalName'] = $personal[0]['Nombre'];
			}

			if (isset($_SESSION['PrevUrl']) && false) { // OJO VERIFICAR ESTE CASO
				$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
			}

			if($this->multiServicio == 1) {
				header("Location: " . $redirecionaSuite );
				exit;
			} else {
				if ($this->loginStrGroup == 1) {
					// $ced = $this->db->select("SELECT * FROM Usuarios WHERE usuario = '$usuario_sis' LIMIT 1");
					$this->registrarLogLogin();
					// header("Location: " . $MM_redirectLoginSuccessNew );
					header("Location: " . $MM_redirectLoginSuccess, true);
					exit;
				}
		
				if($this->loginStrGroup == 2) {
					//$ced = $this->db->select("SELECT * FROM Usuarios WHERE usuario = '$usuario_sis' LIMIT 1");
					//if(count((array) $ced) > 0){
						//if(empty($LoginRS[0]["mandante"])){
							$MM_redirectLoginSuccess2 = "proyecto";
						//} else {
							// $_SESSION['mandante'] = $LoginRS[0]["mandante"];
							//$_SESSION['empresas'] = explode(',',$LoginRS[0]["mandante"]);
						//}
					//}
					$this->registrarLogLogin();
					// header("Location: " . $MM_redirectLoginSuccessNew );
					header("Location: " . $MM_redirectLoginSuccess2, true);
					exit;
				}
						
				if($this->loginStrGroup == 3) {
					//$ced = $this->db->select("SELECT * FROM Usuarios WHERE usuario = '$usuario_sis' LIMIT 1");
					//if(count((array) $ced) > 0){
					require_once(__DIR__.'/../jwt/jwt.php');
					//$url = 'http://127.0.0.1:9206/#/account/validate/';
					$url = $_ENV['AGENTES_URL'].'/#/account/validate/';
					$jwt = new Util_Jwt();
					$payload = [
						'username' => trim($loginUsername),
						'password' => trim($password),
						'iat' => time(),
					];
					$token = $jwt->encode($payload);
					$url .= $token;
					/*
					if(empty($LoginRS[0]["mandante"])) {
						$MM_redirectLoginSuccess5 = "mandante";
					} else {
						$_SESSION['mandante'] = $LoginRS[0]["mandante"];
					}
					*/
					//}
					$this->registrarLogLogin();
					session_destroy();
					header('Location: '.$url, true);
					exit;
				}
		
				if($this->loginStrGroup == 4) { 
					// OJOOO PROBAR ESTE CASO PREGUNTAR EL NEGOCIO DE PORQ EXISTEN VARIOS USUARIO CON EL MISMO NOMBRE DE USUARIO
					//$ced =  $this->db->select("SELECT Id_Cedente, user_dial, pass_dial, mandante FROM Usuarios WHERE usuario = '{$usuario_sis}' LIMIT 1");
					//if(count((array) $ced) > 0){
						$_SESSION['cedente']   = $LoginRS[0]["Id_Cedente"];
						$_SESSION['user_dial'] = $LoginRS[0]["user_dial"];
						$_SESSION['pass_dial'] = $LoginRS[0]["pass_dial"];
						if(empty($LoginRS[0]["mandante"])) {
							$MM_redirectLoginSuccess4 = "mandante";
						} else {
							$_SESSION['mandante'] = $LoginRS[0]["mandante"];
							if(empty($_SESSION['cedente'])) $MM_redirectLoginSuccess4 = "cedente";
						}
						$_SESSION['isEjecutivo'] = true;
					//}
					$this->registrarLogLogin();
					header("Location: " . $MM_redirectLoginSuccess4, true);
					exit;
				}
		
				if ($this->loginStrGroup == 5) {
					// $ced = $this->db->select("SELECT * FROM Usuarios WHERE usuario = '{$usuario_sis}' LIMIT 1");
					//if (count((array) $ced) > 0) {
						if (!empty($LoginRS[0]["mandante"])) $_SESSION['mandante'] = $LoginRS[0]["mandante"];
					//}
					$this->registrarLogLogin();
					header("Location: " . $MM_redirectLoginSuccess, true);
					exit;
				}
		
				if($this->loginStrGroup == 6) {
					//$ced = $this->db->select("SELECT * FROM Usuarios WHERE usuario = '$usuario_sis' LIMIT 1");
					//if(count((array) $ced) > 0) {
						if(empty($LoginRS[0]["mandante"])) {
							$MM_redirectLoginSuccess5 = "mandante";
						} else {
							$_SESSION['mandante'] = $LoginRS[0]["mandante"];
						}
					//}
					$this->registrarLogLogin();
					header("Location: " . $MM_redirectLoginSuccess5, true);
					exit;
				}

				if($this->loginStrGroup == 100) {
					//$ced = $this->db->select("SELECT * FROM Usuarios WHERE usuario = '$usuario_sis' LIMIT 1");
					$this->registrarLogLogin();
					$_SESSION['mandante'] = 1;
					$_SESSION['cedente'] = 100;
					header("Location: " . $MM_redirectLoginSuccessDemo, true);
					exit;
				}
			}				
		} else {
			// Registrando ingreso fallido en el sistema
			$this->registrarLogFallidoLogin($loginUsername,$password);
			return 1;
			header("Location: ". $MM_redirectLoginFailed, true);
		}
	}

	// *** Restrict Access To Page: Grant or deny access to this page
	public function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
	{
		$isValid = False;
		if (!empty($UserName)) {
			$arrUsers = explode(",", $strUsers);
			$arrGroups = explode(",", $strGroups);
			if (in_array($UserName, $arrUsers))	$isValid = true;
			// Or, you may restrict access to only certain users based on their username.
			if (in_array($UserGroup, $arrGroups)) $isValid = true;
			if (($strUsers == "") && false)	$isValid = true;
		}
		return $isValid;
	}

	public function creaMM_restrictGoTo()
	{
		$MM_restrictGoTo = "../index";
		if (!(isset($_SESSION['MM_Username'])))	{
			$MM_qsChar = "?";
			$MM_referrer = $_SERVER['PHP_SELF'];
			if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
			if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
			$MM_referrer .= "?" . $QUERY_STRING;
			$MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
			header("Location: ". $MM_restrictGoTo, true);
			exit;
		}
	}

	public function creaLogoutAction()
	{
		// ** Logout the current user. **
		$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true"; // cierra session
		if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
		return $logoutAction;
	}

	public function borrarVariablesSession()
	{
		unset($_SESSION['MM_Username']);
		unset($_SESSION['MM_UserGroup']);
		unset($_SESSION['PrevUrl']);
		unset($_SESSION['cedente']);
		session_unset();
		$this->destruirSession();
	}

	public function logoutGoTo($logoutGoTo = '')
	{
		if (!empty($logoutGoTo)) {
			header("Location: $logoutGoTo", true);
			exit;
		}
	}		

	public function registrarLogLogin()
	{
		$fechaHora = date('Y-m-d H:i:s');
		$idMenu = 1;
		$sql = "INSERT INTO log_modulo (fecha, id_usuario, usuario, id_menu,ip) VALUES ('".$fechaHora."', '".$_SESSION["id_usuario"]."', '".$_SESSION['MM_Username']."', '".$idMenu."', '".$_SERVER['REMOTE_ADDR']."')";
		$this->db->query($sql);
	}

	public function registrarLogFallidoLogin($usuario,$password)
	{
		$fechaHora = date('Y-m-d H:i:s');
		$sql="INSERT INTO log_fallidos_login (fecha, usuario, password, ip) VALUES ('".$fechaHora."','".$usuario."','".$password."','".$_SERVER['REMOTE_ADDR']."')";
		$this->db->query($sql);
	}
}
?>