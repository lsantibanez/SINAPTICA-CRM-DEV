<?php
    require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

    include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
    
    $objetoSession = new Session($Permisos,false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "speech,repSpeech"));
    // ** Logout the current user. **
    $objetoSession->creaLogoutAction();
    if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
    {
    //to fully log out a visitor we need to clear the session varialbles
        $objetoSession->borrarVariablesSession();
        $objetoSession->logoutGoTo("../index.php");
    }
    $validar = $_SESSION['MM_UserGroup'];
    $objetoSession->creaMM_restrictGoTo();
    $usuario = $_SESSION['MM_Username'];
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
        <!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">
          <!--Page Title-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <div id="page-title">
            <h1 class="page-header text-overflow">Reporte de Transcripciones</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Speech</a></li>
            <li class="active">Reporte de Transcripciones</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="panel">
                    <div class="panel-heading bg-primary">
                        <h2 class="panel-title">Reporte de Transcripciones</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Mes:</label>
                                    <select class="selectpicker form-control" title="Seleccione Mes"  name="Mes" data-live-search="true" data-width="100%"></select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label">&nbsp;</label>
                                    <button class="btn btn-primary form-control" id="Filtrar">Filtrar</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="DistribuidioresTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="dt-center">Distribuidor</th>
                                            <th class="dt-center">N° Transcripciones</th>
                                            <th class="dt-center">Ver Semanal</th>
                                            <th class="dt-center">Transcripciones</th>
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
            <div class="row">
                <div class="panel">
                    <div class="panel-heading bg-primary">
                        <h2 class="panel-title">Porcentaje de cumplimiento llamadas</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="ReportePorDistribuidor"></div>
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
        <script id="ReporteSemanalTemplate" type="text/template">
            <div id="ReporteSemanal" style="width: 100%;"></div>
        </script>
        <script id="VerDetalleTemplate" type="text/template">
            <div class="table-responsive">
                <table id="TranscripcionesTable" width="100%">
                    <thead>
                        <tr>
                            <th>Ver</th>
                            <th>Fecha/Hora</th>
                            <th>Palabras Claves</th>
                            <th>Palabras Encontradas</th>
                            <th>% Cumplimiento</th>
                            <th>Cumplimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </script>
        <script id="TranscripcionTemplate" type="text/template">
            <div class="row">
                {RECORD}
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Transcripción:</label>
                        <p class="form-control" id="TranscripcionText"></p>
                    </div>
                </div>
            </div>
        </script>

    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	<script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/morris_horizontal.min.js"></script>
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
    <script src="../plugins/audiojs/audio.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/speech/reporteSpeech.js"></script>
</body>
</html>