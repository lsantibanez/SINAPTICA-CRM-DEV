<?php 
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include('../class/email/opciones.php'); 
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,omnica,email_conf"));
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
$nombreUsuario = $_SESSION['nombreUsuario']; ?>
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
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <!--Summernote [ OPTIONAL ]-->
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">

    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">

    <!--Custom CSS-->
    <style>
    .bodyHeight {
        height: 300px;
    }
    </style>
    <!--=================================================-->


</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
        
        <!--NAVBAR-->
        <!--===================================================-->
        <?php include('../layout/header.php'); ?>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Configuración de Correo</h1>

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
                    <li><a href="#">Omnicanalidad</a></li>
                    <li><a href="#">MAIL</a></li>
                    <li class="active"><a href="#">Configuración de Correo</a></li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<?php
                        $tipoModulo = "0";
                        $config = new opciones();
                        $opciones = $config->configvalues("",$tipoModulo);
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Selección de tipo de Módulo</h3>
                                </div>
                                <div class="panel-body"> 
                                    <div class="form-group col-lg-4">
                                        <label class="control-label">Tipo de Módulo</label>
                                        <div>
                                            <select name="tipoModulo" class="selectpicker form-control"title="Seleccione" data-live-search="true" data-width="100%" >
                                                <?php
                                                    $Options = "";
                                                    switch($_SESSION["MM_UserGroup"]){
                                                        case "2":
                                                            $Options .= "<option value='0'>Modulo de Correo Masivo</option>";
                                                            $Options .= "<option value='2'>Envio de Facturas</opton>";
                                                        break;
                                                        case "5":
                                                            $Options .= "<option selected value='1'>Modulo de Reclutamiento</opton>";
                                                        default:
                                                            $Options .= "<option selected value='2'>Envio de Facturas</opton>";
                                                        break;
                                                    }
                                                    echo $Options;
                                                ?>
                                                
                                            </select>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
					    </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Configurar Servidor de Correo</h3>
                                </div>
                                <form class="panel-body bodyHeight form-horizontal form-padding"> 
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">SMTPSecure</label>
                                        <div class="col-md-9 pad-no">   
                                            <div class="radio">
                                                <label class="form-radio form-icon Secure"><input type="radio" value="1" name="secure"> SSL</label>
                                                <label class="form-radio form-icon Secure"><input type="radio" value="2" name="secure"> TLS</label>
                                                <label class="form-radio form-icon Secure"><input type="radio" value="0" name="secure"> None</label>
                                            </div> 
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Host</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="host" id="host">
                                        </div>
                                    </div>                               
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Puerto</label>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="port" id="port">
                                        </div>
                                    </div>
                                </form>  
                            </div>
					    </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Cuenta de Envío y Recepción</h3>
                                </div>
                                <form class="panel-body bodyHeight form-horizontal"> 
                                    <!--Email Input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-email-input">Email de envío</label>
                                        <div class="col-md-7">
                                            <input type="email" class="form-control" name="email" id="email" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-password-input">Contraseña</label>
                                        <div class="col-md-7">
                                            <input type="password" class="form-control" name="pass" id="pass" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-readonly-input">Remitente</label>
                                        <div class="col-md-7">
                                            <input type="email" class="form-control" id="from" >
                                        </div>
                                    </div>   
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-readonly-input">Nombre remitente</label>
                                        <div class="col-md-7">
                                            <input type="Text" class="form-control" id="fromname">
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-email-input">Email de confirmación</label>
                                        <div class="col-md-7">
                                            <input type="email" class="form-control" name="ConfirmReadingTo" id="ConfirmReadingTo" >
                                        </div>
                                    </div>       
                                </form>
                            </div>
                        </div>
					</div>					
					<div class="col-lg-12">
                        <div class="text-left">
                            <button class="save-conf btn btn-primary" type="button">Guardar</button>
                        </div>
                    </div>
                </div>
                <!--End page content-->
            </div>
            <!--END CONTENT CONTAINER-->
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <?php include('../layout/main-menu.php'); ?>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
        </div>
        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                You have <a href="#" class="text-bold text-main"><span class="label label-danger">3</span> pending action.</a>
            </div>
            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="hide-fixed pull-right pad-rgt">
                <!-- 14GB of <strong>512GB</strong> Free. -->
            </div>
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- <p class="pad-lft">&#0169; 2016 Your Company</p> -->
        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    
    <!--JAVASCRIPT-->
    <!--=================================================-->
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>
    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>
    <!--Switchery [ OPTIONAL ]-->
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/email/configuracion.js"></script>
    <script src="../js/email/email.js"></script>
</body>
</html>