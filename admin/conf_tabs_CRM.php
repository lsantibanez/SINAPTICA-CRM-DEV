<?php
include("../class/global/global.php");
require_once('../class/session/session.php');
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,gestion,conf_tabCR"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
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
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <style type="text/css">
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
        body.loading
        {
            overflow: hidden;
        }
        body.loading .modal
        {
            display: block;
        }
        .dropdown-menu.open {
            max-height: none !important;
        }
        .transparentInput{
            width: 100%;
            height: 100%;
            border: 0;
            text-align: center;
        }
            .transparentInput:focus{
                outline: 0;
            }
        .Hidden{
            display: none;
        }
        .PrioridadColumn{
            position: relative;
            text-align: center;
        }
    </style>
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
            <h1 class="page-header text-overflow">Configuración de tabs</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Configuración</a></li>
            <li><a href="#">Configuración CRM</a></li>
            <li class="active">Configuración de tabs</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
            <div id="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-3" style="padding: 12px;">
                                        <button id="AddTab" class="btn btn-success btn-block">
                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar nuevo tab
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding: 12px;">
                                        <table id="Tabs">
                                            <thead>
                                                <tr>
                                                    <th>Prioridad</th>
                                                    <th>Tab</th>
                                                    <th>Activo</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script id="TabTemplate" type="text/template">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input type="text" class="form-control" id="Tab" name="Tab">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Prioridad</label>
                                <input type="number" class="form-control" id="Prioridad" name="Prioridad">
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <script id="ColumnTemplate" type="text/template">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-success AddColumn">Agregar Columna</button>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table id="Columnas">
                            <thead>
                                <tr>
                                    <th>Prioridad</th>
                                    <th>Tabla</th>
                                    <th>Columna</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </script>
            <script id="AddColumnTemplate" type="text/template">
                <input type="hidden" name="IdTab" id="IdTab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Tabla</label>
                                <select class="selectpicker form-control" id="Tabla" name="Tabla" title="Seleccione" data-live-search="true" data-width="100%">
                                    <option value="Deuda">Deuda</option>
                                    <option value="Persona">Persona</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Columna</label>
                                <select class="selectpicker form-control" id="Columna" name="Columna" title="Seleccione" data-live-search="true" data-width="100%"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Prioridad</label>
                                <input type="number" class="form-control" id="Prioridad" name="Prioridad">
                            </div>
                        </div>
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
    <script src="../js/admin/config-tabs_CRM.js"></script>
</body>
</html>