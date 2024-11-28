<?php
include("../class/global/global.php");
require_once('../class/db/DB.php');
require_once('../class/session/session.php');
$objetoSession = new Session('1,2,3,4,5,6',false); 
$objetoSession->crearVariableSession($array = array("idMenu" => "inicio,bien"));
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index");
}
$objetoSession->creaMM_restrictGoTo();
if (isset($_SESSION['cedente'])){
	$cedente = $_SESSION['cedente'];
}else{
	$cedente = '';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CRM Sinaptica</title>
	<!--STYLESHEET-->
	
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/nifty.min.css" rel="stylesheet">
	<link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
	<link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
	<link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
	<link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
	<link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
	<link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
	<link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
	<link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
	<link href="../plugins/pace/pace.min.css" rel="stylesheet">
	<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
	<link href="../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
	<link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
	<link href="../css/global/global.css" rel="stylesheet">
	<style>
		.bg-gray-dark {
				background-color: #c3cedb !important;
		}
		.text-transparent-min
		{
			width: auto;
			height: 20px;
			border: none;
			text-align: center;
			background-color:transparent;
		}

    </style>
</head>
<body>
	<div id="container">
		<!--NAVBAR-->
		<!--===================================================-->
		<?php
		include("../layout/header-suite.php");
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
					<h1 class="page-header text-overflow">Servicios Contratados</h1>
				<!--Searchbox-->
				</div>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End page title-->
				<!--Breadcrumb-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--<ol class="breadcrumb">
					<li><a href="#">Inicio</a></li>
					<li class="active">Bienvenido</li>
				</ol>-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End breadcrumb-->
				<!--Page content-->
				<!--===================================================-->
				<br>
				<ol class="breadcrumb">
					<li><a href="#">Inicio</a></li>
					<li class="active">SUITE</li>
				</ol>
				<div id="page-content">
					<div class="row">
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-body">
                                    <div class="row">
                                    <div class="row demo-nifty-panel">
                                        <div class="col-sm-2">
                                        <a href="http://www.google.cl">
                                            <!--Primary panel-->
                                            <!--===================================================-->
                                            <div class="panel panel-colorful panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">CRM</h3>
                                                </div>
                                                <div class="panel-body">
                                                <i class="fa fa-users fa-5x"></i>
                                                </div>
                                            </div>
                                            <!--===================================================-->
                                            <!--End Primary Panel-->
                                    
                                        </div>

                                        </a>
                                        <div class="col-sm-2">
                                        <a href="../mandante">
                                            <!--Info Panel-->
                                            <!--===================================================-->
                                            <div class="panel panel-colorful panel-warning">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Cobranza</h3>
                                                </div>
                                                <div class="panel-body">
                                                <i class="fas fa-money-check-alt fa-5x"></i>
                                                </div>
                                            </div>
                                            <!--===================================================-->
                                            <!--End Info Panel-->
                                        </a>
                                        </div>
                                        <div class="col-sm-2">
                                    
                                            <!--Success Panel-->
                                            <!--===================================================-->
                                            <div class="panel panel-colorful panel-success">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Soporte</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <i class="fas fa-life-ring fa-5x"></i>
                                                </div>
                                            </div>
                                  
                                    
                                        </div>
                                    </div>
					
                                    </div>
								</div>
							</div>
						</div>										
					<!--===================================================-->
					</div>
					<input type="hidden" id="idUsuario" value="<?php echo $_SESSION['id_usuario']; ?>">
					<input type="hidden" id="nivel" value="<?php echo $nivel; ?>">

					<!--===================================================-->
					<!--END CONTENT CONTAINER-->
					<!--MAIN NAVIGATION-->
					<!--===================================================-->
					<?php include("../layout/main-menu-suite.php"); ?>
					<!--===================================================-->
					<!--END MAIN NAVIGATION-->
					<!--ASIDE-->




					<!--===================================================-->
					<!--END ASIDE-->
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
			</div>
 
		</div>
		<!--===================================================-->
		<!-- END OF CONTAINER -->
		<!--JAVASCRIPT-->
		<script src="../js/jquery-2.2.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../plugins/fast-click/fastclick.min.js"></script>
		<script src="../js/nifty.min.js"></script>
		<script src="../plugins/skycons/skycons.min.js"></script>
		<script src="../plugins/switchery/switchery.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../js/demo/nifty-demo.min.js"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../js/demo/ui-alerts.js"></script>
		<script src="../js/global/funciones-global.js"></script>
		<script src="../plugins/fullcalendar/lib/moment.min.js"></script>
		<script src="../plugins/fullcalendar/lib/jquery-ui.custom.min.js"></script>
		<script src="../plugins/fullcalendar/fullcalendar.min.js"></script>
		<script src='../plugins/fullcalendar/lang-all.js'></script>
		<script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
		<script src="../plugins/pace/pace.min.js"></script>
        <script src="../js/bienvenida/bienvenida.js"></script>
        <script src="https://kit.fontawesome.com/8d94a703e8.js"></script>

</body>
</html>
