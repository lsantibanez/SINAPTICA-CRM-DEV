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
$db = new DB();
$sqlWhere = '';
if (isset($_SESSION['mandante']) && !empty($_SESSION['mandante']) && intval($_SESSION['mandante']) > 0) {
  $sqlWhere = 'AND id IN('.intval($_SESSION['mandante']).')';
}
$IdMandante = (int) $_SESSION['mandante'];
$estadisticas = [];

$rsMandantes = $db->select("SELECT id, nombre FROM mandante WHERE estatus = '1' AND nombre !='' {$sqlWhere} ORDER BY nombre ASC;");
if ($rsMandantes) {
  foreach ((array) $rsMandantes as $key => $iMandante) {
    $rsMandantes[$key]['cedentes'] = [];
    $sql = "SELECT DISTINCT c.Id_Cedente AS id, c.Nombre_Cedente AS nombre FROM Cedente AS c
        INNER JOIN mandante_cedente AS mc ON (mc.Id_Cedente = c.Id_Cedente) 
        INNER JOIN mandante AS m ON (m.id = mc.Id_Mandante) 
        WHERE m.id = '" . $iMandante['id'] . "' AND c.Id_Cedente != 100 ORDER BY c.Nombre_Cedente ASC";
    $rsCedentes = $db->select($sql);
    if ($rsCedentes) {
      $rsMandantes[$key]['cedentes'] = (array) $rsCedentes;
    }
  }
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
		.modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('../img/gears.gif')
            50% 50%
            no-repeat;
            }
	</style>
	<link href="../css/global/global.css" rel="stylesheet">
</head>
<body>
	<div id="container" class="effect mainnav-lg ">
		<?php include("../layout/header.php"); ?>
		<div class="boxed">
			<div id="content-container">
				<div id="page-title">
					<h1 class="page-header text-overflow">Bienvenida</h1>
				</div>
				<ol class="breadcrumb">
					<li><a href="#">Inicio</a></li>
					<li class="active">Bienvenida</li>
				</ol>
				<div id="page-content" style="padding: 12px;">
					<div class="row" style="padding: 10px;">
						<div class="col-lg-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row" style="padding: 10px 25px;">
										<div class="col-lg-8">
											<h3 style="margin-top: 0; margin-bottom: 15px;">¡Bienvenido a Sinaptica!</h3>
											<p style="font-size: 13px; text-align: justify; margin-bottom: 20px;">Revolucionando la recaudación de deudas con tecnología e innovación.<br/>Nuestro CRM en la nube, fácil de usar y diseñado para maximizar tu eficiencia en la gestión de cobranzas, te espera.<br/><br/>¡Empieza a transformar tu estrategia de cobranzas ahora!</p>
											<p style="font-size: 13px; text-align: justify;">Nos complace tenerte aquí. En cada paso que damos, nuestra pasión por la innovación y el compromiso con la excelencia nos guía.<br/>Explora las soluciones que hemos creado pensando en ti y descubre cómo podemos hacer tu día a día más fácil y eficiente.<br/><br/>¡Estamos emocionados de emprender este viaje contigo!</p>
										</div>
										<div class="col-lg-4">
											<img src="/img/crm_home.jpg" alt="CRM" style="width: 90%; height: auto; display: block;">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="padding: 0 12px;">
						<div class="col-md-6">
							<div class="panel">
								<div class="panel-body">
									<h3 style="margin: 0;">Estadisticas por carteras</h3>									
									<table class="table table-sm" id="tablaEstadisticas" style="margin-top: 15px; font-size: 13px;">
										<thead>
											<tr>
												<th>Cartera</th>
												<th style="width: 20%; text-align: center;">Gestionados</th>
												<th style="width: 20%; text-align: center;">Por gestionar</th>
												<th style="width: 20%; text-align: center;">Asignados</th>
											</tr>
										</thead>
										<tbody id="tablaDatosEstadisticos"></tbody>										
									</table>
								</div>
							</div>
						</div><!-- col 1 -->
						<div class="col-md-6">
							<div class="row">
								<div class="col-lg-12">
									<div class="panel">
										<div class="panel-body">
											<h4>Gestionados</h4>
											<canvas id="chartGestionados" style="width: 98%; height: 200px; display: block;"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="row" style="margin-top: 10px;">
								<div class="col-lg-12">
									<div class="panel">
										<div class="panel-body">
											<h4>Sin gestión</h4>
											<canvas id="chartSinGestion" style="width: 98%; height: 200px; display: block;"></canvas>
										</div>
									</div>
								</div>
							</div>
						</div><!-- col 2 -->
					</div>
				</div>
			</div><!-- content container -->

			<!--===================================================-->
			<?php include("../layout/main-menu.php"); ?>
			<!--===================================================-->
		</div><!-- boxed -->
		<button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
		<div class="modal"></div>
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
	<script src="/js/jschart/chart.min.js"></script>
  <script>
    const lstMandandantes = <?php  echo ($rsMandantes)? json_encode($rsMandantes): '[]'; ?>;
    
    $(document).ready(function() {
      llenarMandantes();
      validaDatos();
			llenarEstadisticas();
    });
    function regresar(){
      window.location.href = '/';
    }

    function llenarMandantes() {
      const selMandante = $('select#mandante');
      var html = `<option value="">-- Seleccione --</option>\n`;
      if (lstMandandantes.length) {
        lstMandandantes.forEach((mandante) => {
          html += `<option value="${mandante.id}">${mandante.nombre}</option>\n`;
        });        
      }
      selMandante.html(html);
      validaDatos();
    }

    function llenarCedentes(value) {
      const selCedente = $('select#cedente');
      var html = '<option value="">-- Seleccione --</option>';
      const iCedente = lstMandandantes.find(m => parseInt(m.id) === parseInt(value));
      if (iCedente !== undefined && iCedente['cedentes'].length) {
        iCedente['cedentes'].forEach((cedente) => {
          html += `<option value="${cedente.id}">${cedente.nombre}</option>\n`;
        })
      }
      selCedente.html(html);
    }

    function validaDatos() {
      const boton = $('button#continuar');
      const selMandante = $('select#mandante');
      const selCedente = $('select#cedente');
      if ((selMandante.val() !== '') && selCedente.val() !== '') {
        boton.removeAttr('disabled');
        return;
      }
      boton.attr('disabled');
    }

		function llenarEstadisticas() {
			const divContenedor = $('#tablaDatosEstadisticos');
			//const botonRecargar = $('button#botonRecargar')
			$.ajax({
				type: "GET",
				url: "/dashboard/getdata.php",
				dataType: "json",
				beforeSend: function () {
					console.log('cargando datos...')
					divContenedor.html('<tr><td colspan="4" style="padding: 10px 5px;">Cargando datos, por favor espere...</td></tr>')
					//botonRecargar.hide()
				},
				success: function(result)
				{
					let htmlRespuesta = '<tr><td colspan="4">Sin datos</td></tr>'
					console.log(result);
					if (result.carteras.length) {
						htmlRespuesta = '';
						for (let carte of result.carteras){
							htmlRespuesta += '<tr>';
							htmlRespuesta += '<td style="text-align: left;">'+carte.cartera+'</td>';
							htmlRespuesta += '<td style="text-align: center;">'+carte.gestionados+'<br/><small>'+carte.porcentaje_gestionados+' %</small></td>';
							htmlRespuesta += '<td style="text-align: center;">'+carte.faltantes+'<br/><small>'+carte.porcentaje_faltantes+' %</small></td>';
							htmlRespuesta += '<td style="text-align: center;">'+carte.total+'</td>';
							htmlRespuesta += '</tr>';
						}

						htmlRespuesta += '<tr>';
						htmlRespuesta += '<td style="text-align: right; font-weight:600;">Total:</td>';
						htmlRespuesta += '<td style="text-align: center; font-weight:600;">'+result.resumen.total_gestionados+'<br/><small>'+result.resumen.porcentaje_gestionados+' %</small></td>';
						htmlRespuesta += '<td style="text-align: center; font-weight:600;">'+result.resumen.total_fatantes+'<br/><small>'+result.resumen.porcentaje_faltantes+' %</small></td>';
						htmlRespuesta += '<td style="text-align: center; font-weight:600;">'+result.resumen.total+'</td>';
						htmlRespuesta += '</tr>';

						cargaGraficas(result.carteras)
					}
					
					divContenedor.html(htmlRespuesta)
					//botonRecargar.show()
				}
			})
		}

		function random_rgba() {
			var o = Math.round, r = Math.random, s = 255;
			return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',0.7)';
		}

		function cargaGraficas(carteras = null) {
			if (carteras === null) return;
			//return;
			const ctx = document.getElementById('chartGestionados').getContext('2d');
			const coloresGestionados = carteras.map(g => random_rgba())
			const chartGestionados = new Chart(ctx, {
					type: 'bar',
					data: {
							labels: carteras.map(g => g.cartera),
							datasets: [{
									label: '% gestionado',
									data: carteras.map(g => parseFloat(parseFloat(g.porcentaje_gestionados).toFixed(2))),
									backgroundColor: coloresGestionados,
									borderColor: coloresGestionados,
									borderWidth: 1
							}]
					},
					options: {
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
			});
	
			const ctx2 = document.getElementById('chartSinGestion').getContext('2d');
			const coloresSinGestion = carteras.map(g => random_rgba())
			const chartSinGestion = new Chart(ctx2, {
					type: 'bar',
					data: {
							labels: carteras.map(g => g.cartera),
							datasets: [{
									label: '% sin gestión',
									data: carteras.map(g => parseFloat(parseFloat(g.porcentaje_faltantes).toFixed(2))),
									backgroundColor: coloresGestionados,
									borderColor: coloresGestionados,
									borderWidth: 1
							}]
					},
					options: {
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
			});
		}
  </script>
</body>
</html>