<?PHP
	require_once('../class/db/DB.php');
	require_once('../class/session/session.php');

	include("../class/global/global.php");
	$objetoSession = new Session('1,2,3,4,6',false); // 1,4
	//Para Id de Menu Actual (Menu Padre, Menu hijo)
	$objetoSession->crearVariableSession($array = array("idMenu" => "per,per"));
	// ** Logout the current user. **
	$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
	if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
	{ //
		//to fully log out a visitor we need to clear the session varialbles
		$objetoSession->borrarVariablesSession();
		$objetoSession->logoutGoTo("../index.php");
	}
	$objetoSession->creaMM_restrictGoTo();
	$usuario = $_SESSION['MM_Username'];
	$nombreUsuario = $_SESSION['nombreUsuario'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>CRM Sinaptica | Software de Estrategia</title>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/nifty.min.css" rel="stylesheet">
		<link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
		<link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
		<link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
		<link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
		<link href="../plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
		<link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
		<link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../css/perfil.css" rel="stylesheet">
		<link href="../css/global/global.css" rel="stylesheet">
		<style type="text/css" media="screen">
			.img-lg{
				width: 150px;
				height: 150px;
			}
		</style>
	</head>
	<body>
		<div id="container" class="effect mainnav-lg">
			<!--NAVBAR-->
			<!--===================================================-->
			<?php include("../layout/header.php"); ?>
			<!--===================================================-->
			<!--END NAVBAR-->
			<div class="boxed">
				<!--CONTENT CONTAINER-->
				<!--===================================================-->
				<div id="content-container">
					<!--Page Title-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div id="page-title">
						<h1 class="page-header text-overflow">Perfil</h1>
					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End page title-->
					<!--Breadcrumb-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<ol class="breadcrumb">
						<li><a href="#">Inicio</a></li>
						<li class="active">Perfil</li>
					</ol>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End breadcrumb-->
					<!--Page content-->
					<!--===================================================-->
					<div id="page-content">
						<div class="row">
							<div class="col-md-3">
								<div class="panel">
									<div class="row" style="padding: 15px 15px 0;">
										<div class="col-md-12">
											<div class="text-center pad-all">
												<div class="pad-ver cont-preImg2 img-border img-circle img-lg" style="overflow: hidden;">
													<img src="../img/av1.png" class="img-lg img-circle" alt="Profile Picture">
												</div>
												<h3 class="text-overflow mar-no"><?php echo $_SESSION['nombreUsuario']; ?></h3>
												<h4 class="text-muted"><?php echo $_SESSION['emailUsuario']; ?></h4>
												<h4 class="text-muted"><?php echo $_SESSION['cargoUsuario']; ?></h4>
											</div>
											<form>
												<input type="hidden" name="x1" id="x1">
												<input type="hidden" name="y1" id="y1">
												<input type="hidden" name="x2" id="x2">
												<input type="hidden" name="y2" id="y2">
												<input type="hidden" name="w1" id="w1">
												<input type="hidden" name="h1" id="h1">
												<input type="hidden" name="w2" id="w2">
												<input type="hidden" name="h2" id="h2">
											</form>
										</div>
									</div>
									<!-- Simple profile -->
								</div>
							</div><!-- col-0 -->
							<div class="col-md-5">
								<div class="panel">
									<div class="panel-heading">
										<h4 class="panel-title">
											<i class="fa fa-user"></i>&nbsp;&nbsp;Datos de Usuario
										</h4>
									</div>
									<div class="panel-body">
										<div class="row" style="padding: 0 15px;">
											<div class="col-md-12">
												<div class="form-group">
													<label>Usuario</label>
													<input type="text" class="form-control" name="Usuario" readonly>
												</div>
												<div class="form-group">
													<label>Nombre</label>
													<input type="text" class="form-control" name="Nombre">
												</div>
												<div class="form-group">
													<label>Correo</label>
													<input type="text" class="form-control" name="Correo">
												</div>
												<div class="form-group" style="margin-top: 10px; margin-bottom: 0;">
													<button type="button" class="btn btn-primary" id="procesar"><i class="ti-save"></i>&nbsp;&nbsp;Guardar Datos</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">&nbsp;</div>
							<div class="col-md-5">
								<div class="panel">
									<div class="panel-heading">
										<h4 class="panel-title">
											<i class="fa fa-key"></i>&nbsp;&nbsp;Cambio de Contraseña
										</h4>
									</div>
									<div class="panel-body">
										<div class="row" style="padding: 0 15px;">
											<div class="col-md-12">
												<div class="form-group">
													<input id="pass" class="magic-checkbox check" type="checkbox">
													<label for="pass">Cambiar contraseña</label>
												</div>
												<div class="form-group">
													<label>Contraseña actual</label>
													<input type="password" name="pass" class="form-control pass1" disabled="disabled">
												</div>
												<div class="form-group">
													<label>Nueva contraseña</label>
													<input type="password" class="form-control pass2" name="pass2" disabled="disabled">
												</div>
												<div class="form-group">
													<label>Repita su contraseña</label>
													<input type="password" class="form-control pass3" name="newPass" disabled="disabled">
												</div>
												<div class="form-group">
													<button type="button" class="btn btn-primary" id="newPass"><i class="ti-save"></i>&nbsp;&nbsp;Cambio de Contraseña</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- row -->
					<!--===================================================-->
					<!--End page content-->
				</div>
				<!--===================================================-->
				<!--END CONTENT CONTAINER-->
				<!--MAIN NAVIGATION-->
				<!--===================================================-->
				<?php include("../layout/main-menu.php"); ?>
				<!--===================================================-->
				<!--END MAIN NAVIGATION-->
			</div>
			<!-- FOOTER -->
			<!--===================================================-->
			<?php include("../layout/footer.php"); ?>
			<!--===================================================-->
			<!-- END FOOTER -->
			<!-- SCROLL TOP BUTTON -->
			<!--===================================================-->
			<button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
			<div class="modal"><!-- Place at bottom of page --></div>
			<!--===================================================-->
			<div class="modal fade" tabindex="-1" role="dialog" id="Cargando">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<div class="spinner loading"></div>
								<h4 class="text-center">Procesando por favor espere...</h4>
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</div>
		<!--===================================================-->
		<!-- END OF CONTAINER -->

		<!--JAVASCRIPT-->
		<script src="../js/jquery-2.2.1.min.js"></script>
		<script src="../js/funciones.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../plugins/fast-click/fastclick.min.js"></script>
		<script src="../js/nifty.min.js"></script>
		<script src="../plugins/skycons/skycons.min.js"></script>
		<script src="../plugins/switchery/switchery.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../js/demo/nifty-demo.min.js"></script>
		<script src="../plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
		<script src="../plugins/datatables/media/js/jquery.dataTables.js"></script>
		<script src="../plugins/datatables/media/js/dataTables.bootstrap.js"></script>
		<script src="../plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../js/global/funciones-global.js"></script>
		<script src="../js/perfil/controller.js"></script>
	</body>
</html>