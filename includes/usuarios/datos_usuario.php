<?php
	include("../class/usuarios/usuarios.php");
	include("../class/global/cedente.php");
	//include("../class/db/DB.php");
	$objetoCedente = new Cedente();
	$objetoUsuario = new Usuarios();
	$creaModificaUsua = "crearUsuario";
	$modificar = false;
	$id_usu = '';
	$tituloVentana = "Nuevo Usuario";
	$nombre = '';
	$usuario = '';
	$rolUsuario = '';
	$idEmpresa = '';
	$idProyecto = '';
	$extension = '';
	$userProyectos = [];

	if (!empty($id_usuario) && intval($id_usuario) > 0) {
		$creaModificaUsua = "modificarUsuario";
		$id_usu = $id_usuario;
		$tituloVentana = "Modificar Usuario";
		$password_usu = "*.8//";
		$datos = $objetoUsuario->datosUsuario($id_usuario);
		if($datos["haveUsuario"]) {
			unset($datos['clave']);
			$modificar = true;
			$nombre = $datos['nombre_usuario'];
			$usuario = $datos['usuario'];
			$rolUsuario = (int) $datos['nivel'];
			$idEmpresa = (int) $datos['id_mandante'];
			$idProyecto = (int) $datos['id_cedente'];
			$extension = $datos['user_dial'];
			$userProyectos = $datos['proyectos'];
		}
	}
?>
