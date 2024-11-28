<?php
include("../class/global/global.php");
require_once('../class/db/DB.php');
require_once('../class/session/session.php');
$objetoSession = new Session('1,2,3,4,5,6',false); 
$objetoSession->crearVariableSession($array = array("idMenu" => "clientes,verCli"));
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
$rut = $_GET['rut'];
$mysqli = new DB();
$nombreCliente = '';
$deudaTotal = 0;
$deudaVencida = 0;
$porVencer = 0;
$contacto = '';
$fono = 0;
$mail = '';
$direccion = '';
$cobrador = '';
$fechaPago = '';
$montoPago = 0;

$q1 = $mysqli->select("SELECT A.Nombre_Completo,SUM(B.Saldo_ML) as deudaTotal FROM Persona A JOIN Deuda B 
	ON A.Rut = B.Rut WHERE A.Rut = '$rut'");
foreach($q1 as $row){
	$nombreCliente= $row['Nombre_Completo'];
	$deudaTotal = $row['deudaTotal'];
}

$q2 = $mysqli->select("SELECT SUM(Saldo_ML) deudaVencida FROM Deuda WHERE Rut = '$rut' AND dias_atraso > 0");
foreach($q2 as $row){
	$deudaVencida = $row['deudaVencida'];
}

$q3 = $mysqli->select("SELECT SUM(Saldo_ML) porVencer FROM Deuda WHERE Rut = '$rut' AND dias_atraso <= 0");
foreach($q3 as $row){
	$porVencer = $row['porVencer'];
}

$q4 = $mysqli->select("SELECT contacto,fono,mail,direccion,cobrador FROM disal.carterizacionFinal WHERE rut = '$rut' ");
foreach($q4 as $row){
	$contacto = $row['contacto'];
	$fono = $row['fono'];
	$mail = $row['mail'];
	$direccion = $row['direccion'];
	$cobrador = $row['cobrador'];
}

$q5 = $mysqli->select("SELECT fechaPago,monto FROM disal.pagosProcess WHERE rut = '$rut' ORDER By fechaPago DESC LIMIT 1");
foreach($q5 as $row){
	$fechaPago = $row['fechaPago'];
	$montoPago = $row['monto'];

}


function formatFecha($fecha){
	$exp = explode("-",$fecha);
	$dia = $exp[2]; 
	$mes = $exp[1]; 
	$ano = $exp[0]; 
	$nombremes = "";
	switch($mes){
		case "01" : 
		$nombremes = "Ene";
		break;
		case "02" : 
		$nombremes = "Feb";
		break;
		case "03" : 
		$nombremes = "Mar";
		break;
		case "04" : 
		$nombremes = "Abr";
		break;
		case "05" : 
		$nombremes = "May";
		break;
		case "06" : 
		$nombremes = "Jun";
		break;
		case "07" : 
		$nombremes = "Jul";
		break;
		case "08" : 
		$nombremes = "Ago";
		break;
		case "09" : 
		$nombremes = "Sep";
		break;
		case "10" : 
		$nombremes = "Oct";
		break;
		case "11" : 
		$nombremes = "Nov";
		break;
		case "12" : 
		$nombremes = "Dic";
		break;

	}
	$fechaFinal = $dia." ".$nombremes." ".$ano;
	return $fechaFinal;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
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
		.btn {
			background-color: DodgerBlue; /* Blue background */
			border: none; /* Remove borders */
			color: white; /* White text */
			font-size: 10px; /* Set a font size */
			cursor: pointer; /* Mouse pointer on hover */
		}
	</style>
</head>
<body>
	<div id="container" class="effect mainnav-lg ">
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
					<h1 class="page-header text-overflow">Clientes</h1>
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
					<li><a href="#">Clientes</a></li>
					<li class="active">Detalle de Cliente</li>
				</ol>
				<div id="page-content">
					<div class="row">
						<div class="col-sm-2 col-sm-offset-10">
							<button class="btn btn-primary btn-block" onclick="location.href='/clientes/clientesDetallePDf?rut=<?php echo $rut; ?>'">
								GENERAR PDF
							</button>

						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-body">
									<div class="col-sm-6">
										<div class="form-group">
											<b><?php echo $nombreCliente; ?></b><br>
											Rut : <b><?php echo $rut; ?></b>
											<br>
											Contacto : <?php echo $contacto; ?>
											<br>
											Teléfono : <?php echo $fono; ?>
											<br>
											Email : <?php echo $mail; ?>
											<br>
											Dirección : <?php echo $direccion; ?><br><br>
											
											Cobrador : <b><?php echo $cobrador; ?> </b><br><br>
											Scoring : Pendiente
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<b>Deuda Total: $ <?php echo number_format($deudaTotal, 0, '', '.'); ?></b><br><br>
											<span class="text-danger"> Vencida: $ <?php echo number_format($deudaVencida, 0, '', '.'); ?></span><br>
											<span class="text-primary"> No Vencida: $ <?php echo number_format($porVencer, 0, '', '.'); ?></span><br><br>
											Monto línea asignada :  Pendiente<br>
											Línea disponible : Pendiente
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											Días promedio de pago último año : Pendiente<br>
											Fecha último pago : <span class='label label-table label-purple'><?php echo $fechaPago; ?></span><br>
											Monto último pago : <?php echo number_format($montoPago, 0, '', '.'); ?>
											<br><br>
											
											Facturación promedio mes : Pendiente<br>
											Pago último mes : Pendiente<br><br>

											Compromisos vigentes : Pendiente<br>
											% Compromisos cumplidos : Pendiente
											
										</div>
									</div>						
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel" >
								<div class="panel-heading bg-primary">
									<div class="panel-control ">
										<ul class="nav nav-tabs">
											<li class="active"><a href="#demo-tabs-box-1" data-toggle="tab"><b>Listado de Facturas</b> </a></li>
											<li><a href="#demo-tabs-box-2" data-toggle="tab"><b>Listado de Gestiones</b></a></li>
										</ul>
									</div>
								</div>
								<div class="panel-body">
									<div class="tab-content">
										<div class="tab-pane fade in active" id="demo-tabs-box-1">
											<div class="panel">
												<table id='tabla_clientes'>
													<thead>
														<tr>
															<th>Factura</th>
															<th>Fecha Emisión</th>
															<th>Fecha Vencimiento</th>
															<th>Días Atraso</th>
															<th>Origen</th>
															<th>Aging</th>

															<th>Monto</th>

															<th>Saldo</th>
														</tr>	
													</thead>	
													<tbody>
														<?php 
														$query = $mysqli->select("SELECT Numero_Factura,fechaEmision,
															fechaVencimiento,Monto_Factura,Saldo_ML,dias_atraso,origen,aging FROM Deuda WHERE Rut = '$rut' and Id_Cedente !=4");
														foreach($query as $row){
															echo "<tr>";
															$numeroF= $row['Numero_Factura'];
															$fechaEmision = $row['fechaEmision'];
															$fechaVencimiento = $row['fechaVencimiento'];
															$monto = $row['Monto_Factura'];
															$saldo = $row['Saldo_ML'];
															$diasAtraso = $row['dias_atraso'];
															echo "<th>".$numeroF."</th>";
															echo "<th><span class='label label-table label-success'>".$fechaEmision."</span></th>";
															echo "<th><span class='label label-table label-warning'>".$fechaVencimiento."</span></th>";
															echo "<th>".$diasAtraso."</th>";
															echo "<th>".$row['origen']."</th>";
															echo "<th><span class='label label-table label-primary'>".$row['aging']."</span></th>";

															echo "<th>".$monto."</th>";
															echo "<th>".$saldo."</th>";
															echo "</tr>";

														}
														?>
														
													</tbody>		
													<tfoot>
														<tr>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>



														</tr>
													</tfoot>	
												</table>
											</div>
										</div>
										<div class="tab-pane fade" id="demo-tabs-box-2">
											<div class="panel">
												<table id='tabla_gestiones'>
													<thead>
														<tr>
															<th>Fecha Gestión</th>
															<th>Nombre Ejecutivo</th>
															<th>Teléfono</th>
															<th>Respuesta</th>
															<th>Sub Respuesta</th>
															<th>Sub Respuesta</th>
															<th>Número Factura</th>
															<th>Observación</th>
														</tr>	
													</thead>	
													<tbody>
														<?php 
														$queryGestion = $mysqli->select("SELECT * FROM gestion_ult_trimestre WHERE rut_cliente = '$rut'");
														foreach($queryGestion as $rowG){
															echo "<tr>";
															echo "<th>".$rowG['fecha_gestion']."</th>";
															//echo "<th><span class='label label-table label-purple'>".$rowG['fecha_gestion']."</span></th>";
															echo "<th>".$rowG['nombre_ejecutivo']."</th>";
															echo "<th>".$rowG['fono_discado']."</th>";
															echo "<th>".$rowG['n1']."</th>";
															echo "<th>".$rowG['n2']."</th>";
															echo "<th>".$rowG['n3']."</th>";
															echo "<th>".$rowG['factura']."</th>";
															echo "<th>".$rowG['observacion']."</th>";
															echo "</tr>";

														}
														?>

													</tbody>		
													<tfoot>
														<tr>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
															<th></th>
														</tr>
													</tfoot>	
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>				
						<input type="hidden" id="idUsuario" value="<?php echo $_SESSION['id_usuario']; ?>">
						<!--===================================================-->
						<!--END CONTENT CONTAINER-->
						<!--MAIN NAVIGATION-->
						<!--===================================================-->
						<?php include("../layout/main-menu.php"); ?>
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
		<script src="../js/clientes/clientes.js"></script>

	</body>
	</html>
