<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
include("../class/calidad/calidad.php");

$CalidadClass = new Calidad();

//echo $CalidadClass->getRutaGrabaciones("20170725-185812_995883252_013_mrivero-all.mp3");

$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "gra,oper,gest"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$mandante = (int) $_SESSION['mandante'];
if(isset($_SESSION['cedente'])){
    if($_SESSION['cedente'] != ""){
        $cedente = $_SESSION['cedente'];
    }
}

$db = new Db();
$usuarios = $db->select("SELECT usuario, nombre FROM Usuarios WHERE nivel = 3 AND FIND_IN_SET('{$cedente}', Id_Cedente) ORDER BY usuario ASC;");

$carteras = $db->select('SELECT c.Id_Cedente AS id, c.Nombre_Cedente AS nombre FROM Cedente AS c JOIN mandante_cedente AS m ON m.Id_Cedente = c.Id_Cedente WHERE m.Id_Mandante = '.$mandante.' AND m.activo = 1 ORDER BY c.Nombre_Cedente ASC;');

$oneMonth = new \DateInterval('P1M');
$otoday = new \DateTime();
$noMenosDe = $otoday->sub($oneMonth)->format('Y-m-d');

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
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../css/global/global.css" rel="stylesheet">
    <style>
        #ListenRecord{
            text-align: center;
            font-size: 30px;
        }
        #ListenRecord i{
            cursor: pointer;
        }
        #ListenRecord i:hover{
            color: green;
        }   
    </style>
</head>
<body>
    <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente ?>">
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
                    <h1 class="page-header text-overflow">Reporte de gestiones</h1>
                    <!--Searchbox-->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Reportes</a></li>
                    <li><a href="#">Operaciones</a></li>
	                <li class="active">Reporte de gestiones</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <?php if ($mandante == 99) { ?>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                  <h3 style="margin-top: 0;">Exportar</h3>
                                  <div class="row" style="padding: 0 10px;">
                                    <div class="col-md-3">
                                      <h6>Fecha de las gestiones</h6>
                                      <input type="date" class="form-control" style="line-height: 5px;" title="Fecha gestión" name="fecha_exportar" id="fecha_exportar" value="<?php echo date('Y-m-d'); ?>" />
                                    </div>
                                    <div class="col-md-3">
                                      <h6>BPO</h6>
                                      <button class="btn btn-primary btn-block" type="button" onclick="exportarReporte('bpo-gestiones', this)">Generar</button>
                                    </div>
                                    <div class="col-md-3">
                                      <h6>Emerix</h6>
                                      <button class="btn btn-primary btn-block" type="button" onclick="exportarReporte('emx-acciones', this)">Generar acciones</button>
                                      <button class="btn btn-primary btn-block" type="button" onclick="exportarReporte('emx-promesas', this)">Generar promesas</button>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Extras, ruts faltantes de datos</h6>
                                        <button class="btn btn-danger btn-block" type="button" onclick="exportarReporte('ext-sintelefonos', this)">
                                            Sin teléfonos
                                        </button>
                                        <button class="btn btn-danger btn-block" type="button" onclick="exportarReporte('ext-sinemails', this)">
                                            Sin emails
                                        </button>
                                        <button class="btn btn-danger btn-block" type="button" onclick="exportarReporte('ext-sindirecciones', this)">
                                            Sin direcciones
                                        </button>
                                    </div>
                                  </div><!-- row 1 -->
                                  <div class="row" style="padding: 0 10px;">
                                    <div class="col-lg-12">
                                        <h4>Ruts sin gestiones</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <?php if (count((array) $carteras) > 0) { ?>
                                        <select name="carteraSinGestion" id="carteraSinGestion" class="form-control">
                                        <?php foreach((array) $carteras as $cartera) { ?>
                                            <option value="<?php echo $cartera['id'].'|'.mb_strtoupper($cartera['nombre']); ?>" <?php if($cartera['id'] == $cedente) echo 'selected'; ?>><?php echo $cartera['nombre']; ?></option>
                                        <?php } ?>
                                        </select>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary btn-block" type="button" onclick="exportarReporte('ext-singestion', this)">
                                            Generar sin gestiones
                                        </button>
                                    </div>
                                  </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body" style="padding: 20px 35px;">
                                    <h3 style="margin-top: 0;">Búsquedas</h3>
                                    <form action="" onsubmit="return false;" name="busquedaGestiones" id="busquedaGestiones">
                                        <div class="row" style="margin-bottom: 15px;">
                                            <div class="col-md-2">
                                                <h6 style="margin-left: 5px;">Rut</h6>
                                                <input type="text" class="form-control" name="rut" id="rut" placeholder="Rut de cliente" title="Rut del cliente">
                                            </div>
                                            <div class="col-md-2">
                                                <h6 style="margin-left: 5px;">Nro. Teléfono</h6>
                                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Nro. Teléfono" title="Nro. de teléfono">
                                            </div>
                                            <div class="col-md-4">
                                                <h6 style="margin-left: 5px;">Fecha de gestión</h6>
                                                <div class="input-group">
                                                    <input type="date" name="fecha_desde" class="form-control" style="line-height: 25px;border-top-right-radius: 0 !important;border-bottom-right-radius: 0 !important;border-right: none !important;padding: 0;" value="<?php echo date('Y-m') ?>-01" min="<?php echo $noMenosDe; ?>">
                                                    <span class="input-group-addon" style="background-color: #efefef;">-</span>
                                                    <input type="date" name="fecha_hasta" class="form-control" value="<?php echo date('Y-m-d') ?>" min="<?php echo $noMenosDe; ?>" style="line-height: 25px;padding:0; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; border-left: none !important;">
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row" style="margin-bottom: 15px;">
                                            <div class="col-md-4">
                                                <h6 style="margin-left: 5px;">Tipo de gestión</h6>
                                                <select name="nivel_1" id="nivel_1" size="1" class="form-control" onchange="getLevel2(this.value)">
                                                    <option value="">-- Todas --</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 style="margin-left: 5px;">Tipo contacto</h6>
                                                <select name="nivel_2" id="nivel_2" size="1" class="form-control" onchange="getLevel3(this.value)">
                                                    <option value="">-- Todas --</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 style="margin-left: 5px;">Respuesta</h6>
                                                <select name="nivel_3" id="nivel_3" size="1" class="form-control">
                                                    <option value="">-- Todas --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 20px;">
                                                                                
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 style="margin-left: 5px;">Cartera(s)</h6>
                                                <div class="checkbox row" style="padding: 0 15px;">
                                                <?php 
                                                if (count((array) $carteras) > 0) {
                                                    foreach((array) $carteras as $cartera) {
                                                ?>
                                                    <label style="width: 160px; display:block; float:left; margin-bottom: 5px;">
                                                        <input type="checkbox" id="proyecto_<?php echo (int) $cartera['id']; ?>" name="proyectos" value="<?php echo $cartera['id']; ?>" <?php if ((int) $cedente == (int) $cartera['id']) echo 'checked'; ?> >
                                                        <?php echo $cartera['nombre']; ?>
                                                    </label>
                                                    <?php } } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 style="margin-left: 5px;">&nbsp;</h6>
                                                <button type="button" id="BuscarGestiones" class="btn btn-block btn-success">
                                                    <i class="fa fa-search"></i>&nbsp;&nbsp;Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row" style="padding: 12px;">
                                        <div class="col-md-12">
                                            <table id="GestionesTable" class="display" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Fecha/Hora Gestión</th>
                                                        <th>Cartera</th>
                                                        <th>RUT</th>
                                                        <th>Nombre Ejecutivo</th>
                                                        <th>Fono Discado</th>
                                                        <th>Respuesta</th>
                                                        <th>Sub Respuesta</th>
                                                        <th>Sub Respuesta</th>
                                                        <th>Fecha Compromiso</th>
                                                        <th>Monto Compromiso</th>
                                                        <th>Observación</th>
                                                        <th>Escuchar</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div><!-- row -->
                                    <div class="row" style="padding: 12px; display: none;" id="btnDescarga">
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-block btn-primary" id="Download" title="Descargar resultados en csv">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Descargar en CSV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script id="ListenRecordTemplate" type="text/template">
                    <div class="row">
                        <div class="cols-sm-1">
                            <div class="Record" style="text-align: center;">
                                {RECORD_AUDIO}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cols-sm-1" style="text-align:center; padding-top: 10px;">
                            <button type="button" class="btn btn-primary" onclick="bajarAudio('{RECORD_URL}', this)">Descargar</button>
                        </div>
                    </div>
                </script>
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
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                <ul class="footer-list list-inline"></ul>
            </div>
        </footer>
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
    <script src="../js/funciones.js"></script>
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
    <script src="../plugins/audiojs/audio.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/reporteria/ReporteGestiones.js"></script>
    <script>
        function bajarAudio(enlace, e) {
            e.disabled = true
            console.log('Audio: ', enlace);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = enlace;
            // the filename you want
            a.download = enlace.split('/').pop();
            a.target = '_blank'
            document.body.appendChild(a);
            a.click(); 
                /*
            fetch(enlace)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // the filename you want
                a.download = 'todo-1.json';
                document.body.appendChild(a);
                a.click();
                e.disabled = false
                window.URL.revokeObjectURL(url); // or you know, something with better UX...
            })
            .catch(() => { 
                alert('No ha sido posible obtener el archivo de audio.')
                e.disabled = false
            });
            */
        }

        function exportarReporte(tipo, btn) {
          btn.disabled = true
          var fecha = $('#fecha_exportar').val();
          console.log('Fecha exportar: ', fecha)
          datos = new FormData();
          datos.append('fecha', fecha);
          var url = '/includes/reporteria/export/'+tipo
          var filename = 'reporte.csv'
          if (tipo == 'bpo-gestiones') filename = `BUSINESSPRO_GESTION_CASTIGO_${fecha.replace(/-/gi,'')}.txt`
          if (tipo == 'emx-acciones') filename = `Acciones_BUSINESSPRO_${fecha.replace(/-/gi,'')}.csv`
          if (tipo == 'emx-promesas') filename = `Promesas_BUSINESSPRO_${fecha.replace(/-/gi,'')}.csv`
          if (tipo == 'ext-sintelefonos') filename = `RUTS_SIN_TELEFONOS.csv`
          if (tipo == 'ext-sinemails') filename = `RUTS_SIN_EMAILS.csv`
          if (tipo == 'ext-sindirecciones') filename = `RUTS_SIN_DIRECCIONES.csv`
          if (tipo == 'ext-singestion') {
            const cartera = $('select#carteraSinGestion').val().split('|');
            filename = `RUTS_SIN_GESTIONES_${cartera[1]}.csv`
            datos.append('idCartera', cartera[0]);
            datos.append('nombreCartera', cartera[1]);
          }
          console.log(tipo, filename)

          fetch(url, { method: "POST", body: datos })
          .then(response => response.blob())
          .then(data => { 
            download(data, filename);
            btn.disabled = false
          }).catch(() => {
            btn.disabled = false
          })
        }

        function download(blob, filename) {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
          }
    </script>
</body>
</html>