<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
print_r($Permisos);
$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "modSup,supRepDisc"));
// ** Logout the current user. **
/*$objetoSession->creaLogoutAction();
/*if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
	//to fully log out a visitor we need to clear the session varialbles
		$objetoSession->borrarVariablesSession();
		$objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
if(isset($_SESSION['cedente'])){
		if($_SESSION['cedente'] != ""){
			$cedente = $_SESSION['cedente'];
		}
}*/
?>
<!DOCTYPE html>
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
		<link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
		<link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
		<link href="../plugins/pace/pace.min.css" rel="stylesheet">
		<script src="../plugins/pace/pace.min.js"></script>
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
		<link href="../css/global/global.css" rel="stylesheet">
		<style type="text/css">
			.no_data
			{
				text-align: center;
				padding-top: 20px;
				padding-bottom: 20px;
				font-weight: bold;
				color: red;
				font-size: 12px;
			}
		</style>
	</head>
<body>
	<?php 
		if (isset($_SESSION['cola'])){?>
			<input type="hidden" name="cola" id="cola" value="<?php echo $_SESSION['cola'];?>">
	<?php
		}
	?>
	<input type="hidden" name="nivel1" id="nivel1" value="">
	<input type="hidden" name="nivel2" id="nivel2" value="">
	<div id="container" class="effect mainnav-lg">
		<!--NAVBAR-->
		<!--===================================================-->
		<?php
		include("../layout/header.php");
		?>
		<!--===================================================-->
		<!--END NAVBAR-->
			<div class="boxed">
				<!--CONTENT CONTAINER-->
				<!--===================================================-->
				<div id="content-container">
					<!--Page Title-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div id="page-title">
						<h1 class="page-header text-overflow">Reporte Gestión</h1>
					<!--Searchbox-->
					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End page title-->
					<!--Breadcrumb-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<ol class="breadcrumb">
						<li><a href="#">Supervisión</a></li>
						<li class="active">Reporte Gestión</li>
					</ol>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End breadcrumb-->
					<!--Page content-->
					<!--===================================================-->
					<div id="page-content">
						<div class="row" id="div_gestiones" style="display: none">
							<div class="eq-height">
								<div class="col-sm-4">
									<div class="panel">
										<div class="panel-heading">
											<h2 class="panel-title">Gestión Nivel 1</h2>
										</div>
										<div class="panel-body">
											<div><canvas id="myChartNivel1"></canvas></div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="panel">
										<div class="panel-heading">
											<h2 class="panel-title">Gestión Nivel 2</h2>
										</div>
										<div class="panel-body">
											<div>
												<span id="span_Nivel2" style="padding-left: 25%;">
													Pinchar sobre un tipo de Gestión Nivel 1
												</span>
												<canvas id="myChartNivel2"></canvas>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="panel">
										<div class="panel-heading">
											<h2 class="panel-title">Gestión Nivel 3</h2>
										</div>
										<div class="panel-body">
											<div>
												<span id="span_Nivel3" style="padding-left: 25%;">
													Pinchar sobre un tipo de Gestión Nivel 2
												</span>
												<canvas id="myChartNivel3"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="eq-height">
								<div class="col-sm-12">
									<div class="panel no_data" style="display: none" id="no_data">
										<span>NO EXISTEN DATOS PARA LA COLA SELECCIONADA</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="eq-height">
								<div class="col-sm-12">
									<div class="panel" style="display: none" id="gestionTablePanel">
										<div class="panel-heading">
											<h2 class="panel-title">Lista por tipo de gestión</h2>
										</div>
										<div class="panel-body">
											<table id="gestionExport" style="width:100%;">
												<thead>
													<tr>
														<th>Cola</th>
														<th>Rut</th>
														<th>Gestión Nivel 1</th>
														<th>Gestión Nivel 2</th>
														<th>Gestión Nivel 3</th>
														<th>Fecha</th>
														<th>Exportar</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
						</div>
					</div>
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
		</div>
		<!--===================================================-->
		<!-- END OF CONTAINER -->
		<!--JAVASCRIPT-->
		<script src="../js/jquery-2.2.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../plugins/fast-click/fastclick.min.js"></script>
		<script src="../js/nifty.min.js"></script>
		<script src="../plugins/morris-js/morris.min.js"></script>
		<script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
		<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
		<script src="../plugins/skycons/skycons.min.js"></script>
		<script src="../plugins/switchery/switchery.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../js/demo/nifty-demo.min.js"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../js/demo/ui-alerts.js"></script>
		<script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
		<script src="../js/global/funciones-global.js"></script>
		<script src="../plugins/Chart.js/Chart.min.js"></script>
		<script src="../plugins/Chart.js/Chart.bundle.min.js"></script>
		<script src="../js/supervision/supervisionGestion.js"></script>
</body>
</html>