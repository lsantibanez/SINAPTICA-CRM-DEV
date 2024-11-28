<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->
crearVariableSession($array = array("idMenu" => "inicio,bien")); // ** Logout the current user. ** $objetoSession->creaLogoutAction(); if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) { //to fully log out a visitor we need to clear the session varialbles $objetoSession->borrarVariablesSession(); $objetoSession->logoutGoTo("../index.php"); } $validar = $_SESSION['MM_UserGroup']; $objetoSession->creaMM_restrictGoTo(); $usuario = $_SESSION['MM_Username']; if (isset($_SESSION['cedente'])){ $cedente = $_SESSION['cedente']; } ?> <!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRM Sinaptica | Software de Estrategia</title>
<!--STYLESHEET-->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/nifty.min.css" rel="stylesheet">
<link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
<link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
<link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
<link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
<link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
<link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
<link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
<link href="../plugins/pace/pace.min.css" rel="stylesheet">
<script src="../plugins/pace/pace.min.js"></script>
<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
<link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>
<body>
<div id="container" class="effect mainnav-lg">
	<!--NAVBAR-->
	<!--===================================================-->
	<?php
        include("../layout/header.php");
    ?>
	<!--===================================================-->
	<!--END NAVBAR-->
	<div class="boxed">
		<div id="content-container">
			<div id="page-title">
			</div>
			<br>
			<ol class="breadcrumb">
				<li><a href="#">Paleta</a></li>
				<li class="active">Paleta de Respuestas</li>
			</ol>
			<div id="page-content">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel">
							<div class="panel-heading">
								<h2 class="panel-title bg-primary">Filtro</h2>
							</div>
							<div class="panel-body">
								<div class="col-sm-3">
									<div class="form-group">
										<div class="input-daterange input-group " id="datepicker">
											<label for="mandante">Seleccione Mandante</label>
											<select name="mandante" id="mandante" class="selectpicker mandante form-control" data-live-search="true" data-width="100%" title="Seleccione Mandante">
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<div id="Div3">
											<label for="cedente">Seleccione Cedente</label>
											<select name="cedente" id="cedente" class="selectpicker cedente form-control" data-live-search="true" data-width="100%" title="Seleccione Cedente">
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label class="control-label">&nbsp;&nbsp;</label>
										<button class="btn btn-primary btn-block" id="continuar">Continuar</button>
									</div>
								</div>
								<!-- inicio row oculto de niveles -->
								<div class="row" id="oculto" style="display:none;">
									<div class="col-sm-12">
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<div class="input-daterange input-group " id="datepicker">
												<br>
												<label for="vernivel1">Nivel 1</label>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<br>
										<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalnivel1" id="vernivel1" name="vernivel1"><i class="fa fa-plus"></i></button>
									</div>
									<div class="col-sm-12">
									</div>
									<div class="col-sm-3" id="mandante">
										<div class="form-group">
											<div class="input-daterange input-group " id="datepicker">
												<br>
												<label for="vernivel2">Nivel 2</label>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<br>
										<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalnivel2" id="vernivel2" name="vernivel2"><i class="fa fa-plus"></i></button>
									</div>
									<div class="col-sm-12">
									</div>
									<div class="col-sm-3" id="mandante">
										<div class="form-group">
											<div class="input-daterange input-group " id="datepicker">
												<br>
												<label for="vernivel3">Nivel 3</label>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<br>
										<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalnivel3" id="vernivel3" name="vernivel3"><i class="fa fa-plus"></i></button>
									</div>
									<div class="col-sm-12">
									</div>
								</div>
								<!-- fin row oculto de niveles -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Inicio Modal nivel 1-->
			<div class="row">
				<div class="modal fade" id="modalnivel1" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Nivel 1</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<form id="formnivel1" role="form" name="formnivel1">
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="nombreRespuesta" class="control-label">Nombre Respuesta</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="text" name="nombreRespuesta" id="nombreRespuesta" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label class="control-label">Niveles Ingresados</label>
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-offset-2 col-sm-6">
											<div class="form-group">
												<div>
													<label for="nombrenivel1" class="col-sm-6">Id</label><label for="idcedente">Nombre</label>
													<hr style="height: 1px; background-color: black; margin-top:0px; margin-bottom:10px;">
													<select name="nombrenivel1" id="nombrenivel1" class="selectpicker nombrenivel1 form-control" data-live-search="true" data-width="100%" title="Ver Nivel">
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-5 col-sm-offset-2">
											<div class="form-group">
												<button type="submit" id="btnregistrar1" class="btn btn-primary btn-block" data-dismiss="modal">Guardar</button>
											</div>
										</div>
									</form>
								</div>
								<hr>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--fin modal nivel 1-->
			<!-- Inicio Modal nivel 2-->
			<div class="row">
				<div class="modal fade" id="modalnivel2" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Nivel 2</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<form id="formnivel2" role="form" name="formnivel2">
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="nombrenivel1_2" class="control-label">Seleccione Nivel 1</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<select name="nombrenivel1_2" id="nombrenivel1_2" class="selectpicker nombrenivel1_2 form-control" data-live-search="true" data-width="100%" title="Ver Nivel">
												</select>
											</div>
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="nombreRespuesta2" class="control-label">Nombre Respuesta</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="text" name="nombreRespuesta2" id="nombreRespuesta2" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label class="control-label">Niveles Ingresados</label>
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-offset-2 col-sm-6">
											<div class="form-group">
												<div>
													<label for="nombrenivel2" class="col-sm-6">Id</label><label for="nombrenivel2">Nombre</label>
													<hr style="height: 1px; background-color: black; margin-top:0px; margin-bottom:10px;">
													<select name="nombrenivel2" id="nombrenivel2" class="selectpicker nombrenivel2 form-control" data-live-search="true" data-width="100%" title="Ver nivel 2">
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-5 col-sm-offset-2">
											<div class="form-group">
												<button type="submit" id="btnregistrar2" class="btn btn-primary btn-block" data-dismiss="modal">Guardar</button>
											</div>
										</div>
									</form>
								</div>
								<hr>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--fin modal nivel 2-->
			<!-- Inicio Modal nivel 3-->
			<div class="row">
				<div class="modal fade" id="modalnivel3" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Nivel 3</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<form id="formnivel3" role="form" name="formnivel3">
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="nombrenivel2_1" class="control-label">Seleccione Nivel 2</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
											<select name="nombrenivel2_1" id="nombrenivel2_1" class="selectpicker nombrenivel2_1 form-control" data-live-search="true" data-width="100%" title="Ver nivel 2">
											</select>
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="gestion" class="control-label">Tipo Gestion</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="number" name="gestion" id="gestion" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="ponderacion" class="control-label">Ponderación</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="number" name="ponderacion" id="ponderacion" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="peso" class="control-label">Peso</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="number" name="peso" id="peso" class="form-control">
											</div>
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label for="nombreRespuesta3" class="control-label">Nombre Respuesta</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="text" name="nombreRespuesta3" id="nombreRespuesta3" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-3 col-sm-offset-2">
											<div class="form-group">
												<label class="control-label">Niveles Ingresados</label>
											</div>
										</div>
										<div class="col-sm-12">
										</div>
										<div class="col-sm-offset-2 col-sm-6">
											<div class="form-group">
												<div>
													<label for="nombrenivel3" class="col-sm-6">Id</label><label for="idcedente3">Nombre</label>
													<hr style="height: 1px; background-color: black; margin-top:0px; margin-bottom:10px;">
													<select name="nombrenivel3" id="nombrenivel3" class="selectpicker nombrenivel3 form-control" data-live-search="true" data-width="100%" title="Ver Nivel 3">
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-5 col-sm-offset-2">
											<div class="form-group">
												<button type="submit" id="btnregistrar3" class="btn btn-primary btn-block" data-dismiss="modal">Guardar</button>
											</div>
										</div>
									</form>
								</div>
								<hr>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--fin modal nivel 3-->
		</div>
		<?php include("../layout/main-menu.php"); ?>
	</div>
	<?php include("../layout/footer.php"); ?>
</div>
<script src="../js/jquery-2.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../plugins/fast-click/fastclick.min.js"></script>
<script src="../js/nifty.min.js"></script>
<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="../plugins/skycons/skycons.min.js"></script>
<script src="../plugins/switchery/switchery.min.js"></script>
<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="../js/demo/nifty-demo.min.js"></script>
<script src="../plugins/bootbox/bootbox.min.js"></script>
<script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="../js/global/funciones-global.js"></script>
<script src="../js/paleta_respuestas/paleta_respuestas.js"></script>
</body>
</html>