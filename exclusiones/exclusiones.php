<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "crm,super,exclu"));
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
        <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">        
        <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">        
        <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
        <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
        <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
        <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
        <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
        <link href="../plugins/pace/pace.min.css" rel="stylesheet">
        <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
        <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet" media="screen">
        <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">        
        <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
        <link href="../css/global/global.css" rel="stylesheet">

        <style type="text/css">
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: rgba( 255, 255, 255, .8) url('../img/gears.gif') 50% 50% no-repeat;
            }
            
            body.loading {
                overflow: hidden;
            }
            
            body.loading .modal {
                display: block;
            }
            
            .dropdown-menu.open {
                max-height: none !important;
            }
            
            #Cierres td {
                border-top: 1px solid #CCCCCC;
            }
            
            #TablePlan {
                width: 100% !important;
            }
        </style>
    </head>
    <body>
        <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>">
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
                        <h1 class="page-header text-overflow">Exclusiones</h1>
                        <!--Searchbox-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                        <li><a href="#">Gestión</a></li>
                        <li><a href="#">Supervisores</a></li>
                        <li class="active"><a href="#">Exclusiones</a></li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                    <!--Page content-->
                    <!--===================================================-->
                    <div id="page-content">
                        <div class="row">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Exclusiones</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <button style="margin: 10px 0;" id="AddExclusion" class="btn btn-purple">Agregar</button>
                                        <br>
                                        <table class="table-responsive" id="ExclusionesTable">
                                            <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Dato</th>
                                                    <th>Fecha Inicio</th>
                                                    <th>Fecha Final</th>
                                                    <th>Descripción</th>
                                                    <th>Acción</th>
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
            </div>
            <!-- FOOTER -->
            <!--===================================================-->
            <?php include("../layout/footer.php"); ?>
            <!--===================================================-->
            <!-- END FOOTER -->
            <!-- SCROLL TOP BUTTON -->
            <!--===================================================-->
            <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
            <div class="modal">
                <!-- Place at bottom of page -->
            </div>
            <!--===================================================-->
        </div>
        <script id="ExclusionTemplate" type="text/template">
            <input type="hidden" id="id_registr" name="id_registr">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Tipo:</label>
                        <select class="selectpicker form-control" id="Tipo" name="Tipo" data-live-search="true" data-width="100%">
                            <option value="1">Rut</option>
                            <option value="2">Teléfono</option>
                            <option value="3">Correo</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Dato:</label>
                        <input type="text" class="form-control" id ="Dato" name="Dato">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Fecha: </label>
                        <input id="isInhibicion" name="isInhibicion" class="magic-checkbox" type="checkbox">
                        <label for="isInhibicion">Es inhibicion</label>
                        <div id="date-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control" id="Fecha_Inic" name="Fecha_Inic" />
                                <span class="input-group-addon Fecha_Term">a</span>
                                <input type="text" class="form-control Fecha_Term" id="Fecha_Term" name="Fecha_Term" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Descripción:</label>
                        <input type="text" class="form-control" id="Descripcio" name="Descripcio">
                    </div>
                </div>
            </div>
        </script>
        <!--===================================================-->
        <!-- END OF CONTAINER -->
        <!--JAVASCRIPT-->
        <script src="../js/jquery-2.2.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/nifty.min.js"></script>           
        <script src="../js/demo/nifty-demo.min.js"></script>            
        <script src="../js/demo/ui-alerts.js"></script>
        <script src="../plugins/fast-click/fastclick.min.js"></script>
        <script src="../plugins/morris-js/morris.min.js"></script>
        <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
        <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="../plugins/skycons/skycons.min.js"></script>
        <script src="../plugins/switchery/switchery.min.js"></script>
        <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
        <script src="../plugins/bootbox/bootbox.min.js"></script>
        <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
        <script src="../plugins/pace/pace.min.js"></script>
        <script src="../js/global/funciones-global.js"></script>
        <script src="../js/exclusiones/exclusiones.js"></script>
    </body>
</html>