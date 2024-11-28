<?php 
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

//include('../db/connect.php');
include('../class/email/opciones.php');
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//include("../email/cron-email-masivo.php");
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "crm,super,envioSMS"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>


    <!--STYLESHEET-->
    <!--=================================================-->

    
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <!--Custom CSS-->
    <style>
    #message{
        text-align: center;
        position: fixed;
        top:5px;
        left:50%;
        width:90%;
        z-index:99;
        max-width: 600px;
        transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -webkit-transform: translateX(-50%);
        display: none;
    }
    .select1
             {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;

             }
    .select2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;

            }
    .text1
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;

            }
    .text2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;

            }
    </style>
    <!--=================================================-->
</head>
<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <input type="hidden" name="SMSDisponibles" id="SMSDisponibles" value=""/>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
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
                    <h1 class="page-header text-overflow">Envío de SMS</h1>
                    <!--Searchbox-->
                    <!-- <div class="searchbox">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="text-muted" type="button"><i class="demo-pli-magnifi-glass"></i></button>
                            </span>
                        </div>
                    </div> -->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->

                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
					<li><a href="#">Gestión</a></li>
                    <li><a href="#">Supervisores</a></li>
					<li class="active"><a href="#">Envío de SMS</a></li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div id="message">
                        <div class="alert alert-info"></div>
                    </div>
					<div class="row">
					    <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <div class="panel-control ">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#demo-tabs-box-1" data-toggle="tab">Enviar</a></li>
                                            <li><a href="#demo-tabs-box-2" data-toggle="tab">Estadisticas</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="demo-tabs-box-1">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Seleccione Asignación</h3>
                                            </div>
                                            <div class="panel-body">
                                                <form>
                                                    <div class="row">                                                                                      
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="estrategia">Seleccione Estrategia</label>
                                                                <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="estrategia" name="estrategia">
                                                                    <option value="">Seleccione</option>
                                                                    <?php $estrategias = new opciones;
                                                                        echo $estrategias->estrategias(2);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="estrategia">Seleccione Cola</label>
                                                                <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="queue" name="queue">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group" id="templatepicker">
                                                                <label for="template">Seleccione Template</label>
                                                                <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="template" name="template">
                                                                    <?php $templates = new opciones;
                                                                        echo $templates->getTemplatesSMS();
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group" id="templatepicker">
                                                                <label for="template">Seleccione Colores</label>
                                                                <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="colores" name="colores" multiple>
                                                                    <?php $templates = new opciones;
                                                                        echo $templates->getColores();
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="cantidad-rut">Cant. de Rut</label>
                                                                        <input type="text" id="cantidad-rut" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="cantidad-fonos">Cant. de Fonos</label>
                                                                        <input type="text" id="cantidad-fonos" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title">Diseñar Mensaje</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div style="margin-bottom: 10px;">
                                                                <textarea class="form-control" rows="5" id="SMS" maxlength="160" disabled></textarea>
                                                            </div>
                                                            <button id="enviar-sms" class="btn btn-primary" type="button">Enviar</button>
                                                            <button id="clean-temp" class="btn btn-primary" type="button">Limpiar</button>
                                                            <h5 class="pull-right" id="count_message"></h5>
                                                            <br><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="demo-tabs-box-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Estadistica</span></h3>
                                            </div>
                                            <div class="panel-body">
                                                <form>
                                                    <div class="row">                                                                                      
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="estrategia">Seleccione Estrategia</label>
                                                                <select class="selectpicker" title="Seleccione" 
                                                                            data-live-search="true" data-width="100%" 
                                                                            id="estrategia_estadistica" name="estrategia_estadistica">
                                                                    <option value="">Seleccione</option>
                                                                    <?php $estrategias = new opciones;
                                                                        echo $estrategias->estrategias(2);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="estrategia">Seleccione Asignación</label>
                                                                <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                                            data-width="100%" id="asignacion_estadistica" name="asignacion_estadistica">
                                                                    <option value="">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="estrategia">Seleccione Fecha</label>
                                                                <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                                            data-width="100%" id="id_envio" name="id_envio">
                                                                    <option value="">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <h5 class="filtrarEstadistica text-hover" id="ENVIADO">Enviados: <span id="enviados">0</span></h5>
                                                                <h5 class="filtrarEstadistica text-hover" id="PENDIENTE">Pendientes: <span id="pendientes">0</span></h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <h5 class="filtrarEstadistica text-hover" id="ENTREGADO">Entregados: <span id="entregados">0</span></h5>
                                                                <h5 class="filtrarEstadistica text-hover" id="NO ENTREGADO">No Entregados: <span id="no_entregados">0</span></h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <h5 class="filtrarEstadistica text-hover" id="RECHAZADO">Rechazados: <span id="rechazados">0</span></h5>
                                                                <h5 class="filtrarEstadistica text-hover" id="ERROR DE PROVEEDOR">Error de Proveedor: <span id="error_proveedor">0</span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="row">
                                                    <div class="panel">                                
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title">SMS Enviados <button class="btn btn-primary pull-right" id="Download">Descargar</button></h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table" id="sms_enviados">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Rut</th>
                                                                            <th>Nombre</th>
                                                                            <th>Fono</th>
                                                                            <th>Estado</th>
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
                </div>
                <!--End page content-->
            </div>
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
        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <!--=================================================-->
    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>
    <!--Fast Click [ OPTIONAL ]-->
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <!--Nifty Admin [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>
<!--Switchery [ OPTIONAL ]-->
    <script src="../plugins/switchery/switchery.min.js"></script>
<!--Bootstrap Select [ OPTIONAL ]-->
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!--Summernote [ OPTIONAL ]-->
    <script src="../plugins/summernote/summernote.min.js"></script>
<!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
<!--SUMMERNOTE INITIATION-->
    <script src="../js/email/summernote-ini.js"></script><script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
<!-- Consultas -->
    <script src="../js/email/sms.js"></script>
</body>
</html>