<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "modSup,supRepDisc"));
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
if(isset($_SESSION['cedente'])){
    if($_SESSION['cedente'] != ""){
      $cedente = $_SESSION['cedente'];
    }
}
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
</head>
<body>
  <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>">
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
            <h1 class="page-header text-overflow">Reporte Discador</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Supervisión</a></li>
            <li class="active">Reporte Discador</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="eq-height">
                    <div class="col-sm-12">
                        <div class="panel" >
                            <div class="panel-heading bg-primary">
                                <div class="panel-control ">
                                  <ul class="nav nav-tabs">
                                      <li class="active"><a href="#demo-tabs-box-1" data-toggle="tab">Anexos </a></li>
                                      <li><a href="#demo-tabs-box-2" data-toggle="tab">Listas</a></li>
                                  </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="demo-tabs-box-1">
                                    <div class="panel-body">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Seleccione Anexo:</label>
                                                <select class="selectpicker form-control" name="Anexo" title="Seleccione" data-live-search="true" data-width="100%">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2"  id="divBuscar" style="padding-top: 5px;">
                                            <div class="form-group">
                                                <label class="control-label"></label>
                                                <input type="submit" class="btn btn-primary btn-block" value="Buscar" id="buscar_anexo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                      <div class="panel-heading bg-primary">
                                        <h2 class="panel-title">Datos Generales Anexo</h2>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label class="control-label">Estatus</label>
                                            <input type="text" id="status" value="" disabled="disabled" background-color="#FFFFFF"  class="form-control">
                                          </div>
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label class="control-label">Useragent</label>
                                            <input type="text" id="useragent" value="" disabled="disabled" background-color="#FFFFFF"  class="form-control">
                                          </div>
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label class="control-label">Reg. Contact</label>
                                            <input type="text" id="regContact" value="" disabled="disabled" background-color="#FFFFFF"  class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-sm-12">
                                      <div class="panel-heading bg-primary">
                                        <h2 class="panel-title">Datos Colas Anexo</h2>
                                      </div>
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table id="mostrar_queue_anexo">
                                            <thead>
                                              <tr>
                                                <th>Queue</th>
                                                <th>Anexo</th>
                                                <th>Quitar Anexo</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="demo-tabs-box-2">
                                    <div class="panel-body">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Seleccione Lista:</label>
                                                <select class="selectpicker form-control" name="Lista" title="Seleccione" data-live-search="true" data-width="100%">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2"  id="divBuscar" style="padding-top: 5px;">
                                            <div class="form-group">
                                                <label class="control-label"></label>
                                                <input type="submit" class="btn btn-primary btn-block" value="Buscar" id="buscar_lista">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="col-sm-6">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">Lista Miembros</h2>
                                        </div>
                                        <div class="panel-body">
                                          <div class="table-responsive">
                                            <table id="mostrar_miembros">
                                              <thead>
                                                <tr>
                                                  <th>Miembros</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-sm-6">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">Lista Llamadas</h2>
                                        </div>
                                        <div class="panel-body">
                                          <div class="table-responsive">
                                            <table id="mostrar_llamadas">
                                              <thead>
                                                <tr>
                                                  <th>Llamadas</th>
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
                            </div>
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
    <script id="modalPuestosTrabajo" type="text/template">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  <table id="puestosTrabajo" style="width:100%">
                    <thead>
                      <tr>
                        <th>Puesto</th>
                        <th>Ejecutivo</th>
                        <th>Tiempo de conexión</th>
                        <th>Pausa</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </script>
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
    <script src="../js/supervision/supervisionReporteDiscador.js"></script>
</body>
</html>